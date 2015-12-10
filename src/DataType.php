<?php

namespace MORM;

/**
 * @package MORM
 * @subpackage DataType
 * @author Eugen Melnychenko
 */
class DataType
{
    const INTEGER       = 'integer';
    const FLOAT         = 'float';
    const STRING        = 'string';
    const ARRAY         = 'serializeArray';
    const JSON_ARRAY    = 'jsonArray';
    const DATE          = 'date';
    const DATETIME      = 'datetime';

    private $dataType;

    /**
     * @param \MORM\Entity $entity
     *
     * @return \MORM\Entity
     */
    protected function iniType(\MORM\Entity $entity)
    {
        $dataType   = [];
        $dataset    = $entity->getData();

        if(!empty($dataset)) {
            foreach ($dataset as $key => $value) {
                $entity->set($key, null);

                $dataType[$key] = $value;
            }
        }

        $this->setDataType($dataType);

        return $entity;
    }

    /**
     * @param \MORM\Entity $entity
     * @param \MORM\QueryLogic $ql
     *
     * @return \MORM\Entity
     */
    public function setType(\MORM\Entity $entity, \MORM\QueryLogic $ql)
    {
        $datatype   = $this->getDataType();

        if(!empty($datatype)) {
            foreach ($datatype as $key => $value) {
                $data = $entity->get($key);

                switch ($value) {
                    case self::INTEGER:
                        $data = (int) $data;
                        break;

                    case self::FLOAT:
                        $data = (float) $data;
                        break;

                    case self::STRING:
                        $data = (string) $data;
                        break;

                    case self::ARRAY:
                        $unserialize = [];

                        if($unserialize = @unserialize($data)) {
                            $data = $unserialize;
                        }
                        break;

                    case self::JSON_ARRAY:
                        $jsonDecode = [];

                        if($jsonDecode = @json_decode($jsonDecode)) {
                            $data = $jsonDecode;
                        }
                        break;

                    case self::DATE:
                        $data = \DateTime::createFromFormat('Y-m-d', $data);
                        break;

                    case self::DATETIME:
                        $data = \DateTime::createFromFormat('Y-m-d H:i:s', $data);
                        break;
                }

                $entity->set($key, $data, $ql);
            }
        }

        return $entity;
    }

    /**
     * @param \MORM\Entity $entity
     *
     * @return array
     */
    public function getType(\MORM\Entity $entity)
    {
        $result     = [];
        $datatype   = $this->getDataType();

        if(!empty($datatype)) {
            foreach ($datatype as $key => $value) {
                $data = $entity->get($key);

                switch ($value) {
                    case self::ARRAY:
                        if(is_array($data)) {
                            $data = @serialize($data);
                        }
                        break;

                    case self::JSON_ARRAY:
                        if(is_array($data)) {
                            $data = @json_encode($data);
                        }
                        break;

                    case self::DATE:
                        if(is_object($data)) {
                            if(get_class($data) === 'DateTime') {
                                $data = $data->format('Y-m-d');
                            }
                        }

                        break;

                    case self::DATETIME:
                        if(is_object($data)) {
                            if(get_class($data) === 'DateTime') {
                                $data = $data->format('Y-m-d H:i:s');
                            }
                        }
                        break;
                }

                $result[$key] = $data;
            }
        }

        return $result;
    }

    /**
     * @param array $dataType
     *
     * @return string
     */
    protected function setDataType($dataType)
    {
        return $this->dataType = @serialize($dataType);
    }

    /**
     * @return array
     */
    protected function getDataType()
    {
        return @unserialize($this->dataType);
    }
}
