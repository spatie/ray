---
title: Express.js
weight: 12
---

The third-party [Express.js package](https://github.com/permafrost-dev/express-ray) for Ray uses the [package for NodeJS](/docs/ray/v1/installation-in-your-project/nodejs) for 
most core functionality. See the [NodeJS reference](/docs/ray/v1/usage/nodejs) for a full list of available methods.

Once the plugin is installed, you may access the helper function as `app.$ray()` from within your Express application.


```js
app.get('/', (req, res) => {
    app.$ray('sending "hello world" response');
    res.send('hello world');
});
```

### SendRequestToRay Middleware

Send details about each request to Ray with the `SendRequestToRay` middleware, optionally specifying configuration settings.

```ts
interface SendRequestToRayOptions {
    paths?: {
        include?: string[];
        ignore?: string[];
    }
}
```

By default, all paths match and get sent to Ray. Both the `paths.include` and `paths.ignore` configuration settings support wildcards.

```js
import { middleware } from 'express-ray';

app.use(
    middleware.SendRequestToRay({ paths: { include: ['*'], ignore: ['*.css'] } })
);
```

All configuration settings for this middleware are optional:

```js
app.use(middleware.SendRequestToRay());
```

### SendErrorToRay Middleware

To send errors directly to Ray, use the `SendErrorToRay` middleware.

```js
import { middleware } from 'express-ray';

// <express setup code here>

// register the middleware just before listen()
app.use(middleware.SendErrorToRay);

app.listen(port, () => {
    console.log(`Listening on port ${port}`);
});
```