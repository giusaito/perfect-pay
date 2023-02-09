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
    public function index(Request $request){
        //return response()->json($request->all());
        // return response()->json($request->payer['email']);
        // {"token":"358d21c5c4a2210e1ea076c7dca33c3d","issuerId":"12524","paymentMethodId":"master","transactionAmount":45,"installments":1,"description":"Tobias Sammet - Avantasia - The mistery of time","payer":{"email":null,"identification":{"type":"CPF","number":"02493164989"}}}
        
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
            "type" => $request->payer['identification']['type'],
            "number" => $request->payer['identification']['number']
        );
        $payment->payer = $payer;
    
        $payment->save();

        $this->validate_payment_result($payment);
    
        $response_fields = array(
            'id' => $payment->id,
            'status' => $payment->status,
            'detail' => $payment->status_detail
        );
    
        $response_body = json_encode($response_fields);
        return Response::json($response_body);
        // $response->getBody()->write($response_body);

        // return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
    private function validate_payment_result($payment) {
        if($payment->id === null) {
            $error_message = 'Unknown error cause';
    
            if($payment->error !== null) {
                $sdk_error_message = $payment->error->message;
                $error_message = $sdk_error_message !== null ? $sdk_error_message : $error_message;
            }
    
            throw new Exception($error_message);
        }   
    }
}
