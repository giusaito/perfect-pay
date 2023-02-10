<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Exception;
use MercadoPago;

class PaymentController extends Controller
{
    public function __construct(){
        \MercadoPago\SDK::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN'));
    }
    public function cartao(Request $request){
        $payment                        = new MercadoPago\Payment();
        $payment->transaction_amount    = (float)$request->transactionAmount;
        $payment->token                 = $request->token;
        $payment->description           = $request->description;
        $payment->installments          = $request->installments;
        $payment->payment_method_id     = $request->paymentMethodId;
        $payment->issuer_id             = $request->issuerId;

        $payer = new MercadoPago\Payer();
        $payer->email = $request->payer['email'];
        $payer->identification = array(
            "type"      => $request->payer['identification']['type'],
            "number"    => $request->payer['identification']['number']
        );
        $payment->payer = $payer;
    
        $payment->save();

        $this->validate_payment_result($payment);
    
        $response_fields = array(
            'id'     => $payment->id,
            'status' => $payment->status,
            'detail' => $payment->status_detail
        );
    
        $response_body = json_encode($response_fields);
        return Response::json($response_body);
    }
    public function boleto(Request $request){
        // $payment                        = new MercadoPago\Payment();
        // $payment->date_of_expiration    = "2023-02-11T21:00:00.000-04:00";
        // $payment->transaction_amount    = (float)$request->transactionAmount;
        // $payment->description           = $request->description;
        // $payment->payment_method_id     = "bolbradesco";
        // $payer                          = new MercadoPago\Payer();
        // $payer->email                   = $request->email;
        // $payer->first_name              = $request->first_name;
        // $payer->last_name               = $request->last_name;
        // $payer->identification          = array(
        //     "type"          => $request->payer['identification']['type'],
        //     "number"        => $request->payer['identification']['number']
        // );
        // $payer->address                 = array(
        //     "zip_code"      => $request->payer['address']['zip_code'],
        //     "street_name"   => $request->payer['address']['street_name'],
        //     "street_number" => $request->payer['address']['street_number'],
        //     "neighborhood"  => $request->payer['address']['neighborhood'],
        //     "city"          => $request->payer['address']['city'],
        //     "federal_unit"  => $request->payer['address']['federal_unit'],
        // );
        // $payment->payer = $payer;
        // $payment->save();

        // return Response::json(json_encode($payment));

        $payment = new MercadoPago\Payment();
        $payment->transaction_amount = (float)$request->amountTicket;
        $payment->description = $request->descriptionTicket;
        $payment->payment_method_id = "bolbradesco";
        $payment->payer = array(
            "email" => $request->email,
            "first_name" => $request->payerFirstName,
            "last_name" => $request->payerLastName,
            "identification" => array(
                "type" => $request->identificationType,
                "number" => $request->identificationNumber
            ),
            "address"=>  array(
                "zip_code" => $request->payerZipCode,
                "street_name" => $request->payerStreetName,
                "street_number" => $request->payerStreetNumber,
                "neighborhood" => $request->payerNeightborhood,
                "city" => $request->payerCity,
                "federal_unit" => $request->payerFederalUnit
            )
        );

        $payment->save();
        // return Response::json($payment->transaction_details->external_resource_url);
        return redirect()->back()->with('success', 'Seu boleto foi gerado com sucesso! Acesse clicando nesse link <a href="'.$payment->transaction_details->external_resource_url.'" target="_blank" class="btn btn-info">Acesse seu boleto</a>');

        
        // $payment = new MercadoPago\Payment();
        // $payment->transaction_amount = 100;
        // $payment->description = "Título do produto";
        // $payment->payment_method_id = "bolbradesco";
        // $payment->payer = array(
        //     "email" => "test@test.com",
        //     "first_name" => "Test",
        //     "last_name" => "User",
        //     "identification" => array(
        //         "type" => "CPF",
        //         "number" => "19119119100"
        //     ),
        //     "address"=>  array(
        //         "zip_code" => "06233200",
        //         "street_name" => "Av. das Nações Unidas",
        //         "street_number" => "3003",
        //         "neighborhood" => "Bonfim",
        //         "city" => "Osasco",
        //         "federal_unit" => "SP"
        //     )
        // );

        // $payment->save();
        // echo '<pre>',print_r($payment),'</pre>';
    }

    private function validate_payment_result($payment) {
        if($payment->id === null) {
            $error_message = 'Causa do erro desconhecida';
    
            if($payment->error !== null) {
                $sdk_error_message = $payment->error->message;
                $error_message = $sdk_error_message !== null ? $sdk_error_message : $error_message;
            }
    
            throw new Exception($error_message);
        }   
    }
}
