<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('your_merchant_id');
Braintree_Configuration::publicKey('your_public_key');
Braintree_Configuration::privateKey('your_private_key');

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
    return new Response("<h2>Customer created with name: " . $result->customer->firstName . " " . $result->customer->lastName . "</h2>", 200);
  } else {
    return new Response("<h2>Error: " . $result->message . "</h2>", 200);
  }
});

$app->run();

?>
