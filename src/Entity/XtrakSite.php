<?php

namespace App\Entity;

use App\Repository\XtrakSiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: XtrakSiteRepository::class)]
#[UniqueEntity(
    fields: ['domain'],
    errorPath: 'domain',
    message: 'Ce site existe déjà !'
)]
class XtrakSite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['xtrakSite_read'])]
    private ?string $name = null;
    
    #[ORM\Column(length: 100)]
    #[Assert\Hostname(message: '{{ value }} n\'est pas un nom de domaine valide.')]
    #[Groups(['xtrakSite_read'])]
    private ?string $domain = null;
    
    #[ORM\Column(length: 15)]
    #[Groups(['xtrakSite_read'])]
    private ?string $env = null;
    
    #[ORM\OneToMany(mappedBy: 'site', targetEntity: XtrakCode::class)]
    private Collection $xtrakCodes;
    
    #[ORM\Column]
    #[Groups(['xtrakSite_read'])]
    private ?bool $isActive = null;
    
    #[ORM\Column]
    #[Groups(['xtrakSite_read'])]
    private ?\DateTimeImmutable $createdAt = null;
    
    #[ORM\Column(nullable: true)]
    #[Groups(['xtrakSite_read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->xtrakCodes = new ArrayCollection();
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

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;

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

    /**
     * @return Collection<int, XtrakCode>
     */
    public function getXtrakCodes(): Collection
    {
        return $this->xtrakCodes;
    }

    public function addXtrakCode(XtrakCode $xtrakCode): static
    {
        if (!$this->xtrakCodes->contains($xtrakCode)) {
            $this->xtrakCodes->add($xtrakCode);
            $xtrakCode->setSite($this);
        }

        return $this;
    }

    public function removeXtrakCode(XtrakCode $xtrakCode): static
    {
        if ($this->xtrakCodes->removeElement($xtrakCode)) {
            // set the owning side to null (unless already changed)
            if ($xtrakCode->getSite() === $this) {
                $xtrakCode->setSite(null);
            }
        }

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

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
}
