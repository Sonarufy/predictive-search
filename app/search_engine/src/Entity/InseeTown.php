<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;


/**
 * InseeTown
 *
 * @ORM\Table(name="insee_town", indexes={@ORM\Index(name="is_accessible_idx", columns={"enabled"}), @ORM\Index(name="code_idx", columns={"code"})})
 * @ORM\Entity
 */
class InseeTown
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @JMS\Groups({"elasticaInseeTown", "elasticaPostalCode"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5, nullable=false)
     * @JMS\Groups({"elasticaInseeTown", "elasticaPostalCode"})
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="town", type="string", length=255, nullable=false)
     * @JMS\Groups({"elasticaInseeTown", "elasticaPostalCode"})
     */
    private $town;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @JMS\Groups({"elasticaInseeTown", "elasticaPostalCode"})
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="postal_code_ref", type="string", length=10, nullable=true)
     * @JMS\Groups({"elasticaInseeTown", "elasticaPostalCode"})
     */
    private $postalCodeRef;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     * @JMS\Groups({"elasticaInseeTown", "elasticaPostalCode"})
     */
    private $enabled;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="PostalCode", inversedBy="insee")
     * @ORM\JoinTable(name="insees_postals",
     *   joinColumns={
     *     @ORM\JoinColumn(name="insee_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="postal_id", referencedColumnName="id")
     *   }
     * )
     * @JMS\Groups({"elasticaInseeTown"})
     */
    private $postals;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postals = new ArrayCollection();
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
     * @return InseeTown
     */
    public function setCode(string $code): InseeTown
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getTown(): string
    {
        return $this->town;
    }

    /**
     * @param string $town
     * @return InseeTown
     */
    public function setTown(string $town): InseeTown
    {
        $this->town = $town;
        return $this;
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
     * @return InseeTown
     */
    public function setName(string $name): InseeTown
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostalCodeRef(): ?string
    {
        return $this->postalCodeRef;
    }

    /**
     * @param string|null $postalCodeRef
     * @return InseeTown
     */
    public function setPostalCodeRef(?string $postalCodeRef): InseeTown
    {
        $this->postalCodeRef = $postalCodeRef;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return InseeTown
     */
    public function setEnabled(bool $enabled): InseeTown
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPostals(): Collection
    {
        return $this->postals;
    }

    /**
     * @param Collection $postals
     * @return InseeTown
     */
    public function setPostals(Collection $postals): InseeTown
    {
        $this->postals = $postals;
        return $this;
    }

    public function addPostal(PostalCode $postal): self
    {
        if (!$this->postals->contains($postal)) {
            $this->postals[] = $postal;
        }

        return $this;
    }

    public function removePostal(PostalCode $postal): self
    {
        if ($this->postals->contains($postal)) {
            $this->postals->removeElement($postal);
        }

        return $this;
    }

    public function getLabel(): string
    {
    	return $this->name;
    }
}
