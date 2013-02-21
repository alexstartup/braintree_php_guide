<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('use_your_merchant_id');
Braintree_Configuration::publicKey('use_your_public_key');
Braintree_Configuration::privateKey('use_your_private_key');

$app = new Silex\Application();

$app->get('/', function () {
    include 'views/form.php';
    return '';
});

$app->post('/create_transaction', function (Request $request) {
  $result = Braintree_Transaction::sale(array(
    'amount' => '1000.00',
    'creditCard' => array(
      'number' => $request->get('number'),
      'cvv' => $request->get('cvv'),
      'expirationMonth' => $request->get('month'),
      'expirationYear' => $request->get('year')
    ),
    'options' => array(
      'submitForSettlement' => true
    )
  ));

  if ($result->success) {
    return new Response("<h1>Success! Transaction ID: " . $result->transaction->id . "</h1>", 200);
  } else {
    return new Response("<h1>Error: " . $result->message . "</h1>", 200);
  }
});

$app->run();

?>
