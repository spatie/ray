---
title: Alpine.js
weight: 9
---

### Configuration Options

To configure `alpinejs-ray`, you must create an `alpineRayConfig` property on the `window` object before loading `alpinejs-ray`:

```html
<script>
    window.alpineRayConfig = {
        logComponentsInit: true,
        logErrors: true,
        logEvents: ['abc'],
    };
</script>

<!-- load axios and alpinejs-ray -->
```

### Configuration Reference

| Name | Type(s) | Default | Description |
| --- | --- | --- | --- |
| `logComponentsInit` | `boolean` | `false` | Send info on component initializations to Ray |
| `logErrors` | `boolean` | `false` | Send javascript errors to Ray instead of the console |
| `logEvents` | `boolean, array` | `false` | Send specified custom events to Ray, or `false` to disable |
