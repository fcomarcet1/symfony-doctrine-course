<?php
declare(strict_types=1);


namespace App\Entity;

class User
{

    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private \DateTime $createdAt;
    private \DateTime $updatedAt;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
        $this->createdAt = new \DateTime();
        $this->markAsUpdated();

    }

     
    public function getId(): int
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
        if (!\filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            throw new \LogicException('Invalid email');
        }

        $this->email = $email;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

 
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    
    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }

}