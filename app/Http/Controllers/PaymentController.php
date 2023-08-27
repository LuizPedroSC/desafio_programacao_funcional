<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DTO\PaymentDTO;
use App\DTO\ProductDTO;
use App\DTO\PaymentMethodDTO;
use App\Http\Requests\PaymentRequest;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    private $PaymentDTO;

    private $ProductsDTOs = [];

    private $PaymentMethodDTOs = [];

    private $PayPalPaymentDTOs = [];

    private $PaymentService;

    public function __construct()
    {
        $this->PaymentService = new PaymentService();
    }


    public function store(PaymentRequest $request)
    {
        $dados = $request->all();

        $this->setPaymentDTO($dados);

        $this->setProductsDTOs($dados['products']);

        $this->setPaymentMethodsDTOs($dados['paymentMethods']);

        $reponse = $this->PaymentService->store($this->PaymentDTO, $this->ProductsDTOs, $this->PaymentMethodsDTOs);

        Log::channel('payment')->info(['success:'.$reponse['success'], $dados]);
        
        return response()->json($reponse);
    }

    private function setPaymentDTO($dados){
        $this->PaymentDTO = new PaymentDTO($dados);
    }

    private function setProductsDTOs($products){
        $this->ProductsDTOs = array_map(function($product) {
            return new ProductDTO($product);
        }, $products);
    }

    private function setPaymentMethodsDTOs($paymentMethods){
        $this->PaymentMethodsDTOs = array_map(function($paymentMethod) {
            return new PaymentMethodDTO($paymentMethod);
        }, $paymentMethods);
    }
    
}
