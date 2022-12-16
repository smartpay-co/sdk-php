<div id="top"></div>

<!-- PROJECT SHIELDS -->

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![Packagist][packagist-shield]][packagist-url]
[![PHP Version][php-shield]][php-url]
[![MIT License][license-shield]][license-url]

<br />
<div align="center">
  <a href="https://github.com/smartpay-co/sdk-php">
		<picture>
			<source media="(prefers-color-scheme: dark)" srcset="https://assets.smartpay.co/logo/banner/smartpay-logo-dark.png" />
			<source media="(prefers-color-scheme: light)" srcset="https://assets.smartpay.co/logo/banner/smartpay-logo.png" />
			<img alt="Smartpay" src="https://assets.smartpay.co/logo/banner/smartpay-logo.png" style="width: 797px;" />
		</picture>
  </a>

  <p align="center">
    <a href="https://docs.smartpay.co/"><strong>Explore the docs »</strong></a>
    <br />
    <br />
    <a href="https://github.com/smartpay-co/sdk-php/issues">Report Bug</a>
    ·
    <a href="https://github.com/smartpay-co/sdk-php/issues">Request Feature</a>
  </p>
</div>

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
  'items' => [
    [
      'name' => 'オリジナルス STAN SMITH',
      'amount' => 1000,
      'currency' => 'JPY',
      'quantity' => 1,
    ],
    [
      'currency' => 'JPY',
      'amount' => 500,
      'name' => 'Merchant special discount',
      'kind' => 'discount'
    ],
    [
      'currency' => 'JPY',
      'amount' => 100,
      'name' => 'explicit taxes',
      'kind' => 'tax'
    ]
  ],
  'customerInfo' => [
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
  'shippingInfo' => [
    'line1' => '北青山 3-6-7',
    'line2' => '青山パラシオタワー 11階',
    'subLocality' => '',
    'locality' => '港区',
    'administrativeArea' => '東京都',
    'postalCode' => '107-0061',
    'country' => 'JP',
  ],
  'reference' => 'order_ref_1234567',
  'successUrl' => 'https://docs.smartpay.co/example-pages/checkout-successful',
  'cancelUrl' => 'https://docs.smartpay.co/example-pages/checkout-canceled'
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

## Test

Install dependencies

```shell
composer i
```

Run test in the folder

```shell
./vendor/bin/phpunit tests
```

## License

The package is available as open source under the terms of the [MIT License](https://opensource.org/licenses/MIT).


<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->
[contributors-shield]: https://img.shields.io/github/contributors/smartpay-co/sdk-php.svg
[contributors-url]: https://github.com/smartpay-co/sdk-php/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/smartpay-co/sdk-php.svg
[forks-url]: https://github.com/smartpay-co/sdk-php/network/members
[stars-shield]: https://img.shields.io/github/stars/smartpay-co/sdk-php.svg
[stars-url]: https://github.com/smartpay-co/sdk-php/stargazers
[issues-shield]: https://img.shields.io/github/issues/smartpay-co/sdk-php.svg
[issues-url]: https://github.com/smartpay-co/sdk-php/issues
[license-shield]: https://img.shields.io/github/license/smartpay-co/sdk-php.svg
[license-url]: https://github.com/smartpay-co/sdk-php/blob/main/LICENSE
[packagist-shield]: https://img.shields.io/packagist/v/smartpay-co/sdk-php.svg
[packagist-url]: https://packagist.org/packages/smartpay-co/sdk-php
[php-shield]: https://img.shields.io/packagist/php-v/smartpay-co/sdk-php.svg?logo=php&logoColor=white
[php-url]: https://packagist.org/packages/smartpay-co/sdk-php
