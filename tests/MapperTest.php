<?php
/**
 * Created by PhpStorm.
 * User: Marco Bunge
 * Date: 26.11.2015
 * Time: 16:09
 */

namespace Blast\Tests\Orm;

use Blast\Db\Entity\EntityInterface;
use Blast\Db\Factory;
use Blast\Db\Orm\Mapper;
use Blast\Db\Orm\Query;
use Blast\Tests\Orm\Entities\AnyEntity;
use Doctrine\DBAL\Query\QueryBuilder;
use Interop\Container\ContainerInterface;
use Prophecy\Prophecy\ObjectProphecy;

class MapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityInterface
     */
    private $entity;

    /**
     * @var ObjectProphecy
     */
    private $container;

    /**
     * @var Factory
     */
    private $factory;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class)->willImplement(ContainerInterface::class);
        $this->entity = new AnyEntity();

        $container = $this->container->reveal();
        $factory = Factory::create($container, [
            'url' => 'sqlite:///:memory:',
            'memory' => 'true'
        ]);

        $connection = $factory->getConfig()->getConnection(Factory::DEFAULT_CONNECTION);
        $connection->prepare('CREATE TABLE test (id int, pk int, same int)')->execute();
        $connection->insert('test', [
            'id' => 1,
            'pk' => 1,
            'same' => 42
        ]);
        $connection->insert('test', [
            'id' => 2,
            'pk' => 2,
            'same' => 42
        ]);

        $this->factory = $factory;
    }

    protected function tearDown()
    {
        $connection = $this->factory->getConfig()->getConnection(Factory::DEFAULT_CONNECTION);
        $connection->prepare('DROP TABLE test')->execute();
        $this->factory->shutdown();
    }


    public function testFindMany()
    {
        $entity = $this->entity;
        $mapper = new Mapper($entity);

        $result = $mapper->findBy('same', 42);

        $this->assertCount(2, $result);
        $this->assertEquals(1, $result[0]->pk);
    }

    public function testFindOne()
    {
        $entity = $this->entity;
        $mapper = new Mapper($entity);

        $result = $mapper->findBy('pk', 1);

        $query = new Query();
        $query->

        $this->assertInstanceOf(EntityInterface::class, $result);
    }
}
