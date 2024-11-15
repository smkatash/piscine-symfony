<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;
    
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'likedByUsers')]
    #[ORM\JoinTable(name: 'user_liked_posts')]
    private Collection $likedPosts;

    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'dislikedByUsers')]
    #[ORM\JoinTable(name: 'user_disliked_posts')]
    private Collection $dislikedPosts;

    #[ORM\OneToMany(targetEntity: Post::class, cascade: ['persist', 'remove'], mappedBy: 'author')]
    private Collection $posts;
    
    #[ORM\OneToMany(targetEntity: Post::class, cascade: ['persist'], mappedBy: 'editor')]
    private Collection $postEdits;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->postEdits = new ArrayCollection();
        $this->likedPosts = new ArrayCollection();
        $this->dislikedPosts = new ArrayCollection();
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
    
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    
    public function __toString(): string
    {
        return $this->getName();
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
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

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function getPostEdits(): Collection
    {
        return $this->postEdits;
    }

    public function addPostEdit(Post $post): self
    {
        if (!$this->postEdits->contains($post)) {
            $this->postEdits->add($post);
            $post->setEditor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }
    
    public function getLikedPosts(): Collection
    {
        return $this->likedPosts;
    }

    public function addLikedPost(Post $post): void
    {
        if (!$this->likedPosts->contains($post)) {
            $this->likedPosts->add($post);
        }
    }

    public function removeLikedPost(Post $post): void
    {
        $this->likedPosts->removeElement($post);
    }

    public function getDislikedPosts(): Collection
    {
        return $this->dislikedPosts;
    }

    public function addDislikedPost(Post $post): void
    {
        if (!$this->dislikedPosts->contains($post)) {
            $this->dislikedPosts->add($post);
        }
    }

    public function removeDislikedPost(Post $post): void
    {
        $this->dislikedPosts->removeElement($post);
    }

}
