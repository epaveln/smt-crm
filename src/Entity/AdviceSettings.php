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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdviceSettingsRepository")
 */
class AdviceSettings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2500)
     */
    private $searchUrl;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $itemUrl;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\GreaterThanOrEqual(1, message = "Значение не должно быть меньше 1")
     * @Assert\LessThanOrEqual(10, message = "Значение не должно превышать  10")
     */
    private $pages;

    /**
     * @ORM\Column(type="text")
     */
    private $requestHeaders;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSearchUrl(): ?string
    {
        return $this->searchUrl;
    }

    public function setSearchUrl(string $searchUrl): self
    {
        $this->searchUrl = $searchUrl;

        return $this;
    }

    public function getItemUrl(): ?string
    {
        return $this->itemUrl;
    }

    public function setItemUrl(string $itemUrl): self
    {
        $this->itemUrl = $itemUrl;

        return $this;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getRequestHeaders(): ?string
    {
        return $this->requestHeaders;
    }

    public function setRequestHeaders(string $requestHeaders): self
    {
        $this->requestHeaders = $requestHeaders;

        return $this;
    }
}
