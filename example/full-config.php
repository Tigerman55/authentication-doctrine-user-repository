<?php
return [
    'authentication' => [
        'doctrine'   => [
            'entity-manager-class' => 'Doctrine\ORM\EntityManager', // defaults to orm_default
            'entity' => [
                'user' => 'App\Entity\User',
                'role' => 'App\Entity\Role',
            ],
            'field'  => [
                'identity' => 'email',
                'password' => 'passwordHash',
                'role'     => 'name', // this is the primary field name within your roles entity
            ],
            'user'   => [
                'details' => [
                    'displayName',
                    'jobTitle',
                ],
            ],
        ],
    ],
];