<?php

declare(strict_types=1);

namespace Tigerman55\DoctrineUserRepository;

final class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                DoctrineUserRepository::class => DoctrineUserRepositoryFactory::class,
            ],
        ];
    }
}
