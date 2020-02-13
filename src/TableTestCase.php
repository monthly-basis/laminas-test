<?php
namespace LeoGalleguillos\Test;

use Exception;
use Zend\Db\Adapter\Adapter;
use PHPUnit\Framework\TestCase;

class TableTestCase extends TestCase
{
    protected $adapter;

    /**
     * @var string
     */
    protected $sqlDirectory = __DIR__ . '/../sql';

    protected function setUp()
    {
        $this->adapter = $this->getAdapter();
    }

    protected function createTable(
        string $tableName
    ) {
        if (preg_match('/\W/', $tableName)) {
            throw new Exception('Invalid table name.');
        }

        $sqlPath = $_SERVER['PWD']
                 . '/sql/test/'
                 . $tableName
                 . '/create.sql';
        $sql = file_get_contents($sqlPath);
        $this->getAdapter()->query($sql)->execute();
    }

    protected function dropAndCreateTable(
        string $tableName
    ) {
        $this->dropTable($tableName);
        $this->createTable($tableName);
    }

    protected function dropTable(
        string $tableName
    ) {
        if (preg_match('/\W/', $tableName)) {
            throw new Exception('Invalid table name.');
        }

        $sqlPath = $_SERVER['PWD']
                 . '/sql/test/'
                 . $tableName
                 . '/drop.sql';
        $sql = file_get_contents($sqlPath);
        $this->getAdapter()->query($sql)->execute();
    }

    protected function getAdapter(): Adapter
    {
        if (isset($this->adapter)) {
            return $this->adapter;
        }

        $this->instantiateAdapter();
        return $this->adapter;
    }

    protected function getConfigArray(): array
    {
        $configArray = require($_SERVER['PWD'] . '/config/autoload/local.php');
        return $configArray['db']['adapters']['test'];
    }

    protected function instantiateAdapter()
    {
        if (!isset($this->adapter)) {
            $this->adapter = new Adapter($this->getConfigArray());
        }
    }

    protected function setForeignKeyChecks0()
    {
        $sql = file_get_contents(
            $this->sqlDirectory . '/SetForeignKeyChecks0.sql'
        );

        $result = $this->getAdapter()->query($sql)->execute();
    }

    protected function setForeignKeyChecks1()
    {
        $sql = file_get_contents(
            $this->sqlDirectory . '/SetForeignKeyChecks1.sql'
        );

        $result = $this->getAdapter()->query($sql)->execute();
    }
}
