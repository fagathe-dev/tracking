<?php

namespace App\Entity;

use App\Repository\XtrakCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: XtrakCodeRepository::class)]
#[UniqueEntity(
    fields: ['domain'],
    errorPath: 'domain',
    message: 'Ce site existe déjà !'
)]
class XtrakCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    private ?int $nbRequest = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'code', targetEntity: XtrakEvent::class)]
    private Collection $xtrakEvents;

    #[ORM\Column(length: 10)]
    private ?string $env = null;

    #[ORM\ManyToOne(inversedBy: 'xtrakCodes')]
    private ?XtrakSite $site = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Hostname(message: '{{ value }} n\'est pas un nom de domaine valide.')]
    private ?string $domain = null;

    public function __construct()
    {
        $this->xtrakEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getNbRequest(): ?int
    {
        return $this->nbRequest;
    }

    public function setNbRequest(int $nbRequest): static
    {
        $this->nbRequest = $nbRequest;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, XtrakEvent>
     */
    public function getXtrakEvents(): Collection
    {
        return $this->xtrakEvents;
    }

    public function addXtrakEvent(XtrakEvent $xtrakEvent): static
    {
        if (!$this->xtrakEvents->contains($xtrakEvent)) {
            $this->xtrakEvents->add($xtrakEvent);
            $xtrakEvent->setCode($this);
        }

        return $this;
    }

    public function removeXtrakEvent(XtrakEvent $xtrakEvent): static
    {
        if ($this->xtrakEvents->removeElement($xtrakEvent)) {
            // set the owning side to null (unless already changed)
            if ($xtrakEvent->getCode() === $this) {
                $xtrakEvent->setCode(null);
            }
        }

        return $this;
    }

    public function getEnv(): ?string
    {
        return $this->env;
    }

    public function setEnv(string $env): static
    {
        $this->env = $env;

        return $this;
    }

    public function getSite(): ?XtrakSite
    {
        return $this->site;
    }

    public function setSite(?XtrakSite $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }
}
