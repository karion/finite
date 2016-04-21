<?php
namespace karion\Finite\Listener;

use Finite\Event\TransitionEvent;
use karion\Finite\Entity\ObjectLog;
use karion\Finite\Repository\ObjectLogRepository;

class ObjectTransmisionListener
{
    /**
     *
     * @var ObjectLogRepository
     */
    protected $objectLogRepo;
    
    public function __construct(ObjectLogRepository $logRepo)
    {
        $this->objectLogRepo = $logRepo;
    }
    
    public function onObjectTransmision(TransitionEvent $event)
    {
        $stateMachine = $event->getStateMachine();
        $object = $stateMachine->getObject();
        $state = $stateMachine->getCurrentState();
        
        $this->objectLogRepo->add(
            new ObjectLog([
                'object_id' => $object->getId(),
                'become' => $state->getName()
            ])
        );
    }
}
