<?php

namespace App\DTO;

class PaymentDTO extends DTO
{
    public $id;
    public string $name;
    public string $email;
    public float $amount;

    public function __construct($request = [], $id = null)
    {
        $this->id = $id;
        $this->name = $this->pegarValorPorChave($request, 'nome', '');
        $this->email = $this->pegarValorPorChave($request, 'email', '');
        $this->amount = $this->pegarValorPorChave($request, 'valorTotal', 0);
    }


}