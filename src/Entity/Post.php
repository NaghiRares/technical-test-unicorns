<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['post_get', 'post_update'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Your content must be at least {{ limit }} characters long',
        maxMessage: 'Your content can not be longer than {{ limit }} characters',
    )]
    #[Groups(['post_get', 'post_update'])]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'posts', cascade: ['persist'])]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    #[Groups('post_get')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'posts', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn()]
    #[Groups('post_get')]
    private ?Unicorn $unicorn = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUnicorn(): ?Unicorn
    {
        return $this->unicorn;
    }

    public function setUnicorn(?Unicorn $unicorn): static
    {
        $this->unicorn = $unicorn;

        return $this;
    }
}
