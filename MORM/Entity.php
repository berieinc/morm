<?php

namespace MORM;

/**
 * @package MORM
 * @subpackage Entity
 * @author Eugen Melnychenko
 */
class Entity
{
    /**
	 * @param string $pref
     *
     * @return sting
     */
    public function get($pref)
    {
        return $this->$pref;
    }

    /**
	 * @param string $pref
     * @param string $value
     * @param \MORM\QueryLogic $ql
     *
     * @return array
     */
    public function set($pref, $value, \MORM\QueryLogic $ql = null)
    {
        if(!empty($ql)) {
            $this->$pref = $value;
        } elseif(empty($ql) && $pref !== "id") {
            $this->$pref = $value;
        }

        return $this->$pref;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $result = get_object_vars($this);

        return $result;
    }

    /**
     * @param array $dataset
     * @param \MORM\QueryLogic $ql
     *
     * @return \MORM\Entity
     */
    public function setData($dataset, \MORM\QueryLogic $ql = null)
    {
        if(!empty($dataset)) {
            foreach ($dataset as $pref => $value) {
                if(!empty($ql)) {
                    $this->$pref = $value;
                } elseif(empty($ql) && $pref !== "id") {
                    $this->$pref = $value;
                }
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return static::TABLE;
    }
}
