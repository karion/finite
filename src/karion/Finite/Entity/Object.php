<?php

namespace karion\Finite\Entity;

use DateTime;
use Finite\StatefulInterface;
use InvalidArgumentException;

class Object implements StatefulInterface
{
    protected $id;
    protected $name;
    protected $state;
    /**
     *
     * @var DateTime
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
                $this->lastChange = new DateTime("@".$input['last_change']);
            }
            return $this;
        }
        
        $this->state = 'start';
        $this->lastChange = new DateTime;
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
        $this->lastChange = new DateTime;
    }

    function setName($name)
    {
        $this->name = $name;
        $this->lastChange = new DateTime;
    }

    function setState($state)
    {
        if (!in_array(strtolower($state), $this->states)) {
            throw new InvalidArgumentException('Unknow state');
        }
        $this->state = $state;
        $this->lastChange = new DateTime;
    }

//    function setLastChange(DateTime $lastChange)
//    {
//        $this->lastChange = $lastChange;
//    }
    
    function getFiniteState()
    {
        return $this->state;
    }
    
    public function setFiniteState($state)
    {
        if (!in_array(strtolower($state), $this->states)) {
            throw new InvalidArgumentException('Unknow state');
        }
        $this->state = $state;
        $this->lastChange = new DateTime;
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
