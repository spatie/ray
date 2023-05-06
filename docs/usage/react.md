---
title: React
weight: 8
---

The third-party React package for Ray, `react-ray`, uses the [package for NodeJS](/docs/ray/v1/installation-in-your-project/nodejs) for most core functionality.

`react-ray` supports React 16+ and provides two hooks:

- `useRay` - send data to the Ray app whenever it updates.
- `useRayWithElement` - send the contents of an element ref to the Ray app, optionally updating the item in place when its dependencies change.

### `useRay()`

To send data to Ray whenever it updates, use the `useRay` hook along with the `type` option to specify the type of data you are sending.  
The `boolean` `replace` option can be used to update the Ray item in place when its dependencies change.  The default value for `replace` is `false`.

Valid types are `image`, `json`, `html`, `text`, or `xml`. See the [`node-ray` documentation](https://github.com/permafrost-dev/node-ray) for more information on these types.

```js
import { useRay } from 'react-ray';
import { useEffect, useState } from 'react';

const MyComponent = () => {
    const [count, setCount] = useState(0);

    useRay(count, { type: 'text', replace: true });

    return (
        <button onClick={() => setCount(count + 1)}>
            Click me
        </button>
    );
};
```

### `useRayWithElement()`

To send the contents of a ref to the Ray app in a sequential manner (each dependency change sends a new item), set the `replace` option to `false`:

```js
import { useRayWithElement } from 'react-ray';
import { useRef, useState } from 'react';

const MyComponent = () => {
    const [count, setCount] = useState(0);
    const countRef = useRef(null);

    useRayWithElement(countRef, [count], { replace: false });

    return (
        <div>
            <div ref={countRef}>{count}</div>
            <button onClick={() => setCount(count + 1)}>
                Click me
            </button>
        </div>
    );
};
```

To update the Ray item in place that was sent with the contents of a ref when its dependencies change, set the `replace` option to true or omit it:

```js
import { useRayWithElement } from 'react-ray';
import { useRef, useState } from 'react';

const MyComponent = () => {
    const [count, setCount] = useState(0);
    const countRef = useRef(null);

    useRayWithElement(countRef, [count], { replace: true });
    // or
    // useRayWithElement(countRef, [count]);

    return (
        <div>
            <div ref={countRef}>{count}</div>
            <button onClick={() => setCount(count + 1)}>
                Click me
            </button>
        </div>
    );
};
```
