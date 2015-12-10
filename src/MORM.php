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
                $cEntity = clone $entity;
                $result[] = $cEntity->setData($value, $this->getQL());
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
                $cEntity = clone $entity;
                $result[] = $cEntity->setData($value, $this->getQL());
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
        $data   = $entity->getData();
        $table  = $entity->getTable();

        if(empty($entity->get('id'))) {
            $result = $this->getQL()->insertOne($table, $data);

            $entity->setData($result, $this->getQL());
        } else {
            $result = $this->getQL()->updateOne($table, $data);
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

        $result = $this->getQL()->deleteOne($table, $data);

        $entity->__destruct();

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
