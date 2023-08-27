<?php

namespace App\DTO;

class PaymentMethodDTO extends DTO
{
    public $id;
    public $codigo;
    public float $value;
    public string $type;

    public function __construct($request = [], $id = null)
    {
        $this->id = $id;
        $this->codigo = $this->pegarValorPorChave($request, 'id');
        $this->value = $this->pegarValorPorChave($request, 'value');
        $this->type = $this->pegarValorPorChave($request, 'type');
    }
}