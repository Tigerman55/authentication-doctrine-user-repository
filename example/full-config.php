<?php
return [
    'authentication' => [
        'doctrine'   => [
            'entity-manager-class' => 'Doctrine\ORM\EntityManager', // defaults to orm_default
            'entity' => [
                'user' => 'App\Entity\User',
            ],
            'field'  => [
                'identity' => 'email',
            ],
        ],
    ],
];