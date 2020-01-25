<?php

declare(strict_types=1);

namespace Tigerman55\DoctrineUserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;

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
        $users = $this->em->createQueryBuilder()->select('u')
            ->from($this->config['entity']['user'], 'u')
            ->where('u.' . $this->config['field']['identity'] . ' = :identity')
            ->setParameter('identity', $credential)
            ->getQuery()
            ->getArrayResult();

        if ($users === []) {
            return null;
        }

        $user = $users[0];
        if (password_verify($password ?? '', $user[$this->config['field']['password']] ?? '')) {
            return ($this->userFactory)(
                $credential,
                $this->getUserRoles(),
                $this->getUserDetails($user)
            );
        }

        return null;
    }

    private function getUserRoles(): array
    {
        $roles = $this->em->createQueryBuilder()
            ->select('r')
            ->from($this->config['entity']['role'], 'r')
            ->getQuery()
            ->getArrayResult();

        return array_map(function (array $roles) {
            return $roles[$this->config['field']['role']];
        }, $roles);
    }

    private function getUserDetails(array $user): array
    {
        $details = [];
        foreach ($this->config['user']['details'] as $detail) {
            $details[$detail] = $user[$detail];
        }

        return $details;
    }
}
