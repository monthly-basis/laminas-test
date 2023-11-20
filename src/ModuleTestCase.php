<?php
namespace MonthlyBasis\LaminasTest;

use Laminas\Db as LaminasDb;
use Laminas\Mvc\Application;
use PHPUnit\Framework\TestCase;

class ModuleTestCase extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testGetConfig()
    {
        if (!method_exists($this->module, 'getConfig')) {
            $this->markTestSkipped(
              'Method ::getConfig() does not exist.'
            );
        }

        $config = $this->module->getConfig();

        if ($config === []) {
            $this->markTestSkipped(
              'Method ::getConfig() returns empty array.'
            );
        }

        $applicationConfig = include($_SERVER['PWD'] . '/config/application.config.php');
        $this->application = Application::init($applicationConfig);
        $serviceManager    = $this->application->getServiceManager();
        $viewHelperManager = $serviceManager->get('ViewHelperManager');

        if (isset($config['controllers']['factories'])) {
            $controllerManager = $serviceManager->get('ControllerManager');
            foreach ($config['controllers']['factories'] as $class => $closure) {
                $this->assertInstanceOf(
                    $class,
                    $controllerManager->get($class)
                );
                $this->assertInstanceOf(
                    $class,
                    $closure($serviceManager)
                );
            }
        }

        if (isset($config['laminas-cli']['commands'])) {
            foreach ($config['laminas-cli']['commands'] as $command => $class) {
                $this->assertInstanceOf(
                    $class,
                    $serviceManager->get($class)
                );
            }
        }

        if (isset($config['service_manager']['factories'])) {
            foreach ($config['service_manager']['factories'] as $class => $closure) {
                $this->assertInstanceOf(
                    $class,
                    $serviceManager->get($class)
                );
                $this->assertInstanceOf(
                    $class,
                    $closure($serviceManager)
                );
            }
        }

        if (isset($config['view_helpers']['aliases'])) {
            foreach ($config['view_helpers']['aliases'] as $alias => $class) {
                $this->assertInstanceOf(
                    $class,
                    $viewHelperManager->get($class)
                );
            }
        }

        if (isset($config['view_helpers']['factories'])) {
            foreach ($config['view_helpers']['factories'] as $class => $closure) {
                $this->assertInstanceOf(
                    $class,
                    $viewHelperManager->get($class)
                );
                $this->assertInstanceOf(
                    $class,
                    $closure($serviceManager)
                );
            }
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function test_getControllerConfig()
    {
        if (!method_exists($this->module, 'getControllerConfig')) {
            $this->markTestSkipped(
              'Method ::getControllerConfig() does not exist.'
            );
        }

        $applicationConfig = include($_SERVER['PWD'] . '/config/application.config.php');
        $this->application = Application::init($applicationConfig);
        $serviceManager    = $this->application->getServiceManager();
        $controllerManager = $serviceManager->get('ControllerManager');

        $controllerConfig  = $this->module->getControllerConfig();

        foreach ($controllerConfig['factories'] as $class => $value) {
            $this->assertInstanceOf(
                $class,
                $controllerManager->get($class)
            );
        }
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetServiceConfig()
    {
        if (!method_exists($this->module, 'getServiceConfig')) {
            $this->markTestSkipped(
              'Method ::getServiceConfig() does not exist.'
            );
        }

        $serviceConfig = $this->module->getServiceConfig();

        if ($serviceConfig === []) {
            $this->markTestSkipped(
              'Method ::getServiceConfig() returns empty array.'
            );
        }

        $applicationConfig = include($_SERVER['PWD'] . '/config/application.config.php');
        $this->application = Application::init($applicationConfig);
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
