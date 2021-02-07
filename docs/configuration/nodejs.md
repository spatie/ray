---
title: NodeJS
weight: 8
---

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
