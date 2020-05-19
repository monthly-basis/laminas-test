<?php
namespace LeoGalleguillos\Test;

use Exception;
use Laminas\Db as LaminasDb;
use PHPUnit\Framework\TestCase;

class TableTestCase extends TestCase
{
    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * @var LaminasDb\TableGateway\TableGateway[]
     */
    protected $tableGateways = [];

    /**
     * @var string
     */
    protected $sqlDirectory = __DIR__ . '/../sql';

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

    protected function createTables(array $tableNames): bool
    {
        foreach ($tableNames as $tableName) {
            $this->createTable($tableName);
        }

        return true;
    }

    protected function dropAndCreateTable(string $tableName): bool
    {
        $this->dropTable($tableName);
        $this->createTable($tableName);

        return true;
    }

    protected function dropAndCreateTables(array $tableNames): bool
    {
        foreach ($tableNames as $tableName) {
            $this->dropAndCreateTable($tableName);
        }

        return true;
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

    protected function dropTables(array $tableNames): bool
    {
        foreach ($tableNames as $tableName) {
            $this->dropTable($tableName);
        }

        return true;
    }

    protected function getAdapter(): LaminasDb\Adapter\Adapter
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

    protected function getTableGateway(
        string $tableName
    ): LaminasDb\TableGateway\TableGateway {
        if (isset($this->tableGateways[$tableName])) {
            return $this->tableGateways[$tableName];
        }

        $this->instantiateTableGateway($tableName);
        return $this->tableGateways[$tableName];
    }

    protected function instantiateAdapter()
    {
        $this->adapter = new LaminasDb\Adapter\Adapter($this->getConfigArray());
    }

    protected function instantiateTableGateway(string $tableName)
    {
        $this->tableGateways[$tableName] = new LaminasDb\TableGateway\TableGateway(
            $tableName,
            $this->getAdapter()
        );
    }

    protected function setForeignKeyChecks(int $zeroOrOne): bool
    {
        switch ($zeroOrOne) {
            case 0:
                $this->setForeignKeyChecks0();
                break;
            case 1:
                $this->setForeignKeyChecks1();
                break;
            default:
                throw new Exception('Invalid foreign key checks value.');
        }

        return true;
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
