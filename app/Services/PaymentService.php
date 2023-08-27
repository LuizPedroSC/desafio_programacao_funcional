<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Repositories\PaymentRepository;
use App\Repositories\ProductRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\PaypalPaymentRepository;

class PaymentService
{   

    protected $PaymentRepository;
    protected $ProductRepository;
    protected $PaymentMethodRepository;
    protected $PaypalPaymentRepository;

    public function __construct()
    {
        $this->PaymentRepository = new PaymentRepository();
        $this->ProductRepository = new ProductRepository();
        $this->PaymentMethodRepository = new PaymentMethodRepository();
        $this->PaypalPaymentRepository = new PaypalPaymentRepository();
    }

    public function store($PaymentDTO, $ProductsDTOs, $PaymentMethodsDTOs, $callback = null){
        
        DB::beginTransaction();
        $PaymentDTO->amount = $this->calculateAmount($ProductsDTOs);
        
        $payment = $this->PaymentRepository->store($PaymentDTO->toArray());

        if(!$payment){
            DB::rollBack();
            return ['success' => false, 'message' => 'falha ao registrar pagamento'];
        }

        $ProductsFromInsertAll = $this->setIdPaymentAllElements($payment->id, $ProductsDTOs);

        $insertProducts = $this->ProductRepository->insertAll($ProductsFromInsertAll);
        
        if(!$insertProducts){
            DB::rollBack();
            return ['success' => false, 'message' => 'falha ao registrar os produtos'];
        }

        $PaymentMethodsFromInsertAll = $this->setIdPaymentAllElements($payment->id, $PaymentMethodsDTOs);

        $insertPaymentMethods = $this->PaymentMethodRepository->insertAll($PaymentMethodsFromInsertAll);
        
        if(!$insertPaymentMethods){
            DB::rollBack();
            return ['success' => false, 'message' => 'falha ao registrar os metodos de pagamento'];
        }
        
        $paypalPayments = $this->PaymentMethodRepository->searchAllPaymentFromPayPal($payment->id, ['id', 'type', 'payment_id']);

        if($paypalPayments){
            $arrayPaypalPayments = $this->makeArrayPaypalPayments($paypalPayments->toArray());
            $insertPaypalPayments = $this->PaypalPaymentRepository->insertAll($arrayPaypalPayments);
            if(!$insertPaymentMethods){
                DB::rollBack();
                return ['success' => false, 'message' => 'falha ao registrar os pagamentos por paypal'];
            }
        }

        if ($callback !== null && is_callable($callback)) {
            $callback();
        }

        DB::commit();
        return ['success' => true, 'message' => 'Processo concluido'];
    }

    private function calculateAmount($ProductsDTOs){
        return array_reduce($ProductsDTOs, function($amount, $product) {
            return $amount + ($product->quantity * $product->unitValue);
        }, 0);
    }

    private function setIdPaymentAllElements($payment_id, $array){
        return array_map(function ($item) use ($payment_id) {
            $item->payment_id = $payment_id;
            return $item->toArray();
        }, $array);
    }

    private function makeArrayPaypalPayments($paypalPayments)
    {
        return array_map(function ($item) {
            return [
                'payment_method_id' => $item['id'],
                'status' => 'paypal pagou'
            ];
        }, $paypalPayments);
    }
    
}
