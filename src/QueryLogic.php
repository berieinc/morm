<?php

namespace MORM;

/**
 * @package MORM
 * @subpackage QueryLogic
 * @author Eugen Melnychenko
 */
class QueryLogic
{
    private $qb;

    /**
	 * @param \QB\QB $qb
     *
     * @return
     */
    function __construct(\QB\QB $qb)
    {
        $this->qb = $qb;

        return;
    }

    /**
	 * @param string $table
     * @param integer $id
     *
     * @return array
     */
    public function find($table, $id)
    {
        $result = $this->getQB()
            ->select()
            ->from($table)
            ->where('id', ':id')
            ->param('id', $id)
            ->query()
            ->toArray();

        $this->flush();

        return !empty($result) ? $result[0] : [];
    }

    /**
	 * @param string $table
     *
     * @return array
     */
    public function findAll($table)
    {
        $result = $this->getQB()
            ->select()
            ->from($table)
            ->query()
            ->toArray();

        $this->flush();
        
        return $result;
    }

    /**
	 * @param string $table
     * @param array $condition
     * @param array $sort
     * @param integer $limit
     * @param integer $offset
     *
     * @return array
     */
    public function findBy($table, $condition = [], $sort = [], $limit = null, $offset = null)
    {
        $where = $param = $sortBy = [];

        if(!empty($condition) && is_array($condition)) {
            foreach ($condition as $key => $value) {
                if(is_array($value)) {
                    $where[] = sprintf('%s IN (%s)', $key, implode(', ', $value));
                } else {
                    $where[$key] = sprintf(':%s', $key);
                    $param[$key] = $value;
                }
            }
        }

        $result = $this->getQB()
            ->select()
            ->from($table)
            ->where($where)
            ->param($param)
            ->order($sort)
            ->limit($limit)
            ->offset($offset)
            ->query()
            ->toArray();

        $this->flush();

        return $result;
    }

    /**
	 * @param string $table
     * @param array $condition
     * @param array $sort
     * @param integer $offset
     *
     * @return array
     */
    public function findOneBy($table, $condition = [], $sort = [], $offset = null)
    {
        $result = $this->findBy($table, $condition, $sort, 1, $offset);

        return !empty($result) ? $result[0] : [];
    }

    /**
	 * @param string $table
     * @param array $dataset
     *
     * @return array
     */
    public function insertOne($table, $dataset)
    {
        unset($dataset["id"]);

        $result = $this->getQB()
            ->insert($table)
            ->values($dataset)
            ->query();

        $dataset["id"] = $result->lastId;

        $this->flush();

        return $dataset;
    }

    /**
	 * @param string $table
     * @param array $dataset
     *
     * @return array
     */
    public function updateOne($table, $dataset)
    {
        $id = $dataset["id"];

        unset($dataset["id"]);

        $result = $this->getQB()
            ->update($table)
            ->set($dataset)
            ->where("id", ":id")
            ->param("id", $id)
            ->query();

        $this->flush();

        return $dataset;
    }

    /**
	 * @param string $table
     * @param array $dataset
     *
     * @return
     */
    public function deleteOne($table, $dataset)
    {
        $id = $dataset["id"];

        $result = $this->getQB()
            ->delete()
            ->from($table)
            ->where("id", ":id")
            ->param("id", $id)
            ->query();

        $this->flush();

        return;
    }

    /**
     * @return \QB\Query
     */
    private function flush()
    {
        return $this->getQB()->query('FLUSH PRIVILEGES');
    }

    /**
     * @return \QB\QB
     */
    private function getQB()
    {
        return $this->qb;
    }
}
