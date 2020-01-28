<?php

declare(strict_types=1);

namespace Tigerman55\AuthenticationDoctrineUserRepository;

interface UserInterface
{
    /** @return RoleInterface[] */
    public function getRoles(): array;

    public function getPasswordHash(): string;

    public function getDetails(): array;
}
