<?php

namespace karion\Finite\Repository;

use Doctrine\DBAL\Connection;
use karion\Finite\Entity\Object;

class ObjectRepository
{
    /**
     *
     * @var Connection
     */
    protected $db;
    
    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }
    
    public function findAll()
    {
        $statement = $this->db->prepare('SELECT * FROM object');
        $statement->execute();
        
        $objectsArray = $statement->fetchAll();
        
        return array_map(
            function ($input) {
                return new Object($input);
            },
            $objectsArray
        );
    }
    
    public function findById($id)
    {
        $statement = $this->db->prepare('SELECT * FROM object WHERE id = :id');
        $statement->bindParam('id', $id);
        $statement->execute();
        $objectsArray = $statement->fetchAll();

        if (empty ($objectsArray)) {
            return null;
        }
        
        $input = current($objectsArray);
        
        return new Object($input);
    }
    
    public function save(Object $object)
    {
        if ($object->getId()) {
            return $this->update($object);
        } 
        
        return $this->add($object);
    }
    
    protected function add(Object $object)
    {
        $array = $object->toArray();
        unset($array['id']);
        $this->db->insert('object', $array);
        
        $id = $this->db->lastInsertId();
        
        $object->setId($id);
        
        return $object;
    }
    
    protected function update(Object $object)
    {
        $array = $object->toArray();
        $id = $array['id'];
        
        unset($array['id']);
        
        $this->db->update('object', $array, ['id' => $id]);
        
        return $object;
    }
    
    
}
