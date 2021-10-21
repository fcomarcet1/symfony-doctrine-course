<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use LogicException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use function filter_var;
use const FILTER_VALIDATE_EMAIL;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    private string $id;
    private string $name;
    private string $email;
    private array $roles = [];
    private string $password;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(string $name, string $email)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->name = $name;
        $this->email = $email;
        $this->password = '123456';
        $this->createdAt = new DateTime();
        $this->markAsUpdated();
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new LogicException('Invalid email');
        }

        $this->email = $email;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}