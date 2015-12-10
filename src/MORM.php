<?php

namespace MORM;

use MORM\QueryLogic;

/**
 * @package MORM
 * @subpackage MORM
 * @author Eugen Melnychenko
 */
class MORM
{
    private $qb;
    private $ql;

    /**
     * @param \QB\QB $qb
     *
     * @return
     */
    function __construct(\QB\QB $qb)
    {
        $this->setQB($qb);
        $this->setQL($qb);

        return;
    }

    /**
     * @param string $entity
     * @param integer $id
     *
     * @return \MORM\Entity
     */
    public function find($entity, $id)
    {
        $entity = new $entity;
        $table  = $entity->getTable();

        $result = $this->getQL()
            ->find($table, $id);

        $entity->setData($result, $this->getQL());
        $entity->setType($entity, $this->getQL());

        return $entity;
    }

    /**
     * @param string $entity
     *
     * @return array
     */
    public function findAll($entity)
    {
        $result  = [];
        $entity = new $entity;
        $table  = $entity->getTable();

        $dataset = $this->getQL()
            ->findAll($table);

        if(!empty($dataset)) {
            foreach ($dataset as $value) {
                $subentity = clone $entity;

                $subentity->setData($value, $this->getQL());
                $subentity->setType($subentity, $this->getQL());

                $result[] = $subentity;
            }
        }

        return $result;
    }

    /**
     * @param string $entity
     * @param array $condition
     * @param array $sort
     * @param integer $offset
     *
     * @return \MORM\Entity
     */
    public function findOneBy($entity, $condition = [], $sort = [], $offset = null)
    {
        $entity = new $entity;
        $table  = $entity->getTable();

        $result = $this->getQL()
            ->findOneBy($table, $condition, $sort, $offset);

        $entity->setData($result, $this->getQL());
        $entity->setType($entity, $this->getQL());

        return $entity;
    }

    /**
     * @param string $entity
     * @param array $condition
     * @param array $sort
     * @param integer $limit
     * @param integer $offset
     *
     * @return array
     */
    public function findBy($entity, $condition = [], $sort = [], $limit = null, $offset = null)
    {
        $result  = [];
        $entity = new $entity;
        $table  = $entity->getTable();

        $dataset = $this->getQL()
            ->findBy($table, $condition, $sort, $limit, $offset);

        if(!empty($dataset)) {
            foreach ($dataset as $value) {
                $subentity = clone $entity;

                $subentity->setData($value, $this->getQL());
                $subentity->setType($subentity, $this->getQL());

                $result[] = $subentity;
            }
        }

        return $result;
    }

    /**
     * @param \MORM\Entity $entity
     *
     * @return \MORM\Entity
     */
    public function save(\MORM\Entity $entity)
    {
        $result = [];

        /** @ \MORM\Entity */
        $data   = $entity->getData();

        /** @ \MORM\Entity */
        $table  = $entity->getTable();

        /** @ \MORM\DataType */
        $array  = $entity->getType($entity);

        if(empty($entity->get('id'))) {
            /** @ \MORM\QueryLogic */
            $result = $this->getQL()->insertOne($table, $array);

            /** @ \MORM\Entity */
            $entity->setData($result, $this->getQL());

            /** @ \MORM\DataType */
            $entity->setType($entity, $this->getQL());
        } else {
            /** @ \MORM\QueryLogic */
            $result = $this->getQL()->updateOne($table, $array);

            /** @ \MORM\Entity */
            $entity->setData($result, $this->getQL());

            /** @ \MORM\DataType */
            $entity->setType($entity, $this->getQL());
        }

        return $entity;
    }

    /**
     * @param \MORM\Entity $entity
     *
     * @return
     */
    public function remove(\MORM\Entity $entity)
    {
        $result = [];
        $data   = $entity->getData();
        $table  = $entity->getTable();
        $array  = $entity->getType($entity);

        $result = $this->getQL()->deleteOne($table, $array);

        $entity = null;

        return;
    }

    /**
     * @param \QB\QB $qb
     *
     * @return \QB\QB
     */
    private function setQB($qb)
    {
        return $this->qb = $qb;
    }

    /**
     * @return \QB\QB
     */
    public function getQB()
    {
        return $this->qb;
    }

    /**
     * @param \QB\QB $qb
     *
     * @return \MORM\QueryLogic
     */
    private function setQL($qb)
    {
        return $this->ql = new QueryLogic($qb);
    }

    /**
     * @return \MORM\QueryLogic
     */
    private function getQL()
    {
        return $this->ql;
    }
}
