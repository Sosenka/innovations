<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CurrencyRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Currency
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Maksymalna długość nazwy waluty to {{ limit }} znaków."
     * )
     *  @Assert\NotBlank(
     *      message = "Pole nazwa nie może być puste."
     * )
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=3)
     *
     *  @Assert\Length(
     *      max = 3,
     *      maxMessage = "Maksymalna długość kodu waluty to {{ limit }} znaków."
     * )
     *  @Assert\NotBlank(
     *      message = "Pole kod nie może być puste."
     * )
     */
    private $currencyCode;

    /**
     * @var float
     * @ORM\Column(type="float")
     *
     *  @Assert\NotBlank(
     *      message = "Pole kurs wymiany nie może być puste."
     * )
     */
    private $exchangeRate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId()
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

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(float $exchangeRate): self
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new DateTime());
    }

}
