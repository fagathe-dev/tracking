<?php

namespace App\Entity;

use App\Repository\XtrakLogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: XtrakLogRepository::class)]
class XtrakLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 90, nullable: true)]
    private ?string $userAgent = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $origin = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'log')]
    private ?XtrakEvent $xtrakEvent = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $device = null;

    #[ORM\OneToMany(mappedBy: 'log', targetEntity: XtrakLogMetadata::class)]
    private Collection $xtrakLogMetadata;

    public function __construct()
    {
        $this->xtrakLogMetadata = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): static
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(?string $origin): static
    {
        $this->origin = $origin;

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

    public function getXtrakEvent(): ?XtrakEvent
    {
        return $this->xtrakEvent;
    }

    public function setXtrakEvent(?XtrakEvent $xtrakEvent): static
    {
        $this->xtrakEvent = $xtrakEvent;

        return $this;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(?string $device): static
    {
        $this->device = $device;

        return $this;
    }

    /**
     * @return Collection<int, XtrakLogMetadata>
     */
    public function getXtrakLogMetadata(): Collection
    {
        return $this->xtrakLogMetadata;
    }

    public function addXtrakLogMetadata(XtrakLogMetadata $xtrakLogMetadata): static
    {
        if (!$this->xtrakLogMetadata->contains($xtrakLogMetadata)) {
            $this->xtrakLogMetadata->add($xtrakLogMetadata);
            $xtrakLogMetadata->setLog($this);
        }

        return $this;
    }

    public function removeXtrakLogMetadata(XtrakLogMetadata $xtrakLogMetadata): static
    {
        if ($this->xtrakLogMetadata->removeElement($xtrakLogMetadata)) {
            // set the owning side to null (unless already changed)
            if ($xtrakLogMetadata->getLog() === $this) {
                $xtrakLogMetadata->setLog(null);
            }
        }

        return $this;
    }
}
