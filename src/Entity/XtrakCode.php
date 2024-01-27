<?php

namespace App\Entity;

use App\Repository\XtrakCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: XtrakCodeRepository::class)]
class XtrakCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\ManyToOne(inversedBy: 'xtrakCodes')]
    private ?XtrakSite $site = null;

    #[ORM\Column(length: 60, nullable: true)]
    private ?string $page = null;

    #[ORM\Column]
    private ?int $nbRequest = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'code', targetEntity: XtrakEvent::class)]
    private Collection $xtrakEvents;

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

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

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(?string $page): static
    {
        $this->page = $page;

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
}
