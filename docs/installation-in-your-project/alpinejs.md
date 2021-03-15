---
title: Alpine.js
weight: 11
---

You can send information from Alpine.js to Ray via this third party package:

[permafrost-dev/alpinejs-ray](https://github.com/permafrost-dev/alpinejs-ray)

### Installation via CDN

The preferred way to use this package is to load it via a CDN.  You'll need to load the `axios` library as well:

```html
<script src="https://cdn.jsdelivr.net/npm/axios@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs-ray@latest/dist/standalone.min.js"></script>
```

### Installation with package manager

Install with npm:

```bash
npm install alpinejs-ray
```

or yarn:

```bash
yarn add alpinejs-ray
```

#### Importing the plugin

Although not the recommended way, you can import package normally if installed with a package manager _(along with `node-ray`, `alpinejs` and `axios`)_:

```js 
import { Ray, ray } from 'node-ray/web';
import Alpine from 'alpinejs';
import AlpineRayPlugin from 'alpinejs-ray';

window.ray = ray;
window.Ray = Ray;
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Alpine = Alpine;
window.AlpineRayPlugin = AlpineRayPlugin;
window.AlpineRayPlugin.init();
window.AlpineRayPlugin.start();
```
