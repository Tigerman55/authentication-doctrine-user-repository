<?php

declare(strict_types=1);

namespace Tigerman55\DoctrineUserRepository;

use Mezzio\Authentication\Exception;
use Mezzio\Authentication\UserInterface;
use Psr\Container\ContainerInterface;

class DoctrineUserRepositoryFactory
{
    public function __invoke(ContainerInterface $container): DoctrineUserRepository
    {
        $config = $container->get('config')['authentication']['doctrine'] ?? null;
        if (null === $config) {
            throw new Exception\InvalidConfigException(
                'Doctrine values are missing in authentication config'
            );
        }

        return new DoctrineUserRepository(
            $container->get($config['entity-manager-class'] ?? 'orm_default'),
            $config,
            $container->get(UserInterface::class)
        );
    }
}
