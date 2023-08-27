<?php

namespace App\DTO;

abstract class DTO
{
    public function toArray(array $atributos = []): array
    {
        if(count($atributos)){
            return array_map(function ($atributo) {
                if (property_exists($this, $atributo)) {
                    return $this->$atributo;
                }
            }, $atributos);
        }
        return get_object_vars($this);
    }

    protected function pegarValorPorChave(array $array, string $key, $valor_padrao = null)
    {
        return array_key_exists($key, $array) ? $array[$key] : $valor_padrao;
    }
}