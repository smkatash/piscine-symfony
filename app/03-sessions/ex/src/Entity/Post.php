<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: '`post`')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn( nullable: false, onDelete: "CASCADE" )]
    private ?User $author = null;
    
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'postEdits')]
    #[ORM\JoinColumn( nullable: true, onDelete: "SET NULL" )]
    private ?User $editor = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "likedPosts")]
    private Collection $likedByUsers;
    
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: "dislikedPosts")]
    private Collection $dislikedByUsers;

    public function __construct()
    {
        $this->likedByUsers = new ArrayCollection();
        $this->dislikedByUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();

        return $this;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();

        return $this;
    }

    public function getEditor(): ?User
    {
        return $this->editor;
    }

    public function setEditor(User $editor): static
    {
        $this->editor = $editor;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getLikedByUsers(): Collection
    {
        return $this->likedByUsers;
    }

    public function addLikedByUser(User $user): void
    {
        if (!$this->likedByUsers->contains($user)) {
            $this->likedByUsers->add($user);
        }
    }

    public function removeLikedByUser(User $user): void
    {
        if ($this->likedByUsers->contains($user)) {
            $this->likedByUsers->removeElement($user);
        }
    }

    public function getDislikedByUsers(): Collection
    {
        return $this->dislikedByUsers;
    }

    public function addDislikeByUser(User $user): void
    {
        if (!$this->dislikedByUsers->contains($user)) {
            $this->dislikedByUsers->add($user);
        }
    }

    public function removeDislikeByUser(User $user): void
    {
        if ($this->dislikedByUsers->contains($user)) {
            $this->dislikedByUsers->removeElement($user);
        }
    }
}
