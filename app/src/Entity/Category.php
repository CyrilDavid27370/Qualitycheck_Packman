<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, CategoryType>
     */
    #[ORM\OneToMany(targetEntity: CategoryType::class, mappedBy: 'category')]
    private Collection $categoryTypes;

    public function __construct()
    {
        $this->categoryTypes = new ArrayCollection();
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

    /**
     * @return Collection<int, CategoryType>
     */
    public function getCategoryTypes(): Collection
    {
        return $this->categoryTypes;
    }

    public function addCategoryType(CategoryType $categoryType): static
    {
        if (!$this->categoryTypes->contains($categoryType)) {
            $this->categoryTypes->add($categoryType);
            $categoryType->setCategory($this);
        }

        return $this;
    }

    public function removeCategoryType(CategoryType $categoryType): static
    {
        if ($this->categoryTypes->removeElement($categoryType)) {
            // set the owning side to null (unless already changed)
            if ($categoryType->getCategory() === $this) {
                $categoryType->setCategory(null);
            }
        }

        return $this;
    }
}
