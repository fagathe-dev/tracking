<?php

namespace App\Entity;

use App\Repository\XtrakLogMetadataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: XtrakLogMetadataRepository::class)]
class XtrakLogMetadata
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'xtrakLogMetadata')]
    private ?XtrakLog $log = null;

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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
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

    public function getLog(): ?XtrakLog
    {
        return $this->log;
    }

    public function setLog(?XtrakLog $log): static
    {
        $this->log = $log;

        return $this;
    }
}
