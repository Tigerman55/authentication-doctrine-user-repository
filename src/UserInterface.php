<?php

declare(strict_types=1);

namespace Tigerman55\AuthenticationDoctrineUserRepository;

use Doctrine\ORM\PersistentCollection;

interface UserInterface
{
    /** @return PersistentCollection|RoleInterface[] */
    public function getRoles(): PersistentCollection;

    public function getPasswordHash(): string;

    public function getDetails(): array;
}
