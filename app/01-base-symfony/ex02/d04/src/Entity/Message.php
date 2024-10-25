<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;
    
    #[ORM\Column()]
    private bool $includeTimestamp = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timestamp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        if (empty($message)) {
            throw new InvalidArgumentException('Message cannot be empty.');
        }
        $this->message = $message;

        return $this;
    }

    
    public function setIncludeTimestamp(bool $include): bool
    {
        $this->includeTimestamp = $include;
        return $this->includeTimestamp;
    }
    
    public function getIncludeTimestamp(): bool
    {
        return $this->includeTimestamp;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }
    
    public function setTimestamp(?\DateTimeInterface $timestamp = null): static
    {
        $this->timestamp = new \DateTimeImmutable();
        return $this;
    }

    public function getOutput(): ?string
    {
        $timestamp = "";
        if ($this->getIncludeTimestamp()) {
            $timestamp = $this->getTimestamp()->format('Y-m-d H:i:s');
        }

        return $this->message . " " . $timestamp . "\n";
    }
}
