<?php

namespace Installer\EventHandler;

use Installer\Helper\File;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Zend\Math\Rand;

class UserHandler extends AbstractHandler
{
    const CHAR_LIST_PASSWORD = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+~';

    /**
     * @var array
     */
    protected $users = [];

    public function execute()
    {
        $this->writeHeader('Users');

        $project = File::readMetaData('project');

        $this->users[] = $this->createUser(
            $project['sanatized'] . 'SuperAdmin',
            'super admin',
            ['ROLE_SUPER_ADMIN']
        );

        $this->users[] = $this->createUser(
            $project['sanatized'] . 'Admin',
            'admin'
        );

        $this->writeEmpty();

        $this->userSave();
    }

    /**
     * @param $username
     * @param $type
     * @param array $roles
     * @return array
     */
    protected function createUser($username, $type, array $roles =[])
    {
        $roles[] = 'ROLE_ADMIN';

        $this->write(sprintf('Creating <comment>%s</comment> user', $type));

        $password = Rand::getString(20, self::CHAR_LIST_PASSWORD);

        $passwordEncoded = password_hash($password, PASSWORD_BCRYPT);

        $query = sprintf("
          INSERT INTO users
          (username, password, roles, created_at, updated_at)
          VALUES
          ('%s', '%s', '%s', now(), now())", $username, $passwordEncoded, addslashes(json_encode($roles)));

        $query = str_replace(["\n", "\r"], ' ', $query);

        $this->executeSymfonyCommand(sprintf('doctrine:query:sql "%s"', $query));

        return [
            'type' => $type,
            'username' => $username,
            'password' => $password,
        ];
    }

    protected function userSave()
    {
        $metaData = File::readMetaData();
        $assembla = !empty($metaData['assembla']);

        // @todo use custom service (devmonks?) to create this wiki page

        if($assembla) {
            $this->write('Copy the user table below to this wiki page on Assembla:');
            $this->writeEmpty();
            $this->write(sprintf('<comment>%swiki/new?page_name=Admin_Credentials</comment>', $metaData['assembla']['url']));
            $this->write('(make sure to set the visibility to <comment>Private</comment> and the markup format to <comment>markdown</comment>)');
        }
        else {
            $this->write('Copy the user table below to a secure location:');
        }

        $this->writeEmpty();
        $this->writeEmpty();

        require_once __DIR__ . '/../Resources/vendor/TextTable.php';

        $users = [];
        foreach($this->users as $user) {
            $users[] = array_values($user);
        }
        echo (new \TextTable(['Type', 'Username', 'Password'], $users))->render();

        $this->writeEmpty();
        $this->writeEmpty();

        if($assembla) {
            assembla_confirm:
            if(!$this->askConfirmation('Did you create the page?', false)) {
                goto assembla_confirm;
            }
        }

        $this->writeEmpty();
    }
}
