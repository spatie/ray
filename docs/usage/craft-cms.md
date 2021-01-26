---
title: Craft CMS
weight: 6
---

Inside a Craft CMS project, you can use all methods from [the Yii version](/docs/ray/v1/usage/yii2).

Additionally, you can use these additional methods.

### Using Ray in Twig templates

You can use the `{{ ray }}` global variable to easily send variables to Ray from inside a Twig view. You can pass as many things as you'd like.

```twig
{{ ray(variable, anotherVariable) }}
```

You can also use a filter

```twig
{{ myVariable | ray }}
```

The Ray methods are also available on the global variable

```twig
{{ ray.clearScreen }}
```
