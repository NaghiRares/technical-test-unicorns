<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UnicornRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\Status;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: UnicornRepository::class)]
class Unicorn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['post_get', 'post_update', 'unicorn_get'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Your unicorn name must be at least {{ limit }} characters long',
        maxMessage: 'Your unicorn name can not be longer than {{ limit }} characters',
    )]
    #[Groups(['post_get', 'post_update', 'unicorn_get'])]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: 'Your name must be at least {{ limit }} characters long',
        maxMessage: 'Your name cannot be longer than {{ limit }} characters',
    )]
    #[Groups(['post_get', 'post_update', 'unicorn_get'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\NotBlank]
    #[Assert\Range(
        min: 1000,
        max: 9000,
        notInRangeMessage: 'The price must be between {{ min }} and {{ max }}',
    )]
    #[Groups(['post_get', 'post_update', 'unicorn_get'])]
    private ?float $price = null;

    #[ORM\Column(type: Types::INTEGER, enumType: Status::class)]
    #[Groups(['post_get', 'post_update', 'unicorn_get'])]
    private Status $status = Status::UNPURCHASED;

    #[ORM\OneToMany(mappedBy: 'unicorn', targetEntity: Post::class, cascade: ['persist', 'remove'])]
    private Collection $posts;

    #[ORM\ManyToOne(inversedBy: 'unicorns')]
    private ?User $user = null;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUnicorn($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUnicorn() === $this) {
                $post->setUnicorn(null);
            }
        }

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
}
