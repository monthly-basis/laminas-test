<?php
namespace MonthlyBasis\LaminasTest;

use Laminas\Db as LaminasDb;
use Laminas\Mvc\Application;
use PHPUnit\Framework\TestCase;

class ModuleTestCase extends TestCase
{
    public function testGetConfig()
    {
        if (!method_exists($this->module, 'getConfig')) {
			$this->markTestSkipped(
              'Method ::getConfig() does not exist.'
            );
        }

        $applicationConfig = include($_SERVER['PWD'] . '/config/application.config.php');
        $this->application = Application::init($applicationConfig);
        $serviceManager    = $this->application->getServiceManager();
        $viewHelperManager = $serviceManager->get('ViewHelperManager');
        $config            = $this->module->getConfig();

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
        if (!method_exists($this->module, 'getServiceConfig')) {
			$this->markTestSkipped(
              'Method ::getServiceConfig() does not exist.'
            );
        }

        $applicationConfig = include($_SERVER['PWD'] . '/config/application.config.php');
        $this->application = Application::init($applicationConfig);
        $serviceManager    = $this->application->getServiceManager();
        $serviceConfig     = $this->module->getServiceConfig();

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
