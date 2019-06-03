<?php

namespace App\Entity;

use App\Custom\EmailPhoneValidator;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 *
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Пользователь с таким email уже существует!"
 * )
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $email = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $phone = [];

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $skypeId;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $telegram;

    /**
     * @ORM\Column(type="string", length=45)
     *
     * @Assert\NotBlank()
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contractor", inversedBy="persons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contractor;

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

    public function getEmail(): ?array
    {
        return $this->email;
    }

    public function setEmail(?array $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?array
    {
        return $this->phone;
    }

    public function setPhone(?array $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSkypeId(): ?string
    {
        return $this->skypeId;
    }

    public function setSkypeId(?string $skypeId): self
    {
        $this->skypeId = $skypeId;

        return $this;
    }

    public function getTelegram(): ?string
    {
        return $this->telegram;
    }

    public function setTelegram(?string $telegram): self
    {
        $this->telegram = $telegram;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

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
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context)
    {
        //removing toBe@Deleted elements from array
        $this->setEmail(EmailPhoneValidator::removeElementsFromArray($this->getEmail()));
        $this->setPhone(EmailPhoneValidator::removeElementsFromArray($this->getPhone()));
        //checking array values
        EmailPhoneValidator::validateEmails($context, $this->getEmail(), 'email');
        EmailPhoneValidator::validatePhones($context, $this->getPhone(), 'phone');
    }
}
