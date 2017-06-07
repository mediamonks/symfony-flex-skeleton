<?php

namespace Installer;

use Composer\Script\Event;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;

class Skeleton
{
    const CHAR_LIST_URL_SAFE = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const CHAR_LIST_PASSWORD = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+~';
    const LENGTH_SECRET = 40;
    const LENGTH_ADMIN_DIRECTORY = 16;
    const LENGTH_ENCRYPTION_KEY = 64;

    const COLOR_DEFAULT = "\e[1;37m\e[0m";
    const COLOR_GREEN = "\e[1;37m\e[42m";
    const COLOR_RED = "\e[1;37m\e[41m";
    const COLOR_CYAN = "\e[1;37m\e[46m";
    const COLOR_BLUE = "\e[1;37m\e[44m";
    const COLOR_PURPLE = "\e[1;37m\e[45m";

    const FILE_GIT_ORIG_HEAD = '.git/ORIG_HEAD';
    const GIT_COMMAND_VERSION = 'git rev-parse HEAD';
    const BUILD_WRAPPER = '{build_%s}';
    const REVISION_UNKNOWN = 'unknown';

    /**
     * @param Event $event
     */
    public static function postCreateProject(Event $event)
    {
        $package = $event->getComposer()->getPackage();
        $parametersFile = self::getPathFromApp('config/parameters.yml');
        $readmeFile = self::getPathFromTools('../README.md');

        if($package->getStability() !== 'stable') {
            $question = sprintf('The stability of this release is '.self::COLOR_RED.'%s'.self::COLOR_GREEN.' instead of '.self::COLOR_PURPLE.'stable'.self::COLOR_GREEN.'. Are you sure you want to continue?', $package->getStability());
            $answer = self::askQuestion($question, 'y/n');
            if($answer === 'n') {
                self::echoString('Aborting installation', self::COLOR_RED);
                exit;
            }
        }

        $revision = trim(exec(self::GIT_COMMAND_VERSION));
        if (empty($revision) && file_exists(self::getPathFromTools('../' . self::FILE_GIT_ORIG_HEAD))) {
            $revision = trim(file_get_contents(self::getPathFromTools('../' . self::FILE_GIT_ORIG_HEAD)));
        }
        if (empty($revision)) {
            $revision = self::REVISION_UNKNOWN;
        }

        $data = [
            'created'   => (new \DateTime)->format(\DateTime::RFC3339),
            'name'      => $package->getPrettyName(),
            'version'   => $package->getVersion(),
            'revision'  => $revision,
            'stability' => $package->getStability()
        ];

        self::writeToMeta('package', $data);
        self::replaceInFile(
            $readmeFile,
            array_map(function($value) { return sprintf(self::BUILD_WRAPPER, $value); }, array_keys($data)),
            array_values($data)
        );

		self::echoWelcome((isset($event->getArguments()[0]) && $event->getArguments()[0] === 'marco'));
        self::copyFile(self::getPathFromApp('config/parameters.yml.dist'), $parametersFile);

        exec('cd source/symfony && composer install --ignore-platform-reqs');
        $process = new Process('cd source/symfony && php bin/symfony_requirements');
        $process->run();
        echo $process->getOutput();
        self::askQuestion('Did the requirement checker approve your system?', 'y', true);

        $process = new Process('cd source/symfony && php vendor/sensiolabs/security-checker/security-checker security:check');
        $process->run();
        echo $process->getOutput();
        self::askQuestion('Did the security checker approve all used packages?', 'y', true);

        hostname:
		$hostname = self::askQuestion('Project (vagrant) hostname (.local will be added)');
        $hostname .= '.local';
        if(!preg_match('~^([a-z0-9-]+.local)$~', $hostname)) {
            self::echoString('Hostname should only contain a-z, 0-9 and dashes', self::COLOR_RED);
            goto hostname;
        }

        ipaddress:
        $ipaddress = self::askQuestion('Project (vagrant) ip address (192.168.33. prefix will be added)', random_int(2, 255));
        if ((int)$ipaddress >= 2 && (int)$ipaddress <= 255) {
            $ipaddress = '192.168.33.' . $ipaddress;
        } else {
            self::echoString('Please choose a number between 2 and 255', self::COLOR_RED);
            goto ipaddress;
        }

        $composerCacheDir = self::askQuestion('Composer cache directory', '~');

        phpversion:
        $phpVersion = self::askQuestion('PHP Version 5.6 or 7.0', '7.0');
        if (!in_array($phpVersion, ['5.6', '7.0'])) {
            self::echoString('Please choose version 5.6 or 7.0', self::COLOR_RED);
            goto phpversion;
        }

        if ($phpVersion === '5.6') {
            echo self::executeProcess('cd source/symfony && composer require symfony/symfony:^3.2 --no-update');
            echo self::executeProcess('cd source/symfony && composer require twig/twig:^1.28 --no-update');
            echo self::executeProcess('rm -rf source/symfony/var/cache');
            echo self::executeProcess('cd source/symfony && composer update');
        }

        $brandName = self::askQuestion('Name of the brand');
        $projectName = self::askQuestion('Name of the project');

        $titleNew = sprintf('%s %s', $brandName, $projectName);
        self::replaceInFile(self::getPathFromApp('config/sonata/admin.yml'), 'Skeleton', $titleNew);
        self::replaceInFile(self::getPathFromApp('../src/App/FrontEndBundle/Resources/views/layout.html.twig'), 'MediaMonks Symfony Skeleton', $titleNew);
        self::replaceInFile(self::getPathFromTools('docker/Dockerfile'), '7.0', $phpVersion);

        $content = 'hostname: ' . $hostname . PHP_EOL;
        $content .= 'ip_address: ' . $ipaddress . PHP_EOL;
        $content .= 'composer_cache_dir: ' . $composerCacheDir . PHP_EOL;
        self::putContentInFile($content, self::getPathFromTools('vagrant/config.yml'));
        self::replaceInFile(self::getPathFromTools('vagrant/config.yml.dist'), 'symfony-skeleton.local', $hostname);

        self::writeToMeta('brand', $brandName);
        self::writeToMeta('project', $projectName);
        self::replaceInFile($readmeFile, [
            '{brand_name}', '{project_name}'
        ], [$brandName, $projectName]);

        // Assembla
        $assembla = self::askQuestion('Is there an Assembla space for this project?', 'y');
        if ($assembla === 'y') {
            assemblaUrl:
            $assemblaUrl = self::askQuestion('Please enter your Assembla space url');
            if(!preg_match('~^https://(www|mediamonks)\.assembla\.com/spaces/.+/$~', $assemblaUrl)) {
                self::echoString('Assembla space url should look something like https://mediamonks.assembla.com/spaces/<project_space_name>/', self::COLOR_RED);
                goto assemblaUrl;
            }
            $urlData = explode('/', rtrim($assemblaUrl, '/'));
            $assemblaName = end($urlData);
            self::writeToMeta('assembla', ['name' => $assemblaName, 'url' => $assemblaUrl]);
            self::replaceInFile($readmeFile, ['{assembla_space_name}', '{assembla_space_url}'], [$assemblaName, $assemblaUrl]);

        } else {
            self::removeLinesFromFile($readmeFile, range(3,7));
        }

        // Parameters
        $secret = self::generateRandomString(self::LENGTH_SECRET, self::CHAR_LIST_URL_SAFE);
        self::echoString('secret: ' . PHP_EOL . $secret, self::COLOR_CYAN);
        self::replaceParameter($parametersFile, 'secret', $secret);

        $admin_directory = self::generateRandomString(self::LENGTH_ADMIN_DIRECTORY, self::CHAR_LIST_URL_SAFE);
        self::echoString('admin_directory: ' . PHP_EOL . $admin_directory, self::COLOR_CYAN);
        self::replaceParameter($parametersFile, 'admin_directory', $admin_directory);

        $encryption_key = self::generateRandomString(self::LENGTH_ENCRYPTION_KEY, self::CHAR_LIST_URL_SAFE);
        self::echoString('encryption_key: ' . PHP_EOL . $encryption_key, self::COLOR_CYAN);
        self::replaceParameter($parametersFile, 'encryption_key', $encryption_key);

        // Database
        db:
        $dbHost = self::askQuestion('Database host', 'norbu.mediamonks.local');
        $dbPort = self::askQuestion('Database port', '3306');
        $dbUser = self::askQuestion('Database user', 'root');
        $dbPassword = self::askQuestion('Database password', null, false, false);

        $dbName = null;
        $assemblaData = self::getMeta('assembla');
        if(!empty($assemblaData['name'])) {
            $dbName = $assemblaData['name'];
        } else {
            $dbName = self::normalizeString($brandName . $projectName);
        }

        dbname:
        $dbName = self::askQuestion('Database name', $dbName);
        try {
            self::testDbCredentials($dbHost, $dbPort, $dbUser, $dbPassword, $dbName);
        } catch (\Exception $e) {
            self::echoString($e->getMessage(), self::COLOR_RED);
            if (strpos($e->getMessage(), 'already exists') !== false) {
                self::echoString(sprintf('Database with name "%s" already exists', $dbName), self::COLOR_RED);
                goto dbname;
            }
            goto db;
        }

        self::replaceParameter(self::getPathFromApp('config/parameters_local.yml'), 'database_host', $dbHost);
        self::replaceParameter(self::getPathFromApp('config/parameters_local.yml'), 'database_port', $dbPort);
        self::replaceParameter(self::getPathFromApp('config/parameters_local.yml'), 'database_user', $dbUser);
        self::replaceParameter(self::getPathFromApp('config/parameters_local.yml'), 'database_password', $dbPassword);
        self::replaceParameter(self::getPathFromApp('config/parameters_local.yml'), 'database_name', $dbName);

        self::createDatabase($dbHost, $dbPort, $dbUser, $dbPassword, $dbName);
        self::createSchema();
        self::createSessionTable($dbHost, $dbPort, $dbUser, $dbPassword, $dbName);

        // Users
        $project = self::getMeta('project');
        $users = [];
        $users[] = self::createUser(
            self::normalizeString($project) . 'SuperAdmin',
            'super admin',
            ['ROLE_SUPER_ADMIN']
        );
        $users[] = self::createUser(
            self::normalizeString($project) . 'Admin',
            'admin'
        );
        $assemblaMeta = self::getMeta('assembla');
        if(!empty($assemblaMeta)) {
            self::echoString('Copy the user table below to a new wiki page called '.self::COLOR_PURPLE.'Admin_Credentials'.self::COLOR_CYAN.' on Assembla:', self::COLOR_CYAN);
            self::echoString(sprintf('%swiki/new', $assemblaMeta['url']), self::COLOR_CYAN);
            self::echoString('(make sure to set the visibility to '.self::COLOR_PURPLE.'Private'.self::COLOR_CYAN.' and the markup format to '.self::COLOR_PURPLE.'markdown'.self::COLOR_DEFAULT.')', self::COLOR_CYAN);
        } else {
            self::echoString('Copy the user table below to a secure location:', self::COLOR_CYAN);
        }

        $userValues = [];
        foreach($users as $user) {
            $userValues[] = array_values($user);
        }
        echo (new TextTable(['Type', 'Username', 'Password'], $userValues))->render();

        if(!empty($assemblaMeta)) {
            self::askQuestion('Did you create the page?' , 'y', true);
        }

        $cleanup = self::askQuestion('Remove installer?', 'y');
        if ($cleanup === 'y') {
            unlink(self::getPathFromTools('../Skeleton.php'));
            rmdir(self::getPathFromTools('../vendor'));
        }
    }

