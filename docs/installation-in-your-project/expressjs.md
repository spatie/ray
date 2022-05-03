---
title: Express.js
weight: 12
---

You can send information from Express.js applications to Ray via this third party package:

[permafrost-dev/express-ray](https://github.com/permafrost-dev/express-ray)

### Installing the package

You can install this third-party package using either `npm` or `yarn`:

```bash
npm install express-ray

yarn add express-ray
```

### Installing the plugin

To install the `express-ray` plugin into your Express.js application, call the `install` method provided by the `plugin` import:

```js
import { plugin as expressRayPlugin } from 'express-ray';

const app = express();

expressRayPlugin.install(app);
```