<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * PricingZone
 *
 * @ORM\Table(name="pricing_zone")
 * @ORM\Entity
 */
class PricingZone
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Groups({"elasticaPricingZone", "elasticaReferencePrice", "elasticaPostalCode"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @JMS\Groups({"elasticaPricingZone", "elasticaReferencePrice", "elasticaPostalCode"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=false)
     * @JMS\Groups("elasticaPricingZone")
     */
    private $reference;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     * @JMS\Groups("elasticaPricingZone")
     */
    private $status;

    /**
     * @var float
     *
     * @ORM\Column(name="minimal_margin", type="float", precision=10, scale=0, nullable=false)
     * @JMS\Groups("elasticaPricingZone")
     * @JMS\SerializedName("minimal_margin")
     */
    private $minimalMargin;

    /**
     * @var float
     *
     * @ORM\Column(name="fqs_margin", type="float", precision=10, scale=0, nullable=false)
     * @JMS\Groups("elasticaPricingZone")
     * @JMS\SerializedName("fqs_margin")
     */
    private $fqsMargin;

    /**
     * @var float|null
     *
     * @ORM\Column(name="fod_purchase_price_average", type="float", precision=10, scale=0, nullable=true)
     * @JMS\Groups("elasticaPricingZone")
     * @JMS\SerializedName("fod_purchase_price_average")
     */
    private $fodPurchasePriceAverage;

    /**
     * @var float|null
     *
     * @ORM\Column(name="fqs_purchase_price_average", type="float", precision=10, scale=0, nullable=true)
     * @JMS\Groups("elasticaPricingZone")
     * @JMS\SerializedName("fqs_purchase_price_average")
     */
    private $fqsPurchasePriceAverage;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="App\Entity\ReferencePrice", mappedBy="pricingZone")
	 * @JMS\Groups({"elasticaPricingZone", "elasticaPostalCode"})
	 */
	private $referencePrices;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->referencePrices = new ArrayCollection();
	}

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return PricingZone
     */
    public function setName(string $name): PricingZone
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     * @return PricingZone
     */
    public function setReference(string $reference): PricingZone
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStatus(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return PricingZone
     */
    public function setStatus(bool $status): PricingZone
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return float
     */
    public function getMinimalMargin(): float
    {
        return $this->minimalMargin;
    }

    /**
     * @param float $minimalMargin
     * @return PricingZone
     */
    public function setMinimalMargin(float $minimalMargin): PricingZone
    {
        $this->minimalMargin = $minimalMargin;
        return $this;
    }

    /**
     * @return float
     */
    public function getFqsMargin(): float
    {
        return $this->fqsMargin;
    }

    /**
     * @param float $fqsMargin
     * @return PricingZone
     */
    public function setFqsMargin(float $fqsMargin): PricingZone
    {
        $this->fqsMargin = $fqsMargin;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getFodPurchasePriceAverage(): ?float
    {
        return $this->fodPurchasePriceAverage;
    }

    /**
     * @param float|null $fodPurchasePriceAverage
     * @return PricingZone
     */
    public function setFodPurchasePriceAverage(?float $fodPurchasePriceAverage): PricingZone
    {
        $this->fodPurchasePriceAverage = $fodPurchasePriceAverage;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getFqsPurchasePriceAverage(): ?float
    {
        return $this->fqsPurchasePriceAverage;
    }

    /**
     * @param float|null $fqsPurchasePriceAverage
     * @return PricingZone
     */
    public function setFqsPurchasePriceAverage(?float $fqsPurchasePriceAverage): PricingZone
    {
        $this->fqsPurchasePriceAverage = $fqsPurchasePriceAverage;
        return $this;
    }

	/**
	 * @return Collection
	 */
	public function getReferencePrices(): Collection
	{
		return $this->referencePrices;
	}

	/**
	 * @param Collection $referencePrices
	 * @return PricingZone
	 */
	public function setReferencePrices(Collection $referencePrices): PricingZone
	{
		$this->referencePrices = $referencePrices;
		return $this;
	}
}
