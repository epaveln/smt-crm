<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContractorTypeRepository")
 */
class ContractorType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contractor", mappedBy="contractorType")
     */
    private $contractors;

    public function __construct()
    {
        $this->contractors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContractor(): ?Contractor
    {
        return $this->contractor;
    }

    public function setContractor(Contractor $contractor): self
    {
        $this->contractor = $contractor;

        // set the owning side of the relation if necessary
        if ($this !== $contractor->getContractorType()) {
            $contractor->setContractorType($this);
        }

        return $this;
    }

    /**
     * @return Collection|Contractor[]
     */
    public function getContractors(): Collection
    {
        return $this->contractors;
    }

    public function addContractor(Contractor $contractor): self
    {
        if (!$this->contractors->contains($contractor)) {
            $this->contractors[] = $contractor;
            $contractor->setContractorType($this);
        }

        return $this;
    }

    public function removeContractor(Contractor $contractor): self
    {
        if ($this->contractors->contains($contractor)) {
            $this->contractors->removeElement($contractor);
            // set the owning side to null (unless already changed)
            if ($contractor->getContractorType() === $this) {
                $contractor->setContractorType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getDescription();
    }
}
