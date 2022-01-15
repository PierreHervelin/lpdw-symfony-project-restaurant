<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseOrderLine::class, mappedBy="purchaseOrder", cascade={"persist"})
     */
    private $purchaseOrderLines;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $restaurant;

    public function __construct()
    {
        $this->purchaseOrderLines = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|PurchaseOrderLine[]
     */
    public function getPurchaseOrderLines(): Collection
    {
        return $this->purchaseOrderLines;
    }

    public function addPurchaseOrderLine(PurchaseOrderLine $purchaseOrderLine): self
    {
        if (!$this->purchaseOrderLines->contains($purchaseOrderLine)) {
            $this->purchaseOrderLines[] = $purchaseOrderLine;
            $purchaseOrderLine->setProduct($this);
        }

        return $this;
    }

    public function removePurchaseOrderLine(PurchaseOrderLine $purchaseOrderLine): self
    {
        if ($this->purchaseOrderLines->removeElement($purchaseOrderLine)) {
            // set the owning side to null (unless already changed)
            if ($purchaseOrderLine->getProduct() === $this) {
                $purchaseOrderLine->setProduct(null);
            }
        }

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }
    public function __toString() {
        return $this->name;
    }
}