    private static function normalizeString($value) {
        return str_replace(' ', '', preg_replace('/[^a-zA-Z0-9]+/', '', $value));
    }

	private static function echoWelcome($marco) {
		echo "\033[33m";
        echo "=============================================================================" . PHP_EOL;
        echo "=---------------------------------------------------------------------------=" . PHP_EOL;
        echo "=============================================================================" . PHP_EOL;
		echo "                            _ _                          _                   " . PHP_EOL;
		echo "             /\/\   ___  __| (_) __ _  /\/\   ___  _ __ | | _____            " . PHP_EOL;
		echo "            /    \ / _ \/ _` | |/ _` |/    \ / _ \| '_ \| |/ / __|           " . PHP_EOL;
		echo "           / /\/\ \  __/ (_| | | (_| / /\/\ \ (_) | | | |   <\__ \\           " . PHP_EOL;
		echo "           \/    \/\___|\__,_|_|\__,_\/    \/\___/|_| |_|_|\_\___/           " . PHP_EOL;
		echo "                                                                             " . PHP_EOL;
		echo "                      __ _        _      _                                   " . PHP_EOL;
		echo "                     / _\ | _____| | ___| |_ ___  _ __                       " . PHP_EOL;
		echo "                     \ \| |/ / _ \ |/ _ \ __/ _ \| '_ \                      " . PHP_EOL;
		echo "                     _\ \   <  __/ |  __/ || (_) | | | |                     " . PHP_EOL;
		echo "                     \__/_|\_\___|_|\___|\__\___/|_| |_|                     " . PHP_EOL;
		echo "                                                                             " . PHP_EOL;
		if ($marco) {
		    self::echoMarco();
        } else {
		    self::echoStandard();
        }
		echo "                                                                             " . PHP_EOL;
        echo "=============================================================================" . PHP_EOL;
        echo "=---------------------------------------------------------------------------=" . PHP_EOL;
        echo "=============================================================================" . PHP_EOL . PHP_EOL;
	}

