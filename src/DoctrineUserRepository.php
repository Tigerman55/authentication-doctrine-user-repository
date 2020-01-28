<?php

declare(strict_types=1);

namespace Tigerman55\AuthenticationDoctrineUserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Tigerman55\AuthenticationDoctrineUserRepository\UserInterface as EntityUserInterface;

use function array_map;
use function password_verify;

class DoctrineUserRepository implements UserRepositoryInterface
{
    private $em;
    private $config;
    private $userFactory;

    public function __construct(
        EntityManagerInterface $em,
        array $config,
        callable $userFactory
    ) {
        $this->em     = $em;
        $this->config = $config;

        // Provide type safety for the composed user factory.
        $this->userFactory = static function (
            string $identity,
            array $roles = [],
            array $details = []
        ) use ($userFactory): UserInterface {
            return $userFactory($identity, $roles, $details);
        };
    }

    public function authenticate(string $credential, ?string $password = null): ?UserInterface
    {
        $users = $this->em->createQueryBuilder()->select('u, r')
            ->from($this->config['entity']['user'], 'u')
            ->innerJoin('u.roles', 'r')
            ->where('u.' . $this->config['field']['identity'] . ' = :identity')
            ->setParameter('identity', $credential)
            ->getQuery()
            ->getResult();

        if ($users === []) {
            return null;
        }

        /** @var EntityUserInterface $user */
        $user = $users[0];
        if (password_verify($password ?? '', $user->getPasswordHash() ?? '')) {
            return ($this->userFactory)(
                $credential,
                array_map(function (RoleInterface $role): string {
                    return $role->getName();
                }, $user->getRoles()),
                $user->getDetails()
            );
        }

        return null;
    }
}
