<?php

namespace Itkg\Core;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Itkg\Core\Provider\Command\ScriptCommandProvider;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $config = new Config();
        $container = new ServiceContainer();
        $container->setConfig($config);
        $params = array(
            'dbname' => 'DBNAME',
            'user'   => 'USER',
            'password' => 'PWD',
            'host' => '',
            'driver' => 'oci8'
        );

        $config = new Configuration();
        $connection = DriverManager::getConnection($params, $config);
        $values = array('doctrine.connection' => $connection);

        $container->register(new ScriptCommandProvider(), $values);
        $this->assertEquals($connection, $container['doctrine.connection']);
    }
} 