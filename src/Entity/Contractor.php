<?php
/*
 * This file is part of the "Smarttechno" company CRM system.
 *
 * (c) Pavel Evseenko <e.pavel@tut.by>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Entity;

use App\Custom\EmailPhoneValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContractorRepository")
 *
 * @UniqueEntity(
 *     fields={"name"},
 *     message="Пользователь с таким email уже существует!"
 * )
 */
class Contractor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups("main")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80, unique=true)
     *
     * @Assert\NotBlank()
     *
     * @Groups("main")
     */
    private $name;

    /**
     * @ORM\Column(type="json")
     */
    private $emails = [];

    /**
     * @ORM\Column(type="json")
     */
    private $phones = [];

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ContractorType", inversedBy="contractors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contractorType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="contractors")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Person", mappedBy="contractor")
     */
    private $persons;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tender", mappedBy="contractor")
     */
    private $tenders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="contractor")
     */
    private $offers;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
        $this->tenders = new ArrayCollection();
        $this->offers = new ArrayCollection();
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

    public function getEmails(): ?array
    {
        return $this->emails;
    }

    public function setEmails(array $emails): self
    {
        $this->emails = $emails;

        return $this;
    }

    public function getPhones(): ?array
    {
        return $this->phones;
    }

    public function setPhones(array $phones): self
    {
        $this->phones = $phones;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getContractorType(): ?ContractorType
    {
        return $this->contractorType;
    }

    public function setContractorType(?ContractorType $contractorType): self
    {
        $this->contractorType = $contractorType;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|persons[]
     */
    public function getpersons(): Collection
    {
        return $this->persons;
    }

    public function addpersons(person $person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons[] = $person;
            $person->setContractor($this);
        }

        return $this;
    }

    public function removepersons(person $person): self
    {
        if ($this->persons->contains($person)) {
            $this->persons->removeElement($person);
            // set the owning side to null (unless already changed)
            if ($person->getContractor() === $this) {
                $person->setContractor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tender[]
     */
    public function getTenders(): Collection
    {
        return $this->tenders;
    }

    public function addTender(Tender $tender): self
    {
        if (!$this->tenders->contains($tender)) {
            $this->tenders[] = $tender;
            $tender->setContractor($this);
        }

        return $this;
    }

    public function removeTender(Tender $tender): self
    {
        if ($this->tenders->contains($tender)) {
            $this->tenders->removeElement($tender);
            // set the owning side to null (unless already changed)
            if ($tender->getContractor() === $this) {
                $tender->setContractor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setContractorId($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getContractorId() === $this) {
                $offer->setContractorId(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context)
    {
        //removing toBe@Deleted elements from array
        $this->setEmails(EmailPhoneValidator::removeElementsFromArray($this->getEmails()));
        $this->setPhones(EmailPhoneValidator::removeElementsFromArray($this->getPhones()));
        //checking array values
        EmailPhoneValidator::validateEmails($context, $this->getEmails());
        EmailPhoneValidator::validatePhones($context, $this->getPhones());
    }
}
