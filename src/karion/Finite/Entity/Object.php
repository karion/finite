<?php

namespace karion\Finite\Entity;

class Object
{
    protected $id;
    protected $name;
    protected $state;
    /**
     *
     * @var \DateTime
     */
    protected $lastChange;
    
    protected $states = [
        'start',
        'correct',
        'wrong',
        'fillled',
        'done',
        'rejected'
    ];
    
    public function __construct(array $input = null)
    {
        if ($input) {
            $this->id = array_key_exists('id', $input)? $input['id']:null;
            $this->name = array_key_exists('name', $input)? $input['name']:null;
            
            if (array_key_exists('state', $input)) {
                $this->setState($input['state']);
            }
            
            if (array_key_exists('last_change', $input)) {
                $this->setLastChange(new \DateTime("@".$input['last_change']));
            }
            return $this;
        }
        
        $this->state = 'start';
        $this->lastChange = new \DateTime;
        return $this;
    }
    
    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getState()
    {
        return $this->state;
    }

    function getLastChange()
    {
        return $this->lastChange;
    }

    function setId($id)
    {
        $this->id = $id;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setState($state)
    {
        if (!in_array(strtolower($state), $this->states)) {
            throw new \InvalidArgumentException('Unknow state');
        }
        $this->state = $state;
    }

    function setLastChange(\DateTime $lastChange)
    {
        $this->lastChange = $lastChange;
    }

    function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => $this->state,
            'last_change' => $this->lastChange->format('U')
        ];
    }
}
