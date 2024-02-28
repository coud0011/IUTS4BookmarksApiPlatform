<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\RatingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[Get]
#[Post]
#[GetCollection]
#[Put]
#[Delete(
    security: 'object.getUser() == user',
)]
#[Patch(
    security: 'object.getUser() == user',
)]
#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[UniqueEntity(
    fields: ['bookmark', 'user'],
    message: 'This bookmark has already been rated by this user.',
    errorPath: 'bookmark',
)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiProperty(
        openapiContext: [
            'example' => 'message',
        ]
    )]
    private ?Bookmark $bookmark = null;

    #[ApiProperty(
        openapiContext: [
            'example' => 'user1',
        ]
    )]
    #[ORM\ManyToOne(inversedBy: 'ratings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ApiProperty(
        openapiContext: [
            'example' => 1,
        ]
    )]
    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\LessThan(11)]
    #[Assert\GreaterThan(-1)]
    private ?int $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookmark(): ?Bookmark
    {
        return $this->bookmark;
    }

    public function setBookmark(?Bookmark $bookmark): static
    {
        $this->bookmark = $bookmark;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
