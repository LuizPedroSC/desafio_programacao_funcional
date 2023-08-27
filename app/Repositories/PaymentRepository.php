<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;


class PaymentRepository
{

    public function store($dados)
    {
        return Payment::create($dados);
    }

}