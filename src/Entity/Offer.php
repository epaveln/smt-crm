<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    private $attachOfferDir;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $number;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=400)
     */
    private $title;

    /**
     * @ORM\Column(type="json")
     */
    private $attach = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OfferType", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offerType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contractor", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contractor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAttach(): ?array
    {
        return $this->attach;
    }

    public function setAttach(array $attach): self
    {
        $this->attach = $attach;

        return $this;
    }

    public function getOfferType(): ?OfferType
    {
        return $this->offerType;
    }

    public function setOfferType(?OfferType $offerType): self
    {
        $this->offerType = $offerType;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getContractor(): ?Contractor
    {
        return $this->contractor;
    }

    public function setContractor(?Contractor $contractor): self
    {
        $this->contractor = $contractor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttachOfferDir()
    {
        return $this->attachOfferDir;
    }

    /**
     * @param mixed $attachOfferDir
     */
    public function setAttachOfferDir($attachOfferDir): void
    {
        $this->attachOfferDir = $attachOfferDir;
    }



    /**
     * @Assert\Callback()
     */
    public function validate()
    {
        $this->setAttach(array_filter($this->getAttach(), function ($value) {return $value !== 'toBe@Deleted';}));
        if (is_null($this->getAttach())) {
            $this->setAttach([]);
        }
    }
}
