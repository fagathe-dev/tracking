<?php

namespace App\Entity;

use App\Repository\XtrakEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: XtrakEventRepository::class)]
class XtrakEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'xtrakEvents')]
    private ?XtrakCode $code = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 30)]
    private ?string $action = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $page = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $uri = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column(length: 60)]
    private ?string $ref = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'xtrakEvent', targetEntity: XtrakLog::class)]
    private Collection $log;

    public function __construct()
    {
        $this->log = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?XtrakCode
    {
        return $this->code;
    }

    public function setCode(?XtrakCode $code): static
    {
        $this->code = $code;

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

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

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

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(?string $uri): static
    {
        $this->uri = $uri;

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

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

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
     * @return Collection<int, XtrakLog>
     */
    public function getLog(): Collection
    {
        return $this->log;
    }

    public function addLog(XtrakLog $log): static
    {
        if (!$this->log->contains($log)) {
            $this->log->add($log);
            $log->setXtrakEvent($this);
        }

        return $this;
    }

    public function removeLog(XtrakLog $log): static
    {
        if ($this->log->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getXtrakEvent() === $this) {
                $log->setXtrakEvent(null);
            }
        }

        return $this;
    }
}
