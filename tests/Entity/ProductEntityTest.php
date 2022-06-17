<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductEntityTest extends TestCase
{
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Product();
    }

    public function testEntity(): void
    {
        $this->assertNull($this->object->getId());

        $this->object->setName("Lexus");
        $this->assertEquals("Lexus", $this->object->getName());

        $this->object->setDescription("Lexus is a brand of Toyota");
        $this->assertEquals("Lexus is a brand of Toyota", $this->object->getDescription());

        $this->object->setPrice(60000);
        $this->assertEquals(60000, $this->object->getPrice());

        $this->object->setVat(15);
        $this->assertEquals(15, $this->object->getVat());

        $this->object->calcPriceWithVat();
        $this->assertEquals(69000, $this->object->getPriceWithVat());
    }
}
