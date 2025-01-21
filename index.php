<?php
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

if (empty($_SERVER['HTTP_STRIPE_SIGNATURE'])) {
    exit('Please read the README.md');
}

require 'vendor/autoload.php';

$stripe = new StripeClient('sk_test_...');
$env = file_exists('.env') ? parse_ini_file('.env') : [];

if (empty($env['ENDPOINT_SECRET'])) {
    exit('Error: create and populate the .env file using the .env.example as a reference');
}

$endpoint_secret = $env['ENDPOINT_SECRET'];
$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );

} catch(UnexpectedValueException|SignatureVerificationException $e) {
    http_response_code(400);
    exit();
}

switch ($event->type) {
    case 'payment_intent.succeeded':
        $paymentIntent = $event->data->object;
        break;

    default:
        echo 'Received unknown event type ' . $event->type;
}
