<?php

namespace Installer\EventHandler;

use Installer\Helper\File;

class DatabaseHandler extends AbstractHandler
{
    const DB_DEFAULT_HOST = 'norbu.mediamonks.local';
    const DB_DEFAULT_PORT = 3306;
    const DB_DEFAULT_USER = 'root';
    const DB_DEFAULT_PASS = null;

    protected $host;
    protected $port;
    protected $user;
    protected $pass;
    protected $name;

    public function execute()
    {
        $this->writeHeader('Database');

        $this->getCredentials();

        $this->createDatabase();

        $this->createSchema();

        $this->createSessionTable();
    }

    protected function createDatabase()
    {
        $this->write('Creating database');

        try {
            $dbh = $this->getDbConnection();
            $sql = sprintf('CREATE DATABASE `%s` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci',
                $this->name);
            $sth = $dbh->prepare($sql);
            $sth->execute();
        } catch (\Exception $e) {
            $this->writeError(sprintf('Could not create database: %s', $e->getMessage()));
            exit;
        }

        $this->writeEmpty();
    }

    protected function createSchema()
    {
        $this->write('Creating database schema from entities');

        $this->executeSymfonyCommand('doctrine:schema:update --force');

        $this->writeEmpty();
    }

    protected function createSessionTable()
    {
        $this->write('Creating session table');

        $this->getDbConnection()->exec('CREATE TABLE `sessions` (
  `sess_id` varchar(128) COLLATE utf8_bin NOT NULL,
  `sess_data` longtext COLLATE utf8_bin NOT NULL,
  `sess_time` int(10) unsigned NOT NULL,
  `sess_lifetime` mediumint(9) NOT NULL,
  PRIMARY KEY (`sess_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;');
    }

    protected function getCredentials()
    {
        db:
        $this->host = $this->ask('Please enter the database host: ',
            (!empty($this->host)) ? $this->host : self::DB_DEFAULT_HOST);

        $this->port = $this->ask('Please enter the database port: ',
            (!empty($this->port)) ? $this->port : self::DB_DEFAULT_PORT);

        $this->user = $this->ask('Please enter the database user: ',
            (!empty($this->user)) ? $this->user : self::DB_DEFAULT_USER);

        $this->pass = $this->ask('Please enter the database pass: ',
            (!empty($this->pass)) ? $this->pass : self::DB_DEFAULT_PASS);

        $this->name = null;
        $assemblaData = File::readMetaData('assembla');
        if(!empty($assemblaData['name'])) {
            $this->name  = $assemblaData['name'];
        }

        db_name:
        $this->name = $this->ask('Please enter the database name: ', $this->name);

        try {
            $this->testDbCredentials();
        } catch (\Exception $e) {
            $this->writeError($e->getMessage());
            if (strpos($e->getMessage(), 'already exists') !== false) {
                goto db_name;
            }
            goto db;
        }

        $this->writeEmpty();

        $this->replaceParameter('database_host', $this->host, File::PARAMETERS_DEVELOPMENT);
        $this->replaceParameter('database_port', $this->port, File::PARAMETERS_DEVELOPMENT);
        $this->replaceParameter('database_user', $this->user, File::PARAMETERS_DEVELOPMENT);
        $this->replaceParameter('database_password', $this->pass, File::PARAMETERS_DEVELOPMENT);
        $this->replaceParameter('database_name', $this->name, File::PARAMETERS_DEVELOPMENT);

        $this->writeEmpty();
    }

    /**
     * @throws \Exception
     */
    protected function testDbCredentials()
    {
        $dbh = $this->getDbConnection();
        $sth = $dbh->prepare('SHOW DATABASES LIKE :db_name;');
        $sth->execute(['db_name' => $this->name]);
        $result = $sth->fetchAll();
        if (count($result) !== 0) {
            throw new \Exception(sprintf('Database %s already exists', $this->name));
        }
    }

    /**
     * @return \PDO
     * @throws \Exception
     */
    protected function getDbConnection()
    {
        try {
            $dsn = sprintf('mysql:host=%s;port=%d;charset=UTF8', $this->host, $this->port);
            $dbh = new \PDO($dsn, $this->user, $this->pass);
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            throw new \Exception(sprintf('Could not connect to database server %s: %s', $this->host, $e->getMessage()));
        }
        return $dbh;
    }
}
