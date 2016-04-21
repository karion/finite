<?php

namespace karion\Finite\Entity;


class ObjectLog
{
    protected $id;
    protected $objectId;
    protected $become;
    /**
     *
     * @var DateTime
     */
    protected $date;
    
    public function __construct(array $input)
    {
        
        $this->id = array_key_exists('id', $input)? $input['id']:null;
        
        if(!array_key_exists('object_id', $input)) {
            throw new \InvalidArgumentException('Object_id must be set');
        }
        $this->objectId = $input['object_id'];
        
        if(!array_key_exists('become', $input)) {
            throw new \InvalidArgumentException('Become must be set');
        }
        $this->become = $input['become'];

        if (array_key_exists('last_change', $input)) {
            $this->date = new \DateTime("@".$input['date']);
        } else {
            $this->date = new \DateTime;
        }
        
        return $this;
    }
    
    function getId()
    {
        return $this->id;
    }

    function getObjectId()
    {
        return $this->objectId;
    }

    function getBecome()
    {
        return $this->become;
    }

    function getDate()
    {
        return $this->date;
    }
    
    public function toArray()
    {
        return [
            'id' => $this->id,
            'object_id' => $this->objectId,
            'become' => $this->become,
            'date' => $this->date->format('U')
        ];
    }
}
