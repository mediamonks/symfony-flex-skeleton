<?php

namespace Installer\EventHandler;

use Installer\Helper\File;
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

        $brand = File::readMetaData('brand');
        $project = File::readMetaData('project');

        $this->users[] = $this->createUser(
            $project['sanatized'] . 'Root',
            sprintf('root@%s.dev', strtolower($brand['sanatized'])),
            'root',
            ['ROLE_ROOT']
        );

        $this->users[] = $this->createUser(
            $project['sanatized'] . 'SuperAdmin',
            sprintf('super-admin@%s.dev', strtolower($brand['sanatized'])),
            'super admin'
        );

        $this->writeEmpty();

        $this->userSave();
    }

    /**
     * @param $username
     * @param $email
     * @param $type
     * @param array $roles
     * @return array
     */
    protected function createUser($username, $email, $type, array $roles =[])
    {
        $roles[] = 'ROLE_SONATA_ADMIN';

        $this->write(sprintf('Creating <comment>%s</comment> user', $type));

        $password = Rand::getString(20, self::CHAR_LIST_PASSWORD);

        $this->executeSymfonyCommand(sprintf('fos:user:create "%s" "%s" "%s" --super-admin', $username, $email, $password));

        foreach($roles as $role) {
            $this->executeSymfonyCommand(sprintf('fos:user:promote %s %s', $username, $role));
        }

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
