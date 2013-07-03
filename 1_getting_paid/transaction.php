<?php

require_once "PATH_TO_BRAINTREE/lib/Braintree.php";

Braintree_Configuration::environment("sandbox");
Braintree_Configuration::merchantId("your_merchant_id");
Braintree_Configuration::publicKey("your_public_key");
Braintree_Configuration::privateKey("your_private_key");

$result = Braintree_Transaction::sale(array(
    "amount" => "1000.00",
    "creditCard" => array(
        "number" => $_POST["number"],
        "cvv" => $_POST["cvv"],
        "expirationMonth" => $_POST["month"],
        "expirationYear" => $_POST["year"]
    ),
    "options" => array(
        "submitForSettlement" => true
    )
));

if ($result->success) {
    echo("Success! Transaction ID: " . $result->transaction->id);
} else if ($result->transaction) {
    echo("Error: " . $result->message);
    echo("<br/>");
    echo("Code: " . $result->transaction->processorResponseCode);
} else {
    echo("Validation errors:<br/>");
    foreach (($result->errors->deepAll()) as $error) {
        echo("- " . $error->message . "<br/>");
    }
}
?>