	private static function echoMarco() {
        echo "              __                              ___   __        .ama     ,     " . PHP_EOL;
        echo "           ,d888a                          ,d88888888888ba.  ,88'I)   d      " . PHP_EOL;
        echo "          a88']8i                         a88'.8'8)   `'8888:88  ' _a8'      " . PHP_EOL;
        echo "        .d8P' PP                        .d8P'.8  d)      '8:88:baad8P'       " . PHP_EOL;
        echo "       ,d8P' ,ama,   .aa,  .ama.g ,mmm  d8P' 8  .8'        88):888P'         " . PHP_EOL;
        echo "      ,d88' d8[ '8..a8'88 ,8I'88[ I88' d88   ]IaI'        d8[                " . PHP_EOL;
        echo "      a88' dP 'bm8mP8'(8'.8I  8[      d88'    `'         .88                 " . PHP_EOL;
        echo "     ,88I ]8'  .d'.8     88' ,8' I[  ,88P ,ama    ,ama,  d8[  .ama.g         " . PHP_EOL;
        echo "     [88' I8, .d' ]8,  ,88B ,d8 aI   (88',88'8)  d8[ '8. 88 ,8I'88[          " . PHP_EOL;
        echo "     ]88  `888P'  `8888' '88P'8m'    I88 88[ 8[ dP 'bm8m88[.8I  8[           " . PHP_EOL;
        echo "     ]88,          _,,aaaaaa,_       I88 8'  8 ]P'  .d' 88 88' ,8' I[        " . PHP_EOL;
        echo "     `888a,.  ,aadd88888888888bma.   )88,  ,]I I8, .d' )88a8B ,d8 aI         " . PHP_EOL;
        echo "       '888888PP''        `8''''''8   '888PP'  `888P'  `88P'88P'8m'          " . PHP_EOL;
    }

