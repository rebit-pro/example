<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use AllowDynamicProperties;
use App\Auth\Service\PasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 *
 * @ORM\Entity(readOnly=true)
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="auth_user_networks", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"network_name", "newtork_identity"})
 * })
 * */
final class UserNetwork
{
   /**
    * @ORM\Column(type="guid")
    * */
   private string $id;

    public function __construct(
        /**
         * @var User
         * @ORM\ManyToOne(targetEntity="User", inversedBy="networks")
         * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * */
        private User $user,
        /**
         * @var Network
         * @ORM\Embedded(class="Network")
         * */
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
