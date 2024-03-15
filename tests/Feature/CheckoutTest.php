<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_checkout_page_loaded(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_checkout_page_contains_pay_btn(): void
    {
        $response = $this->get('/');
        $response->assertViewIs('checkout');
    }

    public function test_redirect_to_paypal_page()
    {
        $response = $this->post('/send-paypal',[
            'amount' => 20,
        ]);
        $response->assertStatus(302)->assertRedirectContains('paypal');
    }
}
