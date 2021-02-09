---
title: NodeJS
weight: 8
---

## NodeJS configuration
In NodeJS based projects, you can optionally create a `ray.config.js` file in your project directory. It's recommended to put `ray.config.js` in your `.gitignore` so your fellow developers can use their own configuration.

You can use this template as [the ray config file](/docs/ray/v1/configuration/nodejs):

```js
// save this in a file named "ray.config.js"
module.exports = {
    /*
    * This settings controls whether data should be sent to Ray.
    */
    enable: true,

    /*
     *  The host used to communicate with the Ray app.
     */
    host: 'localhost',

    /*
     *  The port number used to communicate with the Ray app. 
     */
    port: 23517,
}
```

## Browser configuration

This section only applies if you are using `node-ray` in a browser environment _(webpack, etc.)_.

You can configure `node-ray` by importing the `Ray` class and calling the `useDefaultSettings()` method.

```js
const { Ray, ray } = require('node-ray');

// set several settings at once:
Ray.useDefaultSettings({ 
    host: '192.168.1.20',
    port: 23517 
});

// or set individual settings only:
Ray.useDefaultSettings({ port: 23517 });

// use ray() normally:
ray().html('<strong>hello world</strong>');
```

These settings persist across calls to `ray()`, so they only need to be defined once.

