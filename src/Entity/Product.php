<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['product:list']
            ]
        ],
        'post' => [
            'path' => '/productCreate',
            'denormalization_context' => [
                'groups' => ['product:create']
            ]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['product:item']
            ]
        ],
    ],
    order: [ 'name' => 'ASC'],
    paginationEnabled: true,
    paginationItemsPerPage: 1,
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]

class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['product:list', 'product:item'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['product:list', 'product:item', 'product:create'])]
    private $name;

    #[ORM\Column(type: 'string', length: 1000)]
    #[Groups(['product:list', 'product:item', 'product:create'])]
    private $description;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['product:list', 'product:item', 'product:create'])]
    private $price;

    #[ORM\Column(type: 'integer')]
    #[Groups(['product:list', 'product:item', 'product:create'])]
    private $vat;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Groups(['product:list', 'product:item'])]
    private $priceWithVat;

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getVat(): ?int
    {
        return $this->vat;
    }

    public function setVat(int $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getPriceWithVat(): ?string
    {
        return $this->priceWithVat;
    }

    public function setPriceWithVat(string $priceWithVat): self
    {
        $this->priceWithVat = $priceWithVat;

        return $this;
    }

    #[ORM\PrePersist]
    public function calcPriceWithVat()
    {
        if (!$this->priceWithVat) {
            $this->priceWithVat = $this->price + round($this->price * $this->vat / 100, 2);
        }
    }

}
