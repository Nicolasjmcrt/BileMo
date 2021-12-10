<?php

namespace App\Entity;

use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Hateoas\Relation(
 *      name = "self",
 *      href = @Hateoas\Route(
 *          "show_product",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *      attributes = {"actions": { "read": "GET" }},
 *      exclusion = @Hateoas\Exclusion(groups={"SHOW_PRODUCT", "LIST_PRODUCT"})
 * )
 * @Hateoas\Relation(
 *      name = "all",
 *      href = @Hateoas\Route(
 *          "products",
 *          absolute = true
 *      ),
 *      attributes = {"actions": { "read": "GET" }},
 *      exclusion = @Hateoas\Exclusion(groups={"SHOW_PRODUCT"})
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"SHOW_PRODUCT", "LIST_PRODUCT"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"SHOW_PRODUCT", "LIST_PRODUCT"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"SHOW_PRODUCT"})
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", scale=2, precision=11)
     * @Serializer\Groups({"SHOW_PRODUCT"})
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"SHOW_PRODUCT"})
     */
    private $quantity;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Serializer\Groups({"SHOW_PRODUCT"})
     */
    private $vat;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"SHOW_PRODUCT"})
     */
    private $reference;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getVat(): ?string
    {
        return $this->vat;
    }

    public function setVat(string $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }
}
