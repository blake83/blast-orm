<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 29.02.2016
 * Time: 10:21
 *
 */

namespace Blast\Orm;

use Blast\Orm\Entity\EntityAwareInterface;
use Blast\Orm\Entity\EntityAwareTrait;
use Blast\Orm\Hydrator\HydratorInterface;

abstract class AbstractRepository implements EntityAwareInterface, RepositoryInterface
{
    use EntityAwareTrait;
    use EntityAdapterLoaderTrait;

    /**
     * @var EntityAdapter
     */
    protected $adapter = null;

    /**
     * Get adapter for entity
     *
     * @return EntityAdapter
     */
    private function getAdapter(){
        if($this->adapter === null){
            $this->adapter = $this->loadAdapter($this->getEntity());
        }
        return $this->adapter;
    }

    /**
     * Get a collection of all entities
     *
     * @return \ArrayObject|\stdClass|\ArrayObject|object
     */
    public function all()
    {
        return $this->getAdapter()->getMapper()->select()->execute(HydratorInterface::HYDRATE_COLLECTION);
    }

    /**
     * Find entity by primary key
     *
     * @param mixed $primaryKey
     * @return \ArrayObject|\stdClass|Entity|\ArrayObject|object
     */
    public function find($primaryKey){
        return $this->getAdapter()->getMapper()->find($primaryKey)->execute(HydratorInterface::HYDRATE_ENTITY);
    }

    /**
     * Save new or existing entity data
     *
     * @param object|array $data
     * @return int|bool
     */
    public function save($data){

        if(is_array($data)){
            $adapter = $this->loadAdapter($this->getEntity());
            $adapter->setData($data);
        }else{
            $adapter = $this->getAdapter();
        }

        $mapper = $this->getAdapter()->getMapper();
        $query = $adapter->isNew() ? $mapper->create($data) : $mapper->update($data);
        return $query->execute();
    }

}