<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('post_get')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your name must be at least {{ limit }} characters long',
        maxMessage: 'Your name cannot be longer than {{ limit }} characters',
    )]
    #[Groups('post_get')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your email must be at least {{ limit }} characters long',
        maxMessage: 'Your email cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Groups('post_get')]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Unicorn::class)]
    private Collection $unicorns;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->unicorns = new ArrayCollection();
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
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Unicorn>
     */
    public function getUnicorns(): Collection
    {
        return $this->unicorns;
    }

    public function addUnicorn(Unicorn $unicorn): static
    {
        if (!$this->unicorns->contains($unicorn)) {
            $this->unicorns->add($unicorn);
            $unicorn->setUser($this);
        }

        return $this;
    }

    public function removeUnicorn(Unicorn $unicorn): static
    {
        if ($this->unicorns->removeElement($unicorn)) {
            // set the owning side to null (unless already changed)
            if ($unicorn->getUser() === $this) {
                $unicorn->setUser(null);
            }
        }

        return $this;
    }
}
