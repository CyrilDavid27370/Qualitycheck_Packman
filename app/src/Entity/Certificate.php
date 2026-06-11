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
    private ?string $delivery_site = null;

    #[ORM\Column(length: 50)]
    private ?string $GPS_ref = null;

    #[ORM\Column(length: 50)]
    private ?string $round = null;

    #[ORM\Column(length: 50)]
    private ?string $project = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $delivery_date = null;

    #[ORM\Column]
    private ?bool $has_derogation = null;

    #[ORM\Column]
    private ?bool $is_hare = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $association_derogation = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $restrictive = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $comodif = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column]
    private ?bool $ready_to_deliver = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

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
        return $this->delivery_site;
    }

    public function setDeliverySite(string $delivery_site): static
    {
        $this->delivery_site = $delivery_site;

        return $this;
    }

    public function getGPSRef(): ?string
    {
        return $this->GPS_ref;
    }

    public function setGPSRef(string $GPS_ref): static
    {
        $this->GPS_ref = $GPS_ref;

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
        return $this->delivery_date;
    }

    public function setDeliveryDate(\DateTime $delivery_date): static
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function hasDerogation(): ?bool
    {
        return $this->has_derogation;
    }

    public function setHasDerogation(bool $has_derogation): static
    {
        $this->has_derogation = $has_derogation;

        return $this;
    }

    public function isHare(): ?bool
    {
        return $this->is_hare;
    }

    public function setIsHare(bool $is_hare): static
    {
        $this->is_hare = $is_hare;

        return $this;
    }

    public function getAssociationDerogation(): ?string
    {
        return $this->association_derogation;
    }

    public function setAssociationDerogation(?string $association_derogation): static
    {
        $this->association_derogation = $association_derogation;

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
        return $this->ready_to_deliver;
    }

    public function setReadyToDeliver(bool $ready_to_deliver): static
    {
        $this->ready_to_deliver = $ready_to_deliver;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

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
