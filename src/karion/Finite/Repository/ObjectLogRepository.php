<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace karion\Finite\Repository;

use Doctrine\DBAL\Connection;
use karion\Finite\Entity\ObjectLog;

/**
 * Description of ObjectLogRepository
 *
 * @author tomasz
 */
class ObjectLogRepository
{
    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }
    
    public function findByObjectId($objectId)
    {
        $statement = $this->db->prepare('SELECT * FROM object_log WHERE object_id = :id ORDER BY date');
        $statement->bindParam('id', $objectId);
        $statement->execute();
        $objectLogsArray = $statement->fetchAll();

        if (empty ($objectLogsArray)) {
            return [];
        }
        
        return array_map(
            function ($input) {
                return new ObjectLog($input);
            },
            $objectLogsArray
        );
    }
    
    public function add(ObjectLog $log)
    {
        $logArray = $log->toArray();
        
        $this->db->insert('object_log', $logArray);
    }
}
