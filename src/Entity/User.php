<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[
    UniqueEntity(
        fields: ['email'],
        errorPath: 'email',
        message: 'Cette adresse email est déjà utilisée !'
    ),
    UniqueEntity(
        fields: ['username'],
        errorPath: 'username',
        message: 'Ce nom d\'utilisateur est déjà utilisé !'
    )
]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Ce champ est obligatoire !')]
    #[Assert\Email(message: 'Cette adresse e-mail n\'est pas valide !')]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom d\'utilisateur est obligatoire !', allowNull: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le mot de passe est obligatoire !')]
    #[Assert\Regex(
        pattern: '#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]){8,}#',
        message: 'Le mot de passe doit contenir au moins une masjuscule, une minuscule et un chiffre avec au moins 8 caractères !',
        match: true
    )]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserRequest::class)]
    private Collection $userRequests;

    public function __construct()
    {
        $this->userRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, UserRequest>
     */
    public function getUserRequests(): Collection
    {
        return $this->userRequests;
    }

    public function addUserRequest(UserRequest $userRequest): static
    {
        if (!$this->userRequests->contains($userRequest)) {
            $this->userRequests->add($userRequest);
            $userRequest->setUser($this);
        }

        return $this;
    }

    public function removeUserRequest(UserRequest $userRequest): static
    {
        if ($this->userRequests->removeElement($userRequest)) {
            // set the owning side to null (unless already changed)
            if ($userRequest->getUser() === $this) {
                $userRequest->setUser(null);
            }
        }

        return $this;
    }
}
