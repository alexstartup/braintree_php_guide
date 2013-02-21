<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('your_merchant_id');
Braintree_Configuration::publicKey('your_public_key');
Braintree_Configuration::privateKey('your_private_key');

global $app;
$app = new Silex\Application();

$app->get('/', function () {
    include 'views/form.php';
    return '';
});

$app->post('/create_customer', function (Request $request) {
  $result = Braintree_Customer::create(array(
    'firstName' => $request->get('first_name'),
    'lastName' => $request->get('last_name'),
    'creditCard' => array(
      'number' => $request->get('number'),
      'expirationMonth' => $request->get('month'),
      'expirationYear' => $request->get('year'),
      'cvv' => $request->get('cvv'),
      'billingAddress' => array(
        'postalCode' => $request->get('postal_code')
      )
    )
  ));

  if ($result->success) {
    $message = "Customer created with name: " . $result->customer->firstName . " " . $result->customer->lastName;
    return new Response("<h2>$message</h2> <a href=\"/subscriptions?id=" . $result->customer->id . "\".>Click here to sign this Customer up for a recurring payment</a>", 200);
  } else {
    return new Response("<h2>Error: " . $result->message . "</h2>", 200);
  }
});

$app->get('/subscriptions', function (Request $request) {
    try {
      $customer_id = $request->get("id");
      $customer = Braintree_Customer::find($customer_id);
      $payment_method_token = $customer->creditCards[0]->token;

      $result = Braintree_Subscription::create(array(
          'paymentMethodToken' => $payment_method_token,
          'planId' => 'test_plan_1'
      ));

      return new Response("<h1>Subscription Status</h1>" . $result->subscription->status, 201);
    } catch (Braintree_Exception_NotFound $e) {
      return new Response("<h1>No customer found for id: " . $request->get("id") . "</h1>");
    }
});

$app->run();

?>
