# Smartpay PHP Library

The Smartpay PHP library offers easy access to Smartpay API from applications written in PHP.

## Documentation

- [Payment Flow](https://docs.smartpay.co/#payment_flow)
- [API Document](https://api-doc.smartpay.co)

## Requirements

- PHP 5.6+
- Smartpay `API keys & secrets`. You can find your credential at the `settings > credentials` page on your [dashboard](https://dashboard.smartpay.co/settings/credentials).

## Installation

#### Install Composer

[Composer](https://getcomposer.org/) is a dependency manager for PHP projects.

Download Composer from their website: [https://getcomposer.org/download/](https://getcomposer.org/download/)

If you are using macos to develop with [homebrew](https://docs.brew.sh/), you can use the command to install Composer:

```shell
brew install composer
```

#### Install package to your repository:

```shell
composer install smartpay-co/sdk-php
```

## Usage

The package needs to be configured with your own API keys, you can find them on your dashboard.

```php
$api = new \Smartpay\Api('<YOUR_PUBLIC_KEY>', '<YOUR_SECRET_KEY>')
```

### Create Checkout session

You can find the description and requirement for request payload in [API Document](https://api-doc.smartpay.co/#8a3538b1-530c-448c-8bae-4a41cdf0b8fd).

```php
$payload = [
  'items' => [[
    'name' => 'オリジナルス STAN SMITH',
    'amount' => 250,
    'currency' => 'JPY',
    'quantity' => 1,
  ]],
  'customer' => [
    'accountAge' => 20,
    'email' => 'merchant-support@smartpay.co',
    'firstName' => '田中',
    'lastName' => '太郎',
    'firstNameKana' => 'たなか',
    'lastNameKana' => 'たろう',
    'address' => [
      'line1' => '北青山 3-6-7',
      'line2' => '青山パラシオタワー 11階',
      'subLocality' => '',
      'locality' => '港区',
      'administrativeArea' => '東京都',
      'postalCode' => '107-0061',
      'country' => 'JP',
    ],
    'dateOfBirth' => '1985-06-30',
    'gender' => 'male',
  ],
  'shipping' => [
    'line1' => '北青山 3-6-7',
    'line2' => '青山パラシオタワー 11階',
    'subLocality' => '',
    'locality' => '港区',
    'administrativeArea' => '東京都',
    'postalCode' => '107-0061',
    'country' => 'JP',
  ],
  'reference' => 'order_ref_1234567',
  'successURL' => 'https://docs.smartpay.co/example-pages/checkout-successful',
  'cancelURL' => 'https://docs.smartpay.co/example-pages/checkout-canceled'
];
```

Create a checkout session by using `checkoutSession` method with your request payload.

```php
$session = $api->checkoutSession($payload);
```

Then, you can redirect your customer to the session url by calling `redirectUrl` method:

```php
$session->redirectUrl()
```

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/[USERNAME]/smartpay. This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [code of conduct](https://github.com/[USERNAME]/smartpay/blob/master/CODE_OF_CONDUCT.md).

## License

The package is available as open source under the terms of the [MIT License](https://opensource.org/licenses/MIT).

## Code of Conduct

Everyone interacting in the Smartpay project's codebases, issue trackers, chat rooms and mailing lists is expected to follow the [code of conduct](https://github.com/[USERNAME]/smartpay/blob/master/CODE_OF_CONDUCT.md).
