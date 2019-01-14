<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * PostalCode
 *
 * @ORM\Table(name="postal_code", indexes={@ORM\Index(name="postal_code_code_idx", columns={"code"}), @ORM\Index(name="IDX_EA98E3765ED7B141", columns={"pricing_zone_id"})})
 * @ORM\Entity
 */
class PostalCode
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Groups({"elasticaPostalCode"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=false)
     * @JMS\Groups({"elasticaPostalCode"})
     */
    private $code;

    /**
     * @var PricingZone
     *
     * @ORM\ManyToOne(targetEntity="PricingZone")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pricing_zone_id", referencedColumnName="id")
     * })
     * @JMS\Groups({"elasticaPostalCode"})
     */
    private $pricingZone;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="InseeTown", mappedBy="postals")
     * @JMS\Groups({"elasticaPostalCode"})
     */
    private $insees;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->insees = new ArrayCollection();
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
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return PostalCode
     */
    public function setCode(string $code): PostalCode
    {
        $this->code = $code;
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
     * @return PostalCode
     */
    public function setPricingZone(PricingZone $pricingZone): PostalCode
    {
        $this->pricingZone = $pricingZone;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getInsees(): Collection
    {
        return $this->insees;
    }

    /**
     * @param Collection $insees
     * @return PostalCode
     */
    public function setInsees(Collection $insees): PostalCode
    {
        $this->insees = $insees;
        return $this;
    }

    public function addInsee(InseeTown $insee): self
    {
        if (!$this->insees->contains($insee)) {
            $this->insees[] = $insee;
            $insee->addPostal($this);
        }

        return $this;
    }

    public function removeInsee(InseeTown $insee): self
    {
        if ($this->insees->contains($insee)) {
            $this->insees->removeElement($insee);
            $insee->removePostal($this);
        }

        return $this;
    }
}
