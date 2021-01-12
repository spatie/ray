---
title: Adding your own Ray functions in PHP
weight: 1
---

In all PHP projects, `ray` will proxy all calls to the `Spatie\Ray\Ray` class. This class is macroable: you can dynamically add your own functions to it.

Here's a silly example where the passed value will be displayed in uppercase in the Ray app.

```php
Spatie\Ray\Ray::macro('uppercase', function(string $value) {
    $uppercasedValue = strtoupper($value);
    
    $this->send($uppercasedValue);
    
    return $this;
});

ray()->uppercase('this string will be displayed uppercase in ray')
```

If you want to control the little label next to the item you should use `sendCustom` in your macro.

```php
Ray::macro('myCustomFunction', function() {
    $this->sendCustom('my custom content', 'hey');
});

ray()->myCustomFunction();
```

![screenshot](/docs/ray/v1/images/custom.png)




