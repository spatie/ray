---
title: Adding your own function to Ray
weight: 2
---

`ray` will proxy all calls to the `Spatie\Ray\Ray` class. This class is macroable: you can dynamically add your own functions to it.

Here's a silly example where the passed value will be displayed in uppercase in the Ray app.

```php
Spatie\Ray\Ray::macro('uppercase', function(string $value) {
    $uppercasedValue = strtoupper($value);

    $payload = new Spatie\Ray\Payloads\LogPayload($uppercasedValue);
    
    $this->sendRequest($payload);
    
    return $this;
});
```



