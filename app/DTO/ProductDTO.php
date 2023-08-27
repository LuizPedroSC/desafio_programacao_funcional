<?php

namespace App\DTO;

class ProductDTO extends DTO
{
    public $id;
    public string $name;
    public int $quantity;
    public float $unitValue;
    public string $category;

    public function __construct($request = [], $id = null)
    {
        $this->id = $id;
        $this->name = $this->pegarValorPorChave($request, 'name');
        $this->quantity = $this->pegarValorPorChave($request, 'quantity');
        $this->unitValue = $this->pegarValorPorChave($request, 'unitValue');
        $this->category = $this->pegarValorPorChave($request, 'category');
    }
}