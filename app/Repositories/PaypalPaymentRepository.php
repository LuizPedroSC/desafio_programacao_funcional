<?php

namespace App\Repositories;

use App\Models\PaypalPayment;
use Illuminate\Support\Facades\DB;


class PaypalPaymentRepository
{
    private $table = '';
    private $PaypalPayment;

    public function __construct(){
        $this->PaypalPayment = new PaypalPayment();
        $this->table = $this->PaypalPayment->getTable();
    }

    public function insertAll($dados)
    {
        return DB::table($this->table)->insert($dados);
    }

}