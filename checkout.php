<?php
// Include the Square Connect API
//autoload.php contains the square API
require_once('autoload.php');

//REPLACE your access token and location ID!
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
//Example: if quantity = 10 and setAmount(100), the actual price in checkout will be $10
//This is because setAmount() is a PER ITEM cost.
$price->setAmount(1000); //100 means $1.00
//Set US currency code
$price->setCurrency('USD');

//Create the line item and set details
$book = new \SquareConnect\Model\CreateOrderRequestLineItem;
//Set the item name that will appear on the checkout page
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

//This setting is a bool
//True makes the shipping info appear on the checkout page
$checkout->setAskForShippingAddress(True);
$checkout->setIdempotencyKey(uniqid()); //uniqid() generates a random string.
$checkout->setOrder($order); //this is the order we created in the previous step.
//Replace with the URL where you want to redirect your customers after transaction.
$checkout->setRedirectUrl("https://example.com/thank-you.html");

//Try catch because in rare cases, it can fail!
try {
    $result = $checkoutClient->createCheckout(
      $locationId,
      $checkout
    );
    //Save the checkout ID for verifying transactions
    $checkoutId = $result->getCheckout()->getId();
    //Generate the checkout URL for this transaction
    $checkoutUrl = $result->getCheckout()->getCheckoutPageUrl();
    //Automacially REDIRECT the user to the checkout page on squareup.com
    header('Location: '.$checkoutUrl);
} catch (Exception $e) {
    echo 'Exception when calling CheckoutApi->createCheckout: ', $e->getMessage(), PHP_EOL;
}
?>
