<?php

namespace App\Entity;

use App\Repository\CertificateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificateRepository::class)]
class Certificate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $deliverySite = null;

    #[ORM\Column(length: 50)]
    private ?string $gpsRef = null;

    #[ORM\Column(length: 50)]
    private ?string $round = null;

    #[ORM\Column(length: 50)]
    private ?string $project = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $deliveryDate = null;

    #[ORM\Column]
    private ?bool $hasDerogation = null;

    #[ORM\Column]
    private ?bool $isHare = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $associatedDerogation = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $restrictive = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $comodif = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column]
    private ?bool $readyToDeliver = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'certificates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'certificates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategoryType $categoryType = null;

    /**
     * @var Collection<int, Evaluation>
     */
    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'certificate')]
    private Collection $evaluations;

    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliverySite(): ?string
    {
        return $this->deliverySite;
    }

    public function setDeliverySite(string $deliverySite): static
    {
        $this->deliverySite = $deliverySite;

        return $this;
    }

    public function getGPSRef(): ?string
    {
        return $this->gpsRef;
    }

    public function setGPSRef(string $gpsRef): static
    {
        $this->gpsRef = $gpsRef;

        return $this;
    }

    public function getRound(): ?string
    {
        return $this->round;
    }

    public function setRound(string $round): static
    {
        $this->round = $round;

        return $this;
    }

    public function getProject(): ?string
    {
        return $this->project;
    }

    public function setProject(string $project): static
    {
        $this->project = $project;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTime
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(\DateTime $deliveryDate): static
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function hasDerogation(): ?bool
    {
        return $this->hasDerogation;
    }

    public function setHasDerogation(bool $hasDerogation): static
    {
        $this->hasDerogation = $hasDerogation;

        return $this;
    }

    public function isHare(): ?bool
    {
        return $this->isHare;
    }

    public function setIsHare(bool $isHare): static
    {
        $this->isHare = $isHare;

        return $this;
    }

    public function getAssociatedDerogation(): ?string
    {
        return $this->associatedDerogation;
    }

    public function setAssociatedDerogation(?string $associatedDerogation): static
    {
        $this->associatedDerogation = $associatedDerogation;

        return $this;
    }

    public function getRestrictive(): ?string
    {
        return $this->restrictive;
    }

    public function setRestrictive(?string $restrictive): static
    {
        $this->restrictive = $restrictive;

        return $this;
    }

    public function getComodif(): ?string
    {
        return $this->comodif;
    }

    public function setComodif(?string $comodif): static
    {
        $this->comodif = $comodif;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function isReadyToDeliver(): ?bool
    {
        return $this->readyToDeliver;
    }

    public function setReadyToDeliver(bool $readyToDeliver): static
    {
        $this->readyToDeliver = $readyToDeliver;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCategoryType(): ?CategoryType
    {
        return $this->categoryType;
    }

    public function setCategoryType(?CategoryType $categoryType): static
    {
        $this->categoryType = $categoryType;

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): static
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->setCertificate($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): static
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getCertificate() === $this) {
                $evaluation->setCertificate(null);
            }
        }

        return $this;
    }
}
