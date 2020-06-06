<?php
namespace LeoGalleguillos\Test;

use Laminas\Db as LaminasDb;
use Laminas\Mvc\Application;
use PHPUnit\Framework\TestCase;

class ModuleTestCase Extends TestCase
{
    public function testGetConfig()
    {
        $applicationConfig = include($_SERVER['PWD'] . '/config/application.config.php');
        $this->application = Application::init($applicationConfig);
        $serviceConfig     = $this->module->getServiceConfig();
        $serviceManager    = $this->application->getServiceManager();
        $viewHelperManager = $serviceManager->get('ViewHelperManager');
        $config            = $this->module->getConfig();

        $this->assertTrue(is_array($config));

        if (isset($config['view_helpers']['aliases'])) {
            foreach ($config['view_helpers']['aliases'] as $alias => $class) {
                $this->assertInstanceOf(
                    $class,
                    $viewHelperManager->get($class)
                );
            }
        }

        if (isset($config['view_helpers']['factories'])) {
            foreach ($config['view_helpers']['factories'] as $class => $value) {
                $this->assertInstanceOf(
                    $class,
                    $viewHelperManager->get($class)
                );
            }
        }
    }

    public function testGetServiceConfig()
    {
        $applicationConfig = include($_SERVER['PWD'] . '/config/application.config.php');
        $this->application = Application::init($applicationConfig);
        $serviceConfig     = $this->module->getServiceConfig();
        $serviceManager    = $this->application->getServiceManager();

        foreach ($serviceConfig['factories'] as $class => $value) {
            if ($class == 'laminas-db-sql-sql') {
                $this->assertInstanceOf(
                    LaminasDb\Sql\Sql::class,
                    $serviceManager->get($class)
                );
            } elseif (substr($class, 0, 39) === 'laminas-db-table-gateway-table-gateway-') {
                $tableGateway = $serviceManager->get($class);
                $tableName    = substr($class, 39);

                $this->assertInstanceOf(
                    \Laminas\Db\TableGateway\TableGateway::class,
                    $tableGateway
                );
                $this->assertSame(
                    $tableName,
                    $tableGateway->getTable()
                );
            } else {
                $this->assertInstanceOf(
                    $class,
                    $serviceManager->get($class)
                );
            }
        }
    }
}
