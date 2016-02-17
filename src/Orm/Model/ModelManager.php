<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 16.02.2016
 * Time: 13:34
 *
 */

namespace Blast\Db\Orm\Model;


use Blast\Db\Manager as DbManager;
use Blast\Db\Orm\MapperAwareInterface;
use Blast\Db\Orm\MapperInterface;

/**
 * @todo very ugly lines of code ...
 * Class ModelManager
 * @package Blast\Db\Orm\Model
 */
class ModelManager
{

    /**
     * @var array
     */
    private $models = [];

    private $mappers = [];

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @return $this
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;

    }

    /**
     * @param $name
     * @return mixed
     */
    public function getModel($name)
    {
        if (!isset($this->models[$name])) {
            $this->prepareModel($name);
        }
        return $this->models[$name];
    }

    /**
     * Prepare a model instance with relations, fields and mapper
     * @param $name
     * @return $this
     */
    private function prepareModel($name)
    {
        $container = DbManager::getInstance()->getContainer();
        $model = $container->get($name);

        if (isset($this->mappers[$name])) {
            $this->bindMapper($name, $this->models[$name]);
        }

        $mapper = $this->mappers[$name];

        if ($model instanceof ModelFieldsAwareInterface) {
            $model::attachFields($mapper, $model);
        }

        if ($model instanceof ModelRelationAwareInterface) {
            $model::attachRelations($mapper, $model);
        }

        $this->models[$name] = $model;


        return $this;
    }

    /**
     * @param $name
     * @param $model
     * @return MapperInterface
     */
    private function bindMapper($name, $model)
    {
        $container = DbManager::getInstance()->getContainer();
        if ($model instanceof MapperAwareInterface) {
            //get mapper from model
            $mapper = $model->getMapper();
        } else {
            //get default mapper by contract
            $mapper = $container->get(MapperInterface::class);

            if ($mapper instanceof MapperInterface) {
                throw new \RuntimeException('No mapper configured!');
            }
        }

        if($mapper instanceof ModelAwareInterface){
            $mapper->setModel($model);
        }

        $this->mappers[$name] = $mapper;
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getMapper($name)
    {
        return $this->mappers[$name];
    }

}