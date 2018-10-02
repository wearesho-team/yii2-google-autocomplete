# Yii2 Google autocomplete api integrate

## Usage

### Bootstrap

Add to your `common/config/main.php`:

```php
<?php

$config = [
    'bootstrap' => [
        'googleAutocomplete' => [
            'class' => Wearesho\GoogleAutocomplete\Yii\Bootstrap::class
        ],
    ]
];
```

### Controller

Add to your `api/config/main.php`:

```php
<?php

$module = [
    'controllerMap' => [
        'autocomplete' => [
            'class' => \Wearesho\GoogleAutocomplete\Yii\Controller::class,
        ],
    ]
];
```

## Authors

- [Roman <KartaviK> Varkuta](mailto:roman.varkuta@gmail.com)

## License

Unlicensed
