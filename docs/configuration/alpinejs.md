---
title: Alpine.js
weight: 9
---

### Configuration options

To configure `alpinejs-ray`, you must create an `alpineRayConfig` property on the `window` object before loading `alpinejs-ray`:

```html
<script>
    window.alpineRayConfig = {
        logComponentsInit: true,
        logCustomEvents: false,
    };
</script>

<!-- load axios and alpinejs-ray scripts here -->
```

| Name | Type | Default | Description |
| --- | --- | --- | --- |
| `logComponentsInit` | `boolean` | `false` | Send info on component initializations to Ray |
| `logCustomEvents` | `boolean` | `false` | Send info on custom events to Ray _(events with hyphenated names)_ |


