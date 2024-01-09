---
title: Automatically remove ray calls
weight: 1
---

To avoid shipping your code with some ray calls in it you can automate the removal of all ray calls in your codebase.

## Rector
If you are already using [Rector](https://getrector.com/) this can be simply done by adding a rule to your `rector.php` config file:

```php
use Spatie\Ray\Rector\RemoveRayCallRector;

$rectorConfig->rule(RemoveRayCallRector::class);
```
