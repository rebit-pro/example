<?php

namespace App\Auth\Entity\User;

use App\Auth\Entity\User\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository implements UserRepositoryInterface
{
    private EntityRepository $repo;

    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        $repo = $this->em->getRepository(User::class);
        $this->repo = $repo;
    }

    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function hasByNetwork(Network $identity): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->innerJoin('t.network', 'n')
                ->andWhere('n.network = :name and n.identity = :identity')
                ->setParameter(':name', $identity->getNetwork())
                ->setParameter(':identity', $identity->getIdentity())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function findByConfirmToken(string $token): ?User
    {
        /** @psalm-var User|null */
        return $this->repo->findOneBy(['joinConfirmToken.value' => $token]);
    }

    public function findByPasswordResetToken(string $token): ?User
    {
        /** @psalm-var User|null */
        return $this->repo->findOneBy(['joinPasswordResetToken.value' => $token]);
    }

    public function get(Id $id): User
    {
        $user = $this->repo->find($id->getValue());

        if ($user === null) {
            throw new \DomainException('User is not found');
        }

        /* @var User $user */
        return $user;
    }

    public function getByEmail(Email $email): User
    {
        $user = $this->repo->findOneBy(['email' => $email->getValue()]);

        if ($user === null) {
            throw new \DomainException('User is not found');
        }

        /* @var User $user */
        return $user;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }
}