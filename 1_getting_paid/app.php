<?php

require_once __DIR__ . '/vendor/autoload.php';

Braintree_Configuration::environment('sandbox');
Braintree_Configuration::merchantId('your_merchant_id');
Braintree_Configuration::publicKey('your_public_key');
Braintree_Configuration::privateKey('your_private_key');

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/', function () {
    include 'views/form.php';
    return '';
});

$app->get("/braintree", function () {
    include 'views/response.php';
    return '';
});

$app->run();

?>
