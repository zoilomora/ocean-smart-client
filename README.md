# Ocean Smart Client
This library helps to communicate with **OceanSmart** software.

Tested with **[Ocean Smart 1.4.0603.1](https://oceansoftware.es/)**.

## Usage
To facilitate the use with several users, a factory is included to start the session.

Below is an example of use:

```php
<?php
require __DIR__ . '/../vendor/autoload.php';

$factory = new \ZoiloMora\OceanSmartClient\ClientFactory(
    new \GuzzleHttp\Client(
        [
            'base_uri' => BASE_URI,
            // ... specific user settings
        ]
    )
);

$client = $factory->build(USER, PASSWORD);

$markings = $client->markings(
    new DateTime('2019-06-01'), // from
    new DateTime('2019-07-01')  // to
);
```

## Notes
For now, raw array of API responses are returned, they are not transformed into mapped objects.
