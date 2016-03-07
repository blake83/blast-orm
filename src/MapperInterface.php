<?php
/**
 * Created by PhpStorm.
 * User: Marco Bunge
 * Date: 26.11.2015
 * Time: 15:40
 */

namespace Blast\Orm;

use Blast\Orm\Data\DataObject;
use Blast\Orm\Entity\Entity;

interface MapperInterface
{

    /**
     * Select query for finding entity by primary key
     *
     * @param mixed $primaryKey
     * @return Query
     */
    public function find($primaryKey);

    /**
     * Select query for entity
     *
     * @param array $selects
     * @return Query
     */
    public function select($selects = ['*']);

    /**
     * Create query for new entity.
     *
     * @param array|DataObject|\ArrayObject|\stdClass|Entity|object $entity
     * @return Query
     */
    public function create($entity);

    /**
     * Update query for existing Model or a collection of entities in storage
     *
     * @param array|DataObject|\ArrayObject|\stdClass|Entity|object $entity
     * @return Query
     */
    public function update($entity);

    /**
     * Prepare delete query for attached entity by identifiers
     *
     * @param array|int|string $identifiers
     * @return query
     */
    public function delete($identifiers);

}