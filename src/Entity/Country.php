<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 *
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Страна с таким именем уже существует!"
 * )
 */
class Country
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, unique=true)
     * @Assert\Regex("/^[А-Яа-я]+/")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contractor", mappedBy="country")
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $contractor->setCountry($this);
        }

        return $this;
    }

    public function removeContractor(Contractor $contractor): self
    {
        if ($this->contractors->contains($contractor)) {
            $this->contractors->removeElement($contractor);
            // set the owning side to null (unless already changed)
            if ($contractor->getCountry() === $this) {
                $contractor->setCountry(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
