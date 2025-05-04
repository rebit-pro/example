<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(readOnly: true)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'auth_user_networks')]
#[ORM\UniqueConstraint(columns: ['network_name', 'network_identity'])]
final class UserNetwork
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    // Если хотите явно показать, что сами генерируете:
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private string $id;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'networks')]
        #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
        private User $user,
        #[ORM\Embedded(class: Network::class)]
        private readonly Network $network
    ) {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getNetwork(): Network
    {
        return $this->network;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getId(): string
    {
        return $this->id;
    }
}