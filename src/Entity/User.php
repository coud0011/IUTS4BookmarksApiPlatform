<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use App\Controller\GetAvatarController;
use App\Repository\UserRepository;
use App\State\MeProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    normalizationContext: ['groups' => ['User_read']]
)]
#[Get]
#[Patch(
    normalizationContext: ['groups' => ['User_read', 'User_me']],
    denormalizationContext: ['groups' => ['User_write']],
    security: 'user == object'
)]
#[Get(
    uriTemplate: '/me',
    openapiContext: [
        'summary' => 'Retrieves the connected user',
        'description' => 'Retrieves the connected user',
        'responses' => [
            '200' => [
                'description' => 'connected user resource',
                'content' => [
                    'application/ld+json' => [
                        'schema' => [
                            '$ref' => '#/components/schemas/User.jsonld-User_me_User_read',
                        ],
                    ],
                ],
            ],
        ],
    ],
    normalizationContext: ['groups' => ['User_read', 'User_me']],
    provider: MeProvider::class
)
]
#[Get(
    uriTemplate: '/users/{id}/avatar',
    formats: [
        'png' => 'image/png',
    ],
    controller: GetAvatarController::class,
    openapiContext: [
        'responses' => [
            '200' => [
                'description' => 'The user avatar',
                'content' => [
                    'image/png' => [
                        'schema' => [
                            'type' => 'string',
                            'format' => 'binary',
                        ],
                    ],
                ],
            ],
            '404' => [
                'description' => 'User does not exist',
            ],
        ],
    ]
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('login')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('User_read')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['User_read', 'User_write'])]
    #[Assert\Regex('/^[^<>&"]*\w+$/')]
    #[ApiProperty(
        openapiContext: [
            'example' => 'user1',
        ]
    )]
    private ?string $login = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    #[Groups('User_write')]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    #[Groups(['User_read', 'User_write'])]
    #[Assert\Regex('/^[^<>&"]*\w+$/')]
    #[ApiProperty(
        openapiContext: [
            'example' => 'Axel',
        ]
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 40)]
    #[Groups(['User_read', 'User_write'])]
    #[Assert\Regex('/^[^<>&"]*\w+$/')]
    #[ApiProperty(
        openapiContext: [
            'example' => 'Coudrot',
        ]
    )]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::BLOB)]
    /**
     * @var resource
     */
    private $avatar;

    #[ORM\Column(length: 100)]
    #[Groups(['User_write', 'User_me'])]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Rating::class, orphanRemoval: true)]
    private Collection $ratings;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): static
    {
        $this->avatar = $avatar;

        return $this;
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

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setUser($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getUser() === $this) {
                $rating->setUser(null);
            }
        }

        return $this;
    }
}
