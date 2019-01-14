<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * ReferencePrice
 *
 * @ORM\Table(name="reference_price", indexes={@ORM\Index(name="IDX_1C6942C95ED7B141", columns={"pricing_zone_id"})})
 * @ORM\Entity
 */
class ReferencePrice
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Groups({"elasticaReferencePrice", "elasticaPricingZone", "elasticaPostalCode"})
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     * @JMS\Groups({"elasticaReferencePrice", "elasticaPricingZone", "elasticaPostalCode"})
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     * @JMS\Groups({"elasticaReferencePrice", "elasticaPricingZone", "elasticaPostalCode"})
     */
    private $date;

    /**
     * @var PricingZone
     *
     * @ORM\ManyToOne(targetEntity="PricingZone", inversedBy="referencePrices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pricing_zone_id", referencedColumnName="id")
     * })
     * @JMS\Groups("elasticaReferencePrice")
     */
    private $pricingZone;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return ReferencePrice
     */
    public function setPrice(float $price): ReferencePrice
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return ReferencePrice
     */
    public function setDate(\DateTime $date): ReferencePrice
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return PricingZone
     */
    public function getPricingZone(): PricingZone
    {
        return $this->pricingZone;
    }

    /**
     * @param PricingZone $pricingZone
     * @return ReferencePrice
     */
    public function setPricingZone(PricingZone $pricingZone): ReferencePrice
    {
        $this->pricingZone = $pricingZone;
        return $this;
    }

}
