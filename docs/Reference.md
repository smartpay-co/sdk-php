# Smartpay PHP SDK Reference

- [Class Smartpay](#class-smartpay)
  - [Constructor](#constructor)
  - [Create Checkout Session](#create-checkout-session)
  - [Get Checkout Session](#get-checkout-session)
  - [List Checkout Sessions](#list-checkout-sessions)
  - [Get Order](#get-order)
  - [Create Order With Token](#create-order)
  - [Cancel Order](#cancel-order)
  - [List Orders](#list-orders)
  - [Create Payment](#create-payment)
  - [Get Payment](#get-payment)
  - [Update Payment](#update-payment)
  - [List Payments](#list-payments)
  - [Create Refund](#create-refund)
  - [Get Refund](#get-refund)
  - [Update Refund](#update-refund)
  - [List Refunds](#list-refunds)
  - [Create Webhook Endpoint](#create-webhook-endpoint)
  - [Get Webhook Endpoint](#get-webhook-endpoint)
  - [Update Webhook Endpoint](#update-webhook-endpoint)
  - [Delete Webhook Endpoint](#delete-webhook-endpoint)
  - [List Webhook Endpoints](#list-webhook-endpoints)
  - [Calculate Webhook Signature](#calculate-webhook-signature)
  - [Verify Webhook Signature](#verify-webhook-signature)
  - [Create Coupon](#create-coupon)
  - [Get Coupon](#get-coupon)
  - [Update Coupon](#update-coupon)
  - [List Coupons](#list-coupons)
  - [Create Promotion Code](#create-promotion-code)
  - [Get Promotion Code](#get-promotion-code)
  - [Update Promotion Code](#update-promotion-code)
  - [List Promotion Codes](#list-promotion-codes)
  - [Get Token](#get-token)
  - [List Tokens](#list-tokens)
  - [Enable Token](#enable-token)
  - [Disable Token](#disable-token)
  - [Delete Token](#delete-token)
- [Base Response](#base-response)
- [Checkout Session Response](#checkout-session-response)
- [Collection](#collection)
  - [Properties](#properties)
- [Constants](#constants)
  - [Address Type](#address-type)
  - [Capture Method](#capture-method)
  - [Order Status](#order-status)
  - [Cancel Remainder](#cancel-remainder)
  - [Refund Reason](#refund-reason)
  - [Discount Type](#discount-type)
- [Common Exceptions](#common-exceptions)

## Class Smartpay Api

The main class.

### Constructor

```php
$api = new \Smartpay\Api($secretKey, $publicKey);
```

#### Arguments

| Name                 | Type   | Description                            |
| -------------------- | ------ | -------------------------------------- |
| secretKey            | String | The secret key from merchant dashboard |
| publicKey (optional) | String | The public key from merchant dashboard |

#### Return

Smartpay Api class instance. Methods are documented below.

#### Exceptions

| Type  | Description            |
| ----- | ---------------------- |
| Error | Secret Key is required |
| Error | Secret Key is invalid  |
| Error | Public Key is invalid  |

### Create Checkout Session

Create a checkout session.

```php
$checkoutSession = $api->checkoutSession($payload, $idempotencyKey);
```

#### Arguments

| Name                      | Type   | Description                                                                      |
| ------------------------- | ------ | -------------------------------------------------------------------------------- |
| payload                   | Array  | The checkout session payload, [strict][strict-payload] or [loose][loose-payload] |
| idempotencyKey (optional) | String | The custom idempotency key                                                       |

[strict-payload]: https://en.docs.smartpay.co/reference/create-checkout-session
[loose-payload]: https://github.com/smartpay-co/sdk-node/blob/main/docs/SimpleCheckoutSession.md

#### Return

The [checkout session object][]

### Get Checkout Session

**Async** method, get single checkout session object by checkout session id.

```php
$checkoutSession = $api->getCheckoutSession($id);
```

#### Arguments

| Name | Type   | Description             |
| ---- | ------ | ----------------------- |
| id   | String | The checkout session id |

#### Return

[Checkout session object][]

#### Exceptions

[Common exceptions][]

### List Checkout Sessions

**Async** method, list checkout session objects.

```php
$checkoutSessionsCollectionResponse = $api->getCheckoutSessions($params);
```

#### Arguments

| Name                                     | Type   | Description                                                                                |
| ---------------------------------------- | ------ | ------------------------------------------------------------------------------------------ |
| params                                   | Array  | The query parameters                                                                       |
| params.maxResults (optional, defualt=20) | Number | Number of objects to return.                                                               |
| params.pageToken (optional)              | String | The token for the page of the collection of objects.                                       |
| params.expand (optional, default=no)     | String | Set to `all` if the references within the response need to be expanded to the full objects |

#### Return

The [Base Response](#base-response) object whose data is [Collection][] of [checkout session object][]

#### Exceptions

[Common exceptions][]

### Get Order

Get single order object by order id.

```php
$orderResponse = $api->getOrder($params);
```

#### Arguments

| Name      | Type   | Description      |
| --------- | ------ | ---------------- |
| params    | Array  | The params array |
| params.id | String | The order id     |

#### Return

The [Base Response](#base-response) object whose data is an [Order object][].

#### Exceptions

[Common exceptions][]

### Create Order

Create an order using a token.

```php
$orderResponse = $api->createOrder($payload, $idempotencyKey);
```

#### Arguments

| Name                      | Type   | Description                |
| ------------------------- | ------ | -------------------------- |
| payload                   | Array  | The [order payload][]      |
| idempotencyKey (optional) | String | The custom idempotency key |

[order payload]: https://en.docs.smartpay.co/reference/create-order

#### Return

The [Base Response](#base-response) object whose data is an [Order object][].

#### Exceptions

[Common exceptions][]

### Cancel Order

**Async** method, cancel an order.

```php
$orderResponse = $api->cancelOrder($params, $idempotencyKey);
```

#### Arguments

| Name                      | Type   | Description                |
| ------------------------- | ------ | -------------------------- |
| params                    | Array  | The params array           |
| params.id                 | String | The order id               |
| idempotencyKey (optional) | String | The custom idempotency key |

#### Return

The [Base Response](#base-response) object whose data is [Order object][].

#### Exceptions

[Common exceptions][]

### List Orders

List order objects.

```php
$ordersCollectionResponse = $api->getOrders($params);
```

#### Arguments

| Name                                     | Type   | Description                                                                                |
| ---------------------------------------- | ------ | ------------------------------------------------------------------------------------------ |
| params                                   | Array  | The query parameters                                                                       |
| params.maxResults (optional, defualt=20) | Number | Number of objects to return.                                                               |
| params.pageToken (optional)              | String | The token for the page of the collection of objects.                                       |
| params.expand (optional, default=no)     | String | Set to `all` if the references within the response need to be expanded to the full objects |

#### Return

The [Base Response](#base-response) object whose data is [Collection][] of [order object][]

#### Exceptions

[Common exceptions][]

### Create Payment

Create a payment object([capture][]) to an order.

```php
$paymentResponse = $api->createPayment($payload);
```

#### Arguments

| Name                                                  | Type     | Description                                                                                              |
| ----------------------------------------------------- | -------- | -------------------------------------------------------------------------------------------------------- |
| payload                                               | Array    | The [payment payload][]                                                                                  |
| payload.order                                         | String   | The order id                                                                                             |
| payload.amount                                        | Number   | The amount of the payment                                                                                |
| payload.currency                                      | String   | Three-letter ISO currency code, in uppercase. Must be a supported currency.                              |
| payload.cancelRemainder (optional, default=automatic) | Stirng   | Whether to cancel remaining amount in case of a partial capture. `automatic` or `manual`.                |
| payload.lineItems (optional)                          | String[] | A list of the IDs of the Line Items of the original Payment this Refund is on.                           |
| payload.reference (optional)                          | String   | A string to reference the Payment which can be used to reconcile the Payment with your internal systems. |
| payload.description (optional)                        | String   | An arbitrary long form explanation of the Payment, meant to be displayed to the customer.                |
| payload.metadata (optional)                           | Array    | Set of up to 20 key-value pairs that you can attach to the object.                                       |

[payment payload]: https://en.docs.smartpay.co/reference/create-payment

#### Return

The [Base Response](#base-response) object whose data is a [payment object][]

#### Exceptions

[Common exceptions][]

| Type          | Error Code                 | Description                                                           |
| ------------- | -------------------------- | --------------------------------------------------------------------- |
| SmartpayError | `order.not-found`          | No order was found meeting the requirements.                          |
| SmartpayError | `order.cannot-capture`     | No payment can be created. The error message will include the reason. |
| SmartpayError | `payment.excessive-amount` | The payment exceeds the order's amount available for capture          |

### Get Payment

Get the payment object by payment id.

```php
$paymentResponse = $api->getPayment($params);
```

#### Arguments

| Name      | Type   | Description      |
| --------- | ------ | ---------------- |
| params    | Array  | The params array |
| params.id | String | The payment id   |

#### Return

The [Base Response](#base-response) object whose data is a [payment object][]

#### Exceptions

[Common exceptions][]

### Update Payment

Create a payment object([capture][]) to an order.

```php
$paymentResponse = $api->updatePayment($payload, $idempotencyKey);
```

#### Arguments

| Name                           | Type   | Description                                                                                              |
| ------------------------------ | ------ | -------------------------------------------------------------------------------------------------------- |
| payload                        | Array  | Partial of the [payment payload][]                                                                       |
| payload.id                     | String | The payment id                                                                                           |
| payload.reference (optional)   | String | A string to reference the Payment which can be used to reconcile the Payment with your internal systems. |
| payload.description (optional) | String | An arbitrary long form explanation of the Payment, meant to be displayed to the customer.                |
| payload.metadata (optional)    | Array  | Set of up to 20 key-value pairs that you can attach to the object.                                       |
| idempotencyKey (optional)      | String | The custom idempotency key                                                                               |

#### Return

The [Base Response](#base-response) object whose data is a [payment object][]

#### Exceptions

[Common exceptions][]

### List Payments

List the payment objects.

```php
$paymentsResponse = $api->getPayments($params);
```

#### Arguments

| Name                                     | Type   | Description                                                                                |
| ---------------------------------------- | ------ | ------------------------------------------------------------------------------------------ |
| params                                   | Array  | The query parameters                                                                       |
| params.maxResults (optional, defualt=20) | Number | Number of objects to return.                                                               |
| params.pageToken (optional)              | String | The token for the page of the collection of objects.                                       |
| params.expand (optional, default=no)     | String | Set to `all` if the references within the response need to be expanded to the full objects |

#### Return

The [Base Response](#base-response) object whose data is [Collection][] of [payment object][]

#### Exceptions

[Common exceptions][]

### Create Refund

Create a refund object([refund][]) to a payment.

```php
$refundResponse = $api->createRefund($payload, $idempotencyKey);
```

#### Arguments

| Name                           | Type     | Description                                                                                              |
| ------------------------------ | -------- | -------------------------------------------------------------------------------------------------------- |
| payload                        | Array    | The [refund payload][]                                                                                   |
| payload.payment                | String   | The payment id                                                                                           |
| payload.amount                 | Number   | The amount of the refund                                                                                 |
| payload.currency               | String   | Three-letter ISO currency code, in uppercase. Must be a supported currency.                              |
| payload.reason                 | Stirng   | The reason of the Refund. `requested_by_customer` or `fraudulent`                                        |
| payload.lineItems (optional)   | String[] | A list of the IDs of the Line Items of the original Payment this Refund is on.                           |
| payload.reference (optional)   | String   | A string to reference the Payment which can be used to reconcile the Payment with your internal systems. |
| payload.description (optional) | String   | An arbitrary long form explanation of the Payment, meant to be displayed to the customer.                |
| payload.metadata (optional)    | Array    | Set of up to 20 key-value pairs that you can attach to the object.                                       |
| idempotencyKey (optional)      | String   | The custom idempotency key                                                                               |

[refund payload]: https://en.docs.smartpay.co/reference/create-refund

#### Return

[Refund object][]

#### Exceptions

[Common exceptions][]

| Type          | Error Code            | Description                                                        |
| ------------- | --------------------- | ------------------------------------------------------------------ |
| SmartpayError | `payment.not-found`   | No payment was found meeting the requirements.                     |
| SmartpayError | `amount.insufficient` | Available amount on payment is insufficient to handle the request. |

### Get Refund

Get the refund object by refund id.

```php
$refundResponse = $api->getRefund($id);
```

#### Arguments

| Name | Type   | Description   |
| ---- | ------ | ------------- |
| id   | String | The refund id |

#### Return

The [Base Response](#base-response) object whose data is a [Refund object][]

#### Exceptions

[Common exceptions][]

### Update Refund

Update a refund object([capture][]).

```php
$refundResponse = $api->updateRefund($payload, $idempotencyKey);
```

#### Arguments

| Name                           | Type   | Description                                                                                              |
| ------------------------------ | ------ | -------------------------------------------------------------------------------------------------------- |
| payload                        | Array  | Partail of the [refund payload][]                                                                        |
| payload.id                     | String | The refund id                                                                                            |
| payload.reference (optional)   | String | A string to reference the Payment which can be used to reconcile the Payment with your internal systems. |
| payload.description (optional) | String | An arbitrary long form explanation of the Payment, meant to be displayed to the customer.                |
| payload.metadata (optional)    | Array  | Set of up to 20 key-value pairs that you can attach to the object.                                       |
| idempotencyKey (optional)      | String | The custom idempotency key                                                                               |

#### Return

The [Base Response](#base-response) object whose data is a [Refund object][]

#### Exceptions

[Common exceptions][]

### List Refunds

List refunds.

```php
$refundsResponse = $api->listRefunds($params);
```

#### Arguments

| Name                                     | Type   | Description                                                                                |
| ---------------------------------------- | ------ | ------------------------------------------------------------------------------------------ |
| params                                   | Array  | The query parameters                                                                       |
| params.maxResults (optional, defualt=20) | Number | Number of objects to return.                                                               |
| params.pageToken (optional)              | String | The token for the page of the collection of objects.                                       |
| params.expand (optional, default=no)     | String | Set to `all` if the references within the response need to be expanded to the full objects |

#### Return

The [Base Response](#base-response) object whose data is [Collection][] of [refund object][]

#### Exceptions

[Common exceptions][]

#### Create Webhook Endpoint

Create a webhook endpoint object.

```php
$webhookEndpointResponse = $api->createWebhookEndpoint($payload, $idempotencyKey);
```

#### Arguments

| Name                           | Type     | Description                                                                                        |
| ------------------------------ | -------- | -------------------------------------------------------------------------------------------------- |
| payload                        | Array    | The [webhook endpoint payload][]                                                                   |
| payload.url                    | String   | The url which will be called when any of the events you subscribed to occur.                       |
| payload.eventSubscriptions     | String[] | The list of events to subscribe to. If not specified you will be subsribed to all events.          |
| payload.description (optional) | String   | An arbitrary long form explanation of the Webhook Endpoint, meant to be displayed to the customer. |
| payload.metadata (optional)    | Array    | Set of up to 20 key-value pairs that you can attach to the object.                                 |
| idempotencyKey (optional)      | String   | The custom idempotency key                                                                         |

[webhook endpoint payload]: https://en.docs.smartpay.co/reference/create-webhook-endpoint

#### Return

The [Base Response](#base-response) object whose data is [Webhook Endpoint object][]

#### Exceptions

[Common exceptions][]

### Get Webhook Endpoint

Get the webhook endpoint object by webhook endpoint id.

```php
$webhookEndpointResponse = $api->getWebhookEndpoint($params);
```

#### Arguments

| Name      | Type   | Description             |
| --------- | ------ | ----------------------- |
| params    | Array  | The query parameters    |
| params.id | String | The webhook endpoint id |

#### Return

The [Base Response](#base-response) object whose data is [Webhook Endpoint object][]

#### Exceptions

[Common exceptions][]

### Update Webhook Endpoint

Update a webhook endpoint.

```php
$webhookEndpointResponse = $api->updateWebhookEndpoint($payload, $idempotencyKey);
```

#### Arguments

| Name                                  | Type     | Description                                                                                        |
| ------------------------------------- | -------- | -------------------------------------------------------------------------------------------------- |
| payload                               | Array    | Partial of the [webhook endpoint payload][]                                                        |
| payload.id                            | String   | The webhook endpoint id                                                                            |
| payload.active (optional)             | Boolean  | Has the value true if the webhook endpoint is active and events are sent to the url specified.     |
| payload.url (optional)                | String   | The url which will be called when any of the events you subscribed to occur.                       |
| payload.eventSubscriptions (optional) | String[] | The list of events to subscribe to. If not specified you will be subsribed to all events.          |
| payload.description (optional)        | String   | An arbitrary long form explanation of the Webhook Endpoint, meant to be displayed to the customer. |
| payload.metadata (optional)           | Array    | Set of up to 20 key-value pairs that you can attach to the object.                                 |
| idempotencyKey (optional)             | String   | The custom idempotency key                                                                         |

#### Return

[Webhook Endpoint object][]

#### Exceptions

[Common exceptions][]

### Delete Webhook Endpoint

Delete the webhook endpoint by webhook endpoint id.

```php
$api->deleteWebhookEndpoint($params, $idempotencyKey);
```

#### Arguments

| Name                      | Type   | Description                |
| ------------------------- | ------ | -------------------------- |
| params                    | Array  | The query parameters       |
| params.id                 | String | The webhook endpoint id    |
| idempotencyKey (optional) | String | The custom idempotency key |

#### Return

The [Base Response](#base-response) object whose data is empty

#### Exceptions

[Common exceptions][]

### List Webhook Endpoints

List the webhook endpoint objects.

```php
$webhookEndpointsResponse = $api->getWebhookEndpoints($params);
```

#### Arguments

| Name                                     | Type   | Description                                                                                |
| ---------------------------------------- | ------ | ------------------------------------------------------------------------------------------ |
| params                                   | Array  | The query parameters                                                                       |
| params.maxResults (optional, defualt=20) | Number | Number of objects to return.                                                               |
| params.pageToken (optional)              | String | The token for the page of the collection of objects.                                       |
| params.expand (optional, default=no)     | String | Set to `all` if the references within the response need to be expanded to the full objects |

#### Return

The [Base Response](#base-response) object whose data is a [Collection][] of [webhook endpoint object][]

#### Exceptions

[Common exceptions][]

### Calculate Webhook Signature

Calculate the signature for webhook event of the given data.

```php
$signature = $api->calculateWebhookSignature($data, $signatureTimestamp, $secret);
```

#### Arguments

| Name               | Type   | Description                       |
| ------------------ | ------ | --------------------------------- |
| data               | String | The data string                   |
| signatureTimestamp | String | The signature timestamp           |
| secret             | String | The Base62 encoded signing secret |

#### Return

Signature of the data.

### Verify Webhook Signature

Verify the signature of the given data.

```php
$verifyResult = $api->verifyWebhookSignature(
  $data,
  $secret,
  $signature,
);
```

#### Arguments

| Name      | Type   | Description                       |
| --------- | ------ | --------------------------------- |
| data      | String | The data string                   |
| secret    | String | The Base62 encoded signing secret |
| signature | String | The expected signature value      |

#### Return

Boolean value, `true` if the signatures are matching.

#### Create Coupon

Create a coupon object.

```php
$couponResponse = $api->createCoupon();
```

#### Arguments

| Name                                  | Type   | Description                                                                                                        |
| ------------------------------------- | ------ | ------------------------------------------------------------------------------------------------------------------ |
| payload                               | Array  | The [coupon payload][]                                                                                             |
| payload.name                          | String | The coupon's name, meant to be displayable to the customer.                                                        |
| payload.discountType                  | String | Discount Type. `amount` or `percentage`                                                                            |
| payload.discountAmount                | Number | Required if discountType is `amount`. The amount of this coupon object.                                            |
| payload.discountPercentage            | Number | Required if discountType is `percentage`. The discount percentage of this coupon object.                           |
| payload.currency                      | String | Required if discountType is `amount`. Three-letter ISO currency code, in uppercase. Must be a supported currency.  |
| payload.expiresAt (optional)          | String | Time at which the Coupon expires. Measured in milliseconds since the Unix epoch.                                   |
| payload.maxRedemptionCount (optional) | String | Maximum number of times this coupon can be redeemed, in total, across all customers, before it is no longer valid. |
| payload.metadata (optional)           | Array  | Set of up to 20 key-value pairs that you can attach to the object.                                                 |
| idempotencyKey (optional)             | String | The custom idempotency key                                                                                         |

[coupon payload]: https://en.docs.smartpay.co/reference/create-coupon

#### Return

[Coupon object][]

#### Exceptions

[Common exceptions][]

### Get Coupon

Get the coupon object by coupon id.

```php
$couponResponse = $api->getCoupon($params);
```

#### Arguments

| Name      | Type   | Description          |
| --------- | ------ | -------------------- |
| params    | Array  | The query parameters |
| params.id | String | The coupon id        |

#### Return

The [Base Response](#base-response) object whose data is a [Coupon object][]

#### Exceptions

[Common exceptions][]

### Update Coupon

Update a coupon.

```php
$couponResposne = $api->updateCoupon($payload, $idempotencyKey);
```

#### Arguments

| Name                        | Type    | Description                                                                          |
| --------------------------- | ------- | ------------------------------------------------------------------------------------ |
| payload                     | Array   | Partial of the [coupon payload][]                                                    |
| payload.id                  | String  | The coupon id                                                                        |
| payload.name (optional)     | String  | The coupon's name, meant to be displayable to the customer.                          |
| payload.active (optional)   | Boolean | Has the value true if the coupon is active and events are sent to the url specified. |
| payload.metadata (optional) | Array   | Set of up to 20 key-value pairs that you can attach to the object.                   |
| idempotencyKey (optional)   | String  | The custom idempotency key                                                           |

#### Return

The [Base Response](#base-response) object whose data is a [Coupon object][]

#### Exceptions

[Common exceptions][]

### List Coupons

List the coupon objects.

```php
$couponsResponse = $api->listCoupons($params);
```

#### Arguments

| Name                                     | Type   | Description                                                                                |
| ---------------------------------------- | ------ | ------------------------------------------------------------------------------------------ |
| params                                   | Array  | The query parameters                                                                       |
| params.maxResults (optional, defualt=20) | Number | Number of objects to return.                                                               |
| params.pageToken (optional)              | String | The token for the page of the collection of objects.                                       |
| params.expand (optional, default=no)     | String | Set to `all` if the references within the response need to be expanded to the full objects |

#### Return

The [Base Response](#base-response) object whose data is a [Collection][] of [coupon object][]

#### Exceptions

[Common exceptions][]

### Create Promotion Code

Create a promotion code object of a coupon.

```php
$promotionCodeResponse = $api->createPromotionCode($payload, $idempotencyKey);
```

#### Arguments

| Name                                    | Type    | Description                                                                                                                                                    |
| --------------------------------------- | ------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| payload                                 | Array   | The [promotion code payload][]                                                                                                                                 |
| payload.coupon                          | String  | The unique identifier for the Coupon object.                                                                                                                   |
| payload.code                            | String  | The customer-facing code. Regardless of case, this code must be unique across all your promotion codes.                                                        |
| payload.active (optional)               | Boolean | Has the value true (default) if the promotion code is active and can be used, or the value false if it is not.                                                 |
| payload.currency (optional)             | String  | Three-letter ISO currency code, in uppercase. Must be a supported currency.                                                                                    |
| payload.expiresAt (optional)            | Number  | Time at which the Promotion Code expires. Measured in milliseconds since the Unix epoch.                                                                       |
| payload.firstTimeTransaction (optional) | Boolean | A Boolean indicating if the Promotion Code should only be redeemed for customers without any successful order with the merchant. Defaults to false if not set. |
| payload.maxRedemptionCount (optional)   | Number  | Maximum number of times this Promotion Code can be redeemed, in total, across all customers, before it is no longer valid.                                     |
| payload.minimumAmount (optional)        | Number  | The minimum amount required to redeem this Promotion Code (e.g., the amount of the order must be ¥10,000 or more to be applicable).                            |
| payload.onePerCustomer (optional)       | Boolean | A Boolean indicating if the Promotion Code should only be redeemed once by any given customer. Defaults to false if not set.                                   |
| payload.metadata (optional)             | Array   | Set of up to 20 key-value pairs that you can attach to the object.                                                                                             |
| idempotencyKey (optional)               | String  | The custom idempotency key                                                                                                                                     |

[promotion code payload]: https://en.docs.smartpay.co/reference/create-promotion-code

#### Return

The [Base Response](#base-response) object whose data is a [Promotion Code object][]

#### Exceptions

[Common exceptions][]

| Type          | Error Code              | Description                                                                                               |
| ------------- | ----------------------- | --------------------------------------------------------------------------------------------------------- |
| SmartpayError | `coupon.not-found`      | No coupon was found meeting the requirements.                                                             |
| SmartpayError | `promotion-code.exists` | The promotion code {code} already exists. The code needs to be unique across all of your promotion codes. |

### Get Promotion Code

Get the promotion code object by promotion code id.

```php
$promotionCodeResponse = $api->getPromotionCode($params);
```

#### Arguments

| Name      | Type   | Description           |
| --------- | ------ | --------------------- |
| params    | Array  | The query parameters  |
| params.id | String | The promotion code id |

#### Return

The [Base Response](#base-response) object whose data is a [Promotion Code object][]

#### Exceptions

[Common exceptions][]

### Update Promotion Code

Update a promotion code.

```php
$promotionCodeResponse = $api->updatePromotionCode($payload, $idempotencyKey);
```

#### Arguments

| Name                        | Type    | Description                                                                                 |
| --------------------------- | ------- | ------------------------------------------------------------------------------------------- |
| payload                     | Array   | Partial of the [promotion code payload][]                                                   |
| payload.id                  | String  | The promotion code id                                                                       |
| payload.active (optional)   | Boolean | Has the value true if the promotion codeis active and events are sent to the url specified. |
| payload.metadata (optional) | Array   | Set of up to 20 key-value pairs that you can attach to the object.                          |
| idempotencyKey (optional)   | String  | The custom idempotency key                                                                  |

#### Return

The [Base Response](#base-response) object whose data is a [Promotion Code object][]

#### Exceptions

[Common exceptions][]

### List Promotion Codes

List the promotion code objects.

```php
$promotionCodesResponse = $api->getPromotionCodes($params);
```

#### Arguments

| Name                                     | Type   | Description                                                                                |
| ---------------------------------------- | ------ | ------------------------------------------------------------------------------------------ |
| params                                   | Array  | The query parameters                                                                       |
| params.maxResults (optional, defualt=20) | Number | Number of objects to return.                                                               |
| params.pageToken (optional)              | String | The token for the page of the collection of objects.                                       |
| params.expand (optional, default=no)     | String | Set to `all` if the references within the response need to be expanded to the full objects |

#### Return

The [Base Response](#base-response) object whose data is [Collection][] of [Collection][] of [promotion code object][]

#### Exceptions

[Common exceptions][]

### Get Token

Get the token object by token id.

```php
$tokenResponse = $api->getToken($params);
```

#### Arguments

| Name      | Type   | Description          |
| --------- | ------ | -------------------- |
| params    | Array  | The query parameters |
| params.id | String | The token id         |

#### Return

The [Base Response](#base-response) object whose data is [Token object][]

#### Exceptions

[Common exceptions][]

### List Tokens

List the token objects.

```php
$tokensResponse = $api->listTokens($params);
```

#### Arguments

| Name                              | Type   | Description                                                                                |
| --------------------------------- | ------ | ------------------------------------------------------------------------------------------ |
| params                            | Array  | The query parameters                                                                       |
| maxResults (optional, defualt=20) | Number | Number of objects to return.                                                               |
| pageToken (optional)              | String | The token for the page of the collection of objects.                                       |
| expand (optional, default=no)     | String | Set to `all` if the references within the response need to be expanded to the full objects |

#### Return

The [Base Response](#base-response) object whose data is [Collection][] of [token object][]

#### Exceptions

[Common exceptions][]

### Enable Token

Enable the token by token id.

```php
$resultResponse = $api->enableToken($params);
```

#### Arguments

| Name   | Type   | Description          |
| ------ | ------ | -------------------- |
| params | Array  | The query parameters |
| id     | String | The token id         |

#### Return

The [Base Response](#base-response) object whose data is empty

#### Exceptions

[Common exceptions][]

| Type          | Error Code        | Description                                                                                                                   |
| ------------- | ----------------- | ----------------------------------------------------------------------------------------------------------------------------- |
| SmartpayError | `token.not-found` | No token was found meeting the requirements. Try to enable token under `requires_authorization` status throws this error too. |

### Disable Token

Disable the token by token id.

```php
$resultResponse = $api->disableToken(params);
```

#### Arguments

| Name   | Type   | Description          |
| ------ | ------ | -------------------- |
| params | Array  | The query parameters |
| id     | String | The token id         |

#### Return

The [Base Response](#base-response) object whose data is empty

#### Exceptions

[Common exceptions][]

| Type          | Error Code        | Description                                                                                                                    |
| ------------- | ----------------- | ------------------------------------------------------------------------------------------------------------------------------ |
| SmartpayError | `token.not-found` | No token was found meeting the requirements. Try to disable token under `requires_authorization` status throws this error too. |

### Delete Token

Ddelete the token by token id.

```php
$resultResponse = $api->deleteToken($params);
```

#### Arguments

| Name   | Type   | Description          |
| ------ | ------ | -------------------- |
| params | Array  | The query parameters |
| id     | String | The token id         |

#### Return

The [Base Response](#base-response) object whose data is empty

#### Exceptions

[Common exceptions][]

| Type          | Error Code        | Description                                  |
| ------------- | ----------------- | -------------------------------------------- |
| SmartpayError | `token.not-found` | No token was found meeting the requirements. |

## Base Response

### asJson

Return the data array

```php
$data = $baseResponse->asJson();
```

#### Return

The object data array.

## Checkout Session Response

### asJson

Return the checkout session data array

```php
$checkoutSessionResponse = $api->checkoutSession($payload);
$checkoutSession = $checkoutSessionResponse->asJson();
```

#### Return

The [checkout session object][]

### redirectUrl

Return the checkout url of the session

```php
$checkoutSessionResponse = $api->checkoutSession($payload);
$checkoutSessionUrl = $checkoutSessionResponse->redirectUrl($options);
```

#### Arguments

| Name                  | Type   | Description                   |
| --------------------- | ------ | ----------------------------- |
| options               | Array  | The options object            |
| options.promotionCode | String | The pre-append promotion code |

#### Return

The checkout URL of the checkout session. ex:

```
https://checkout.smartpay.co/checkout_live_vptIEMeycBuKLNNVRL6kB2.1ntK1e.2Z9eoI1j1KU7Jz7XMA9t9wU6gKI4ByzfUSJcwZAhYDoZWPr46ztb1F1ZcsBc7J4QmifNzmcNm4eVHSO98sMVzg
```

## Collection

Collection of items, a general data structure of collection data.

### Properties

| Name          | Type   | Description                                                                                                                        |
| ------------- | ------ | ---------------------------------------------------------------------------------------------------------------------------------- |
| object        | String | Always be `collection`                                                                                                             |
| pageToken     | String | The token for the page of the collection of objects.                                                                               |
| nextPageToken | String | The token for the next page of the collection of objects.                                                                          |
| maxResults    | Number | The maximum number of objects returned for this call. Equals to the maxResults query parameter specified (or 20 if not specified). |
| results       | Number | The actual number of objects returned for this call. This value is less than or equal to maxResults.                               |
| data          | Array  | The array of data                                                                                                                  |

## Constants

### Address Type

```
Smartpay::ADDRESS_TYPE_HOME
Smartpay::ADDRESS_TYPE_GIFT
Smartpay::ADDRESS_TYPE_LOCKER
Smartpay::ADDRESS_TYPE_OFFICE
Smartpay::ADDRESS_TYPE_STORE
```

### Capture Method

```
Smartpay::CAPTURE_METHOD_AUTOMATIC
Smartpay::CAPTURE_METHOD_MANUAL
```

### Order Status

```
Smartpay::ORDER_STATUS_SUCCEEDED
Smartpay::ORDER_STATUS_CANCELED
Smartpay::ORDER_STATUS_REJECTED
Smartpay::ORDER_STATUS_FAILED
Smartpay::ORDER_STATUS_REQUIRES_AUTHORIZATION
```

### Token Status

```
Smartpay::TOKEN_STATUS_ACTIVE
Smartpay::TOKEN_STATUS_DISABLED
Smartpay::TOKEN_STATUS_REJECTED
Smartpay::TOKEN_STATUS_REQUIRES_AUTHORIZATION
```

### Cancel Remainder

```
Smartpay::CANCEL_REMAINDER_AUTOMATIC
Smartpay::CANCEL_REMAINDER_MANUAL
```

### Refund Reason

```
Smartpay::REFUND_REQUEST_BY_CUSTOMER
Smartpay::REFUND_FRAUDULENT
```

### Discount Type

```
Smartpay::COUPON_DISCOUNT_TYPE_AMOUNT
Smartpay::COUPON_DISCOUNT_TYPE_PERCENTAGE
```

## Common Exceptions

| Type          | Error Code                   | Description                    |
| ------------- | ---------------------------- | ------------------------------ |
| SmartpayError | `unexpected_error`           | Unexpected network issue.      |
| SmartpayError | `unexpected_error`           | Unable to parse response body. |
| SmartpayError | `request.invalid`            | Required argument is missing.  |
| SmartpayError | Error code from API response | Unable to parse response body. |

[checkout session object]: https://en.docs.smartpay.co/reference/the-checkout-session-object
[order object]: https://en.docs.smartpay.co/reference/the-order-object
[payment object]: https://en.docs.smartpay.co/reference/the-payment-object
[refund object]: https://en.docs.smartpay.co/reference/the-refund-object
[webhook endpoint object]: https://en.docs.smartpay.co/reference/the-webhook-endpoint-object
[coupon object]: https://en.docs.smartpay.co/reference/the-coupon-object
[promotion code object]: https://en.docs.smartpay.co/reference/the-promotion-code-object
[token object]: https://en.docs.smartpay.co/reference/the-token-object
[capture]: https://en.docs.smartpay.co/docs/capture-an-order#using-the-smartpay-api
[refund]: https://en.docs.smartpay.co/docs/refund-a-purchase#using-the-smartpay-api
[collection]: #collection
[common exceptions]: #common-exceptions
