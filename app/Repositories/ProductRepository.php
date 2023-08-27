<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\DB;


class ProductRepository
{
    private $table = '';
    private $Product;

    public function __construct(){
        $this->Product = new Product();
        $this->table = $this->Product->getTable();
    }

    public function insertAll($dados)
    {
        return DB::table($this->table)->insert($dados);
    }

}