	private static function echoStandard() {
        echo "                                   _, . '__ .                                " . PHP_EOL;
        echo "                                '_(_0o),(__)o().                             " . PHP_EOL;
        echo "                              ,o(__),_)o(_)O,(__)o                           " . PHP_EOL;
        echo "                            o(_,-o(_ )(),(__(_)oO)_                          " . PHP_EOL;
        echo "                            .O(__)o,__).(_ )o(_)Oo_)                         " . PHP_EOL;
        echo "                        .----|^^^|^^^|^^^|^^^|^^^|_)0                        " . PHP_EOL;
        echo "                       /  .--|= =|= =|= =|= =|= =|,_)                        " . PHP_EOL;
        echo "                      |  /   |===|=o=|===|0==|=o=|o(_)                       " . PHP_EOL;
        echo "                      |  |   |=0=|===|===|===|o==|_/`)                       " . PHP_EOL;
        echo "                      |  |   |===|===|===|===|===|O_)                        " . PHP_EOL;
        echo "                      |  |   |==o|===|=0=|==o|===|                           " . PHP_EOL;
        echo "                      |  \   |=o=|===|===|o==|==0|                           " . PHP_EOL;
        echo "                       \  '--|===|===|=o=|===|===|                           " . PHP_EOL;
        echo "                        '----|===|===|==o|===|o==|                           " . PHP_EOL;
        echo "                             |==0|===|===|===|===|                           " . PHP_EOL;
        echo "                             \===\===\===/===/===/                           " . PHP_EOL;
        echo "                              `-----------------`                            " . PHP_EOL;
    }

