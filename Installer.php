<?php

namespace Skeleton;

use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class Installer
{
    /**
     * @param $value
     * @return string
     */
    public static function normalizeString($value)
    {
        return strtolower(str_replace(' ', '-', preg_replace('/[^a-zA-Z0-9]+/', '-', $value)));
    }

    /**
     * @param $filename
     * @param $search
     * @param $replace
     */
    public static function replaceInFile($filename, $search, $replace)
    {
        file_put_contents(
            $filename,
            str_replace(
                $search,
                $replace,
                file_get_contents($filename)
            )
        );
    }

    /**
     * @throws Exception
     */
    public static function install()
    {
        $application = new Application('installer', '3.0.0');
        $application
            ->register('installer')
            ->setCode(
                function (InputInterface $input, OutputInterface $output) {
                    echo "===============================================================================" . PHP_EOL;
                    echo "=-----------------------------------------------------------------------------=" . PHP_EOL;
                    echo "===============================================================================" . PHP_EOL;
                    echo "                                                                               " . PHP_EOL;
                    echo "      ____             _                  _   __  __             _             " . PHP_EOL;
                    echo "     |  _ \           | |                | | |  \/  |           | |            " . PHP_EOL;
                    echo "     | |_) | __ _  ___| | _____ _ __   __| | | \  / | ___  _ __ | | _____      " . PHP_EOL;
                    echo "     |  _ < / _` |/ __| |/ / _ \ '_ \ / _` | | |\/| |/ _ \| '_ \| |/ / __|     " . PHP_EOL;
                    echo "     | |_) | (_| | (__|   <  __/ | | | (_| |_| |  | | (_) | | | |   <\__ \     " . PHP_EOL;
                    echo "     |____/ \__,_|\___|_|\_\___|_| |_|\__,_(_)_|  |_|\___/|_| |_|_|\_\___/     " . PHP_EOL;
                    echo "                                                                               " . PHP_EOL;
                    echo "                                            .+y+:+Nd:                          " . PHP_EOL;
                    echo "                                           oNd.  /NN+                          " . PHP_EOL;
                    echo "                                .:::-    .hMM-    ..                           " . PHP_EOL;
                    echo "                              :hNy/:/o+- hMMy  .yy.                            " . PHP_EOL;
                    echo "                             .NMM/    -+hMMM/  :Nd.                            " . PHP_EOL;
                    echo "                             .mMMNs.   -NMMN+++/:                              " . PHP_EOL;
                    echo "                           :: :dMMMm-  sMMMs                                   " . PHP_EOL;
                    echo "                          oMN:  +MMM+  NMMM-                                   " . PHP_EOL;
                    echo "                          -hm:../NNy. /MMMh                                    " . PHP_EOL;
                    echo "                            -:/+o/-   hMMN-                                    " . PHP_EOL;
                    echo "                                     :MMN+                                     " . PHP_EOL;
                    echo "                              ./:    dMN+                                      " . PHP_EOL;
                    echo "                             .mMMo .yNy-                                       " . PHP_EOL;
                    echo "                              smd+oyo-                                         " . PHP_EOL;
                    echo "                                ..                                             " . PHP_EOL;
                    echo "                                                                               " . PHP_EOL;
                    echo "                                                                               " . PHP_EOL;
                    echo "===============================================================================" . PHP_EOL;
                    echo "=-----------------------------------------------------------------------------=" . PHP_EOL;
                    echo "===============================================================================" . PHP_EOL . PHP_EOL;

                    $symfonyStyle = new SymfonyStyle($input, $output);
                    $settings = [];
                    $questions = [
                        'hostname' => [
                            'q' => 'Hostname (".lcl" will be added automatically)',
                            'd' => 'symfony-skeleton',
                            'suf' => '.lcl',
                            'val' => function ($value) {
                                return self::normalizeString($value);
                            },
                        ],
                        'composerCacheDirectory' => [
                            'q' => 'Composer Cache directory (for you personally)',
                            'd' => '~',
                            'val' => null,
                        ],
                        'ip' => [
                            'q' => 'IP address ("192.168.33." will be prepended automatically)',
                            'd' => rand(5, 240),
                            'pre' => '192.168.33.',
                            'val' => function ($value) {
                                if ($value < 5 || $value > 240) throw new Exception('Please choose value between 5-240!');
                                return $value;
                            },
                        ],
                        'phpVersion' => [
                            'q' => 'PHP Version (please check with your project manager for this project)',
                            'd' => '7.4',
                            'choices' => ['7.4', '8.0'],
                        ],
                        'symfonyVersion' => [
                            'q' => 'Symfony Version (please check with your project manager for this project)',
                            'd' => '5.4.*',
                            'choices' => ['5.4.*', '6.*'],
                        ],
                    ];

                    $settings['projectName'] = $symfonyStyle->ask('Project name', null, function ($value) {
                        if (empty($value)) throw new Exception('Project name is mandatory!');
                        return $value;
                    });

                    $questions['hostname']['d'] = self::normalizeString($settings['projectName']);
                    foreach ($questions as $setting => $question) {
                        if (isset($question['choices'])) {
                            $answer = $symfonyStyle->choice($question['q'], $question['choices'], $question['d']);
                        } else {
                            $answer = $symfonyStyle->ask($question['q'], $question['d'], $question['val']);
                        }
                        if (isset($question['pre'])) $answer = $question['pre'] . $answer;
                        if (isset($question['suf'])) $answer .= $question['suf'];
                        $settings[$setting] = $answer;
                    }

                    if ($settings['symfonyVersion'] === '6.*') {
                        $settings['phpVersion'] = '8.0';
                        $output->writeln(sprintf('Symfony %d was selected, it requires php 8. Using php 8 instead', $settings['symfonyVersion']));
                    }

                    $filesystem = new Filesystem();

                    // Docker:
                    self::replaceInFile(sprintf('%s/tools/docker/web/www.conf', __DIR__), '__hostname__', $settings['hostname']);
                    self::replaceInFile(sprintf('%s/tools/docker/docker-compose.yml', __DIR__), '__hostname__', $settings['hostname']);
                    self::replaceInFile(sprintf('%s/tools/docker/docker-compose.yml', __DIR__), '__php_version__', $settings['phpVersion']);
                    self::replaceInFile(sprintf('%s/tools/docker/web/generate-ssl', __DIR__), '__hostname__', $settings['hostname']);
                    self::replaceInFile(sprintf('%s/tools/docker/web/generate-ssl', __DIR__), '__ip__', $settings['ip']);
                    self::replaceInFile(sprintf('%s/tools/docker/console-debug', __DIR__), '__hostname__', $settings['hostname']);
                    self::replaceInFile(sprintf('%s/tools/docker/phpunit-debug', __DIR__), '__hostname__', $settings['hostname']);
                    self::replaceInFile(sprintf('%s/tools/docker/init-environment', __DIR__), '__hostname__', $settings['hostname']);

                    // Symfony
                    self::replaceInFile(sprintf('%s/source/symfony/composer.json', __DIR__), '__symfony_version__', $settings['symfonyVersion']);

                    $output->writeln("===============================================================================");
                    $output->writeln("=-----------------------------------------------------------------------------=");
                    $output->writeln("===============================================================================");
                    $output->writeln("                                                                               ");
                    $output->writeln(" <comment>Project Info</comment>                                               ");
                    $output->writeln(" Symfony: <info>" . $settings['symfonyVersion'] . "</info>                     ");
                    $output->writeln(" Hostname: <info>" . $settings['hostname'] . "</info>                          ");
                    $output->writeln(" IP Address: <info>" . $settings['ip'] . "</info>                              ");
                    $output->writeln("                                                                               ");
                    $output->writeln(" <comment>Local SSL</comment>                                                  ");
                    $output->writeln(" If you want to use SSL for this project, please install                       ");
                    $output->writeln(" the generated SSL certificate in <info>tools/docker/</info> manually.         ");
                    $output->writeln("                                                                               ");
                    $output->writeln("                                                                               ");
                    $output->writeln(" <comment>Available Recipes</comment>                                          ");
                    $output->writeln(" Sonata Admin: <info>composer req admin</info>                                 ");
                    $output->writeln(" Sonata Media Bundle: <info>composer req sonata-media</info>                   ");
                    $output->writeln(" API: <info>composer req api</info>                                            ");
                    $output->writeln(" PII: <info>composer req pii</info>                                            ");
                    $output->writeln("                                                                               ");
                    $output->writeln("                                                                               ");
                    $output->writeln(" <comment>Useful commands</comment>                                            ");
                    $output->writeln(" Navigate to <info>tools/docker/</info>                                        ");
                    $output->writeln(" • <info>bash init-environment</info> - Local environment initialization.      ");
                    $output->writeln("       Useful alias to <info>docker compose up</info> with some extra spice.   ");
                    $output->writeln(" • <info>bash container</info> - Bash into the specified container.            ");
                    $output->writeln(" • <info>bash composer</info> - Run composer in the php container.             ");
                    $output->writeln(" • <info>bash console</info> - Run symfony console in the php container.       ");
                    $output->writeln(" • <info>bash console-debug</info> - Run symfony console in the php container  ");
                    $output->writeln("       with Xdebug enabled.                                                    ");
                    $output->writeln(" • <info>bash phpunit</info> - Run phpunit in the php container.               ");
                    $output->writeln(" • <info>bash phpunit-debug</info> - Run phpunit in the php container          ");
                    $output->writeln("       with Xdebug enabled.                                                    ");
                    $output->writeln(" • <info>bash cache-clear</info> - Clears cache.                               ");
                    $output->writeln(" • <info>bash logs-clear</info> - Clears logs.                                 ");
                    $output->writeln(" • <info>bash dump-server</info> - Run the var-dump server if available.       ");
                    $output->writeln("                                                                               ");
                    $output->writeln("                                                                               ");
                    $output->writeln(" <comment>Initialize local development environment</comment>                   ");
                    $output->writeln(" Navigate to <info>tools/docker/</info>                                        ");
                    $output->writeln(" run <info>bash init-environment</info>                                        ");
                    $output->writeln("                                                                               ");
                    $output->writeln("===============================================================================");
                    $output->writeln("=-----------------------------------------------------------------------------=");
                    $output->writeln("===============================================================================");

                    $filesystem->remove(sprintf('%s/vendor', __DIR__));
                    $filesystem->remove(sprintf('%s/composer.json', __DIR__));
                    $filesystem->remove(sprintf('%s/composer.lock', __DIR__));
                    $filesystem->remove(sprintf('%s/Installer.php', __DIR__));
                }
            )
            ->getApplication()
            ->setDefaultCommand('installer', true);
        $application->run(new ArrayInput([]));
    }
}