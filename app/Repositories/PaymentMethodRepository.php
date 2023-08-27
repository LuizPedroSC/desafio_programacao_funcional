<?php

namespace App\Repositories;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;


class PaymentMethodRepository
{
    private $table = '';
    private $PaymentMethod;

    public function __construct(){
        $this->PaymentMethod = new PaymentMethod();
        $this->table = $this->PaymentMethod->getTable();
    }

    public function insertAll($dados)
    {
        return DB::table($this->table)->insert($dados);
    }

    public function searchAllPaymentFromPayPal($payment_id, $fields = ['*'])
    {
        return PaymentMethod::select($fields)
            ->where('payment_id', $payment_id)
            ->where('type', 'LIKE', '%paypal%')
            ->get();
    }

}