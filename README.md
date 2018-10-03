# Yii2 Google Autocomplete Api Integrate

## Installation

Use [composer](https://packagist.org) to install the package:

```bash
composer require wearesho-team/yii2-google-autocomplete
```

## Usage

### Bootstrap

Add to your `common/config/main.php`:

```php
<?php

use Wearesho\GoogleAutocomplete;

$config = [
    'bootstrap' => [
        'googleAutocomplete' => [
            'class' => GoogleAutocomplete\Yii\Bootstrap::class
        ],
    ]
];
```

### Controller

Add to your `api/config/main.php`:

```php
<?php

use Wearesho\GoogleAutocomplete;

$module = [
    'controllerMap' => [
        'autocomplete' => [
            'class' => GoogleAutocomplete\Yii\Controller::class,
        ],
    ]
];
```

## Contributors

- [Roman <KartaviK> Varkuta](mailto:roman.varkuta@gmail.com)

## License

Unlicensed
