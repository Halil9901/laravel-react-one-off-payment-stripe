<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        if ($event->payload['type'] === 'checkout.session.completed') {
            $this->handleCheckoutSessionCompleted($event);
        }

        if ($event->payload['type'] === 'charge.refunded') {
           // \Log::info($event->payload['data']);
        }
    }

    private function handleCheckoutSessionCompleted($event): void
    {
        $stripeCustomerId = $event->payload['data']['object']['customer'];
        if (empty($stripeCustomerId)) {
            \Log::error('Stripe customer ID is empty');
            return;
        }

        $user = Cashier::findBillable($stripeCustomerId);
        if (empty($user)) {
            \Log::error('User not found');
            return;
        }

        $payment = $user->payment()->create([
            'payment_id' => $event->payload['data']['object']['id'],
            'amount' => $event->payload['data']['object']['amount_total'],
            'payment_status' => $event->payload['data']['object']['payment_status'],
            'payment_intent' => $event->payload['data']['object']['payment_intent'],
            'data' => $event->payload,
        ]);
    }
}
