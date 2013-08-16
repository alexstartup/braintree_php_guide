<?php

require_once "PATH_TO_BRAINTREE/lib/Braintree.php";

Braintree_Configuration::environment("sandbox");
Braintree_Configuration::merchantId("your_merchant_id");
Braintree_Configuration::publicKey("your_public_key");
Braintree_Configuration::privateKey("your_private_key");

if(isset($_GET["bt_challenge"])) {
    echo(Braintree_WebhookNotification::verify($_GET["bt_challenge"]));
}

if(
    isset($_POST["bt_signature"]) &&
    isset($_POST["bt_payload"])
) {
    $webhookNotification = Braintree_WebhookNotification::parse(
        $_POST["bt_signature"], $_POST["bt_payload"]
    );

    $message =
        "[Webhook Received " . $webhookNotification->timestamp->format('Y-m-d H:i:s') . "] "
        . "Kind: " . $webhookNotification->kind . " | "
        . "Subscription: " . $webhookNotification->subscription->id . "\n";

    file_put_contents("/tmp/webhook.log", $message, FILE_APPEND);
}

?>
