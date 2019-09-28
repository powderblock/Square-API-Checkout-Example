<?php
// Include the Square Connect API resources
require_once('autoload.php');

//Replace your access token and location ID
$accessToken = 'REPLACE_ME';
$locationId = 'REPLACE_ME';

// Create and configure a new API client object
$defaultApiConfig = new \SquareConnect\Configuration();
$defaultApiConfig->setAccessToken($accessToken);
$defaultApiClient = new \SquareConnect\ApiClient($defaultApiConfig);
$checkoutClient = new SquareConnect\Api\CheckoutApi($defaultApiClient);

//Set quantity
$quantity = 1;

//Create a Money object to represent the price of the line item.
$price = new \SquareConnect\Model\Money;

//Set the base price PER ITEM (in cents)
$price->setAmount(100); //100 means $1.00
$price->setCurrency('USD');

//Create the line item and set details
$book = new \SquareConnect\Model\CreateOrderRequestLineItem;
$book->setName('ITEM NAME');
$book->setQuantity((string)$quantity);
$book->setBasePriceMoney($price);

//Puts our line item object in an array called lineItems.
$lineItems = array();
array_push($lineItems, $book);

// Create an Order object using line items from above
$order = new \SquareConnect\Model\CreateOrderRequest();

$order->setIdempotencyKey(uniqid()); //uniqid() generates a random string.

//sets the lineItems array in the order object
$order->setLineItems($lineItems);

///Create Checkout request object.
$checkout = new \SquareConnect\Model\CreateCheckoutRequest();

$checkout->setAskForShippingAddress(True);
$checkout->setIdempotencyKey(uniqid()); //uniqid() generates a random string.
$checkout->setOrder($order); //this is the order we created in the previous step.
//Replace with the URL where you want to redirect your customers after transaction.
$checkout->setRedirectUrl("https://example.com/thank-you.html");

try {
    $result = $checkoutClient->createCheckout(
      $locationId,
      $checkout
    );
    //Save the checkout ID for verifying transactions
    $checkoutId = $result->getCheckout()->getId();
    //Get the checkout URL that opens the checkout page.
    $checkoutUrl = $result->getCheckout()->getCheckoutPageUrl();
    header('Location: '.$checkoutUrl);
} catch (Exception $e) {
    echo 'Exception when calling CheckoutApi->createCheckout: ', $e->getMessage(), PHP_EOL;
}
?>
