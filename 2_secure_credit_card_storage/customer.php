<?php

require_once "PATH_TO_BRAINTREE/lib/Braintree.php";

Braintree_Configuration::environment("sandbox");
Braintree_Configuration::merchantId("your_merchant_id");
Braintree_Configuration::publicKey("your_public_key");
Braintree_Configuration::privateKey("your_private_key");

$result = Braintree_Customer::create(array(
    "firstName" => $_POST["first_name"],
    "lastName" => $_POST["last_name"],
    "creditCard" => array(
        "number" => $_POST["number"],
        "expirationMonth" => $_POST["month"],
        "expirationYear" => $_POST["year"],
        "cvv" => $_POST["cvv"],
        "billingAddress" => array(
            "postalCode" => $_POST["postal_code"]
        )
    )
));

if ($result->success) {
    echo("Success! Customer ID: " . $result->customer->id);
} else {
    echo("Validation errors:<br/>");
    foreach (($result->errors->deepAll()) as $error) {
        echo("- " . $error->message . "<br/>");
    }
}
?>
