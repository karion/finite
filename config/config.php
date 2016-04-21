<?php

return [
    'db' => [
        'user' => '',
        'password' => '',
        'path' => '../data/finite.db'
    ],
    
    'displayErrorDetails' => true,
    
    'state_machine' => [
        'class'  => karion\Finite\Entity\Object::class,
        'states' => [
            'start'    => ['type' => 'initial', 'properties' => []],
            'correct'  => ['type' => 'normal',  'properties' => []],
            'wrong'    => ['type' => 'normal',  'properties' => []],
            'filled'   => ['type' => 'normal',  'properties' => []],
            'done'     => ['type' => 'final',   'properties' => []],
            'rejected' => ['type' => 'final',   'properties' => []],
        ],
        'transitions' => [
            'valid'     => ['from' => ['start'],   'to' => 'correct'],
            'not_valid' => ['from' => ['start'],   'to' => 'wrong'],
            'correct'   => ['from' => ['wrong'],   'to' => 'correct'],
            'dump'      => ['from' => ['wrong'],   'to' => 'rejected'],
            'fill'      => ['from' => ['correct'], 'to' => 'filled'],
            'reject'    => ['from' => ['filled'],   'to' => 'rejected'],
            'acepted'   => ['from' => ['filled'],   'to' => 'done'],
        ]
    ]
];