<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitRequest;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal;

class PaypalController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function __construct(public readonly PayPal $paypal)
    {
        $this->paypal->getAccessToken();
    }

    public function checkout()
    {
        return view('checkout');
    }

    /**
     * @throws \Throwable
     */
    public function sendToPaypal(SubmitRequest $request,)
    {
        $response = $this->paypal->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('success'),
                "cancel_url" => route('cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => number_format($request->input('amount'), 2)
                    ],
                ],
            ]
        ]);
        if (!isset($response['id'])) {
            return redirect()->route('cancel');
        }
        $link = $this->findInLinks($response['links'], 'approve');
        if (is_null($link)) {
            return redirect()->route('cancel');
        }
        return redirect()->to($link['href']);
    }

    /**
     * @throws \Throwable
     */
    public function success(Request $request)
    {
        $payment = $this->paypal->capturePaymentOrder($request->token);
        if (isset($payment['error'])) {
            return redirect()->route('cancel');
        }
        return view('success', compact('payment'));
    }

    public function cancel(Request $request)
    {
        return view('cancel');
    }

    private function findInLinks(array $links, string $rel)
    {
        foreach ($links as $link) {
            if ($rel == $link['rel']) {
                return $link;
            }
        }
        return null;
    }
}