    public static function generateRandomString($length = 10, $characters) {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

	private static function askQuestion($question, $default = null, $matchDefault = false, $required = true) {
        ask:
        $fullQuestion = " " . $question . (!empty($default) ? " (".$default.")" : "") . ": ";
		echo self::COLOR_GREEN . $fullQuestion . self::COLOR_DEFAULT . " ";
		$handle = fopen ("php://stdin","r");
		$answer = trim(fgets($handle));
		if(empty($answer)){
            if ($default === null && $required) goto ask;
            $answer = $default;
		}
        if ($matchDefault && ($answer !== $default)) {
            self::echoString('Abort!', self::COLOR_RED);
            exit;
        }
        self::echoString($answer);

		return $answer;
	}

	private static function echoString($string, $color = "\e[1;37m\e[0m") {
        if ($string === null) $string = 'null';
        echo $color . $string . self::COLOR_DEFAULT . PHP_EOL . PHP_EOL;
	}

	private static function replaceInFile($filename, $search, $replace) {
        file_put_contents($filename, str_replace(
            $search,
            $replace,
            file_get_contents($filename)
        ));
    }

    // Relative from /app
    private static function getPathFromApp($filename) {
        return __DIR__ . '/source/symfony/app/' . $filename;
    }

    // Relative from /app
    private static function getPathFromTools($filename) {
        return __DIR__ . '/tools/' . $filename;
    }

    private static function copyFile($source, $destination) {
        $filesystem = new Filesystem();
        $filesystem->copy($source, $destination, true);
    }

    private static function putContentInFile($content, $filename)
    {
        file_put_contents($filename, $content);
    }

    private static function getMeta($key = null) {
        if(!file_exists(self::getPathFromTools('../meta.json'))) { return []; }
        $data = json_decode(file_get_contents(self::getPathFromTools('../meta.json')), true);

        if(empty($key)) { return $data; }
        if(empty($data[$key])) { return null; }

        return $data[$key];
    }

    private static function writeToMeta($key, $value) {
        $meta = self::getMeta();
        $meta[$key] = $value;
        file_put_contents(self::getPathFromTools('../meta.json'), json_encode($meta));
    }

    private static function removeLinesFromFile($file, array $ignore)
    {
        $lines = [];
        foreach(file($file) as $i => $line) {
            if(!in_array($i, $ignore)) {
                $lines[] = $line;
            }
        }
        file_put_contents($file, implode('', $lines));
    }

    private static function replaceParameter($file, $name, $value)
    {
        $parameters = [];
        foreach (file($file) as $line) {
            if (strpos($line, $name . ':') !== false) {
                $lineData    = explode(':', $line);
                $lineData[1] = sprintf(' %s', $value) . PHP_EOL;
                $line        = implode(':', $lineData);
            }
            $parameters[] = $line;
        }

        file_put_contents($file, implode('', $parameters));
    }

    private static function getDbConnection($useDb = false, $dbHost, $dbPort, $dbUser, $dbPassword, $dbName)
    {
        try {
            $dsn = sprintf('mysql:host=%s;port=%d;charset=UTF8', $dbHost, $dbPort);
            $dbh = new \PDO($dsn, $dbUser, $dbPassword);
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            if($useDb) {
                $dbh->exec(sprintf('USE `%s`', $dbName));
            }
        } catch (\Exception $e) {
            throw new \Exception(sprintf('Could not connect to database server %s: %s', $dbHost, $e->getMessage()));
        }
        return $dbh;
    }

    private static function testDbCredentials($dbHost, $dbPort, $dbUser, $dbPassword, $dbName)
    {
        $dbh = self::getDbConnection(false, $dbHost, $dbPort, $dbUser, $dbPassword, $dbName);
        $sth = $dbh->prepare('SHOW DATABASES LIKE :db_name;');
        $sth->execute(['db_name' => $dbName]);
        $result = $sth->fetchAll();
        if (count($result) !== 0) {
            throw new \Exception(sprintf('Database %s already exists', $dbName));
        }
    }

    private static function createDatabase($dbHost, $dbPort, $dbUser, $dbPassword, $dbName)
    {
        try {
            $dbh = self::getDbConnection(false, $dbHost, $dbPort, $dbUser, $dbPassword, $dbName);
            $sql = sprintf('CREATE DATABASE `%s` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci',
                $dbName);
            $sth = $dbh->prepare($sql);
            $sth->execute();
        } catch (\Exception $e) {
            self::echoString(sprintf('Could not create database: %s', $e->getMessage()), self::COLOR_RED);
            exit;
        }
    }

    private static function createSchema()
    {
        $process = new Process('cd source/symfony && php bin/console doctrine:schema:update --force');
        $process->run();
    }

    private static function createSessionTable($dbHost, $dbPort, $dbUser, $dbPassword, $dbName)
    {
        self::getDbConnection(true, $dbHost, $dbPort, $dbUser, $dbPassword, $dbName)->exec('CREATE TABLE `sessions` (
  `sess_id` varchar(128) COLLATE utf8_bin NOT NULL,
  `sess_data` longtext COLLATE utf8_bin NOT NULL,
  `sess_time` int(10) unsigned NOT NULL,
  `sess_lifetime` mediumint(9) NOT NULL,
  PRIMARY KEY (`sess_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;');
    }

    private static function createUser($username, $type, array $roles =[])
    {
        $roles[] = 'ROLE_ADMIN';
        $password = self::generateRandomString(20, self::CHAR_LIST_PASSWORD);
        $passwordEncoded = password_hash($password, PASSWORD_BCRYPT);

        $query = sprintf("
          INSERT INTO users
          (username, password, roles, created_at, updated_at)
          VALUES
          ('%s', '%s', '%s', now(), now())", $username, addslashes($passwordEncoded), addslashes(json_encode($roles)));
        $query = str_replace(["\n", "\r"], ' ', $query);

        self::echoString('Creating user: '.$username, self::COLOR_PURPLE);
        $process = new Process(sprintf('cd source/symfony && php bin/console doctrine:query:sql "%s"', $query));
        $process->run();

        return [
            'type' => $type,
            'username' => $username,
            'password' => $password,
        ];
    }

    private static function executeProcess($command)
    {
        $process = new Process($command);
        $process->run();
        return $process->getOutput();
    }
}

/**
 * Creates a markdown document based on the parsed documentation
 *
 * @author Peter-Christoph Haider <peter.haider@zeyon.net>
 * @package Apidoc
 * @version 1.00 (2014-04-04)
 * @license GNU Lesser Public License
 */
class TextTable {
    /** @var int The source path */
    public $maxlen = 50;
    /** @var array The source path */
    private $data = array();
    /** @var array The source path */
    private $header = array();
    /** @var array The source path */
    private $len = array();
    /** @var array The source path */
    private $align = array(
        'name' => 'L',
        'type' => 'C'
    );

    /**
     * @param array $header  The header array [key => label, ...]
     * @param array $content Content
     * @param array $align   Alignment optios [key => L|R|C, ...]
     */
    public function __construct($header=null, $content=array(), $align=false) {
        if ($header) {
            $this->header = $header;
        } elseif ($content) {
            foreach ($content[0] as $key => $value)
                $this->header[$key] = $key;
        }

        foreach ($this->header as $key => $label) {
            $this->len[$key] = strlen($label);
        }

        if (is_array($align))
            $this->setAlgin($align);

        $this->addData($content);
    }

    /**
     * Overwrite the alignment array
     *
     * @param array $align   Alignment optios [key => L|R|C, ...]
     */
    public function setAlgin($align) {
        $this->align = $align;
    }

    /**
     * Add data to the table
     *
     * @param array $content Content
     */
    public function addData($content) {
        foreach ($content as &$row) {
            foreach ($this->header as $key => $value) {
                if (!isset($row[$key])) {
                    $row[$key] = '-';
                } elseif (strlen($row[$key]) > $this->maxlen) {
                    $this->len[$key] = $this->maxlen;
                    $row[$key] = substr($row[$key], 0, $this->maxlen-3).'...';
                } elseif (strlen($row[$key]) > $this->len[$key]) {
                    $this->len[$key] = strlen($row[$key]);
                }
            }
        }

        $this->data = $this->data + $content;
        return $this;
    }

    /**
     * Add a delimiter
     *
     * @return string
     */
    private function renderDelimiter() {
        $res = '|';
        foreach ($this->len as $key => $l)
            $res .= (isset($this->align[$key]) && ($this->align[$key] == 'C' || $this->align[$key] == 'L') ? ':' : ' ')
                .str_repeat('-', $l)
                .(isset($this->align[$key]) && ($this->align[$key] == 'C' || $this->align[$key] == 'R') ? ':' : ' ')
                .'|';
        return $res."\r\n";
    }

    /**
     * Render a single row
     *
     * @param  array $row
     * @return string
     */
    private function renderRow($row) {
        $res = '|';
        foreach ($this->len as $key => $l) {
            $res .= ' '.$row[$key].($l > strlen($row[$key]) ? str_repeat(' ', $l - strlen($row[$key])) : '').' |';
        }

        return $res."\r\n";
    }

    /**
     * Render the table
     *
     * @param  array  $content Additional table content
     * @return string
     */
    public function render($content=array()) {
        $this->addData($content);

        $res = $this->renderRow($this->header)
            .$this->renderDelimiter();
        foreach ($this->data as $row)
            $res .= $this->renderRow($row);

        return $res;
    }
}
