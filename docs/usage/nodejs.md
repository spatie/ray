---
title: NodeJS
weight: 7
---

The API for the NodeJS package closely mirrors the official `spatie/ray` package API, so it's likely that if it exists there, it's available to use in your NodeJS project.

### Importing the package

When working with NodeJS, import the package as you would normally:

```js 
// es module import:
import { ray } from 'node-ray';

// commonjs import:
const { ray } = require('node-ray');
```

When creating a bundle for use within a browser-based environment _(i.e. with webpack)_, import the `/web` variant:

```js 
// es module import:
import { ray } from 'node-ray/web';

// commonjs import:
const { ray } = require('node-ray/web');
```

To use `node-ray` directly in a webpage, include the standalone umd-format script via CDN. The standalone version is bundled with everything _except_ the axios library, which must be included separately and before the standalone script.

```html
    <script src="https://cdn.jsdelivr.net/npm/axios@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/node-ray@latest/dist/standalone.js"></script>
    <script>
        window.ray = Ray.ray;
        window.Ray = Ray.Ray;
    </script>
```

### Enabling and disabling

Ray can be enabled or disabled at runtime using `enable()` or `disable()`.

```js
ray('this is sent');
ray().disable();
ray('this is not sent');
ray().enable();
ray('this is also sent');
```

### Working with screens

Ray can create or clear new screens of information.

```js
ray().newScreen();

ray().newScreen('my new screen');

ray().clearScreen();

ray().clearAll();
```

![screenshot](/docs/ray/v1/images/screen.jpg)

### App visibility

The Ray app can be shown or hidden programmatically.

```js
ray().showApp();

ray().hideApp();
```

### Payload types

Ray can display payloads of several types, including JSON, file contents, images, XML, and HTML.

```js
ray().json('['a' => 1, 'b' => ['c' => 3]]');

ray().toJson({name: 'my object'});

ray().file('test.txt');

ray().image('https://placekitten.com/200/300');

ray().html('<strong>hello</strong> world');

ray().xml('<one><two><three>3</three></two></one>');
```

![screenshot](/docs/ray/v1/images/json.png)

![screenshot](/docs/ray/v1/images/xml.png)

### Using colors

Ray can display the data you send with different colors.

Available colors:
- green
- orange
- red
- blue
- purple
- gray

```js
ray('this is green').green();

ray('this is purple').purple();
```
![screenshot](/docs/ray/v1/images/colors.jpg)

### Using sizes

Ray can display stuff in large, normal, or small text.

```js
ray('small').small();

ray('regular');

ray('large').large();
```

![screenshot](/docs/ray/v1/images/sizes.jpg)

### Displaying tables

Ray can display an object formatted in a table.  Complex items such as arrays and objects are pretty-printed and highlighted to make their contents pleasant to read.

```js
ray().table({
    First: 'First value',
    Second: 'Second value',
    Third: 'Third value',
});
```

![screenshot](/docs/ray/v1/images/table.png)

An array of items can also be displayed with formatted values.

```js
ray().table(['John', 'Paul', 'George', 'Ringo'], 'Beatles');
```

![screenshot](/docs/ray/v1/images/table-label.png)

### Counting

Ray can count the number of times a piece of code is called, optionally with a specific name.

```js
for(const n in [...Array(12).keys()]) {
    ray().count('first');
}

[1, 2].forEach(n => ray().count());
```
![screenshot](/docs/ray/v1/images/named-count.jpg)

### Reset counters

Counter values persist across multiple calls to `ray()`.  Reset all counters with `clearCounters()`:

```js
ray().count('first');
ray().count('first');
console.log(Ray.counters.get('first'); // displays '2'

ray().clearCounters();
console.log(Ray.counters.get('first'); // displays '0'
```

### Conditionally showing items

You can conditionally show things using the `showIf` and `showWhen` methods. If you pass a truthy value, the item will be displayed.

You may also pass a callback that returns a boolean value.

```js
ray('will be shown').showIf(() => true);

ray('will be shown').showWhen(true);

ray('will not be shown').showIf(false);

ray('will not be shown').showWhen(() => false);
```

### See the caller of a function

Sometimes you want to know where your code is being called. You can quickly determine that by using the `caller`
function.

```js
ray().caller();
```

![screenshot](/docs/ray/v1/images/caller.jpg)

If you want to see the entire stack trace, use the `trace` function.

```js
ray().trace();
```

![screenshot](/docs/ray/v1/images/trace.jpg)

### Pausing code execution

Ray can pause code execution within async code.

```js
async function test() {
    ray('before pausing');

    await ray().pause();

    ray('after resuming');
});

test();
```

![screenshot](/docs/ray/v1/images/pause.jpg)


### Displaying a notification

You can use Ray to display a notification.

```js
ray().notify('This is my notification');
```

![screenshot](/docs/ray/v1/images/notification.jpg)


### Working with errors

Ray can display information about an `Error` or exception with the `error` method.

```js
ray().error(new Error('my error message'));
```

### Displaying class information

You can display the classname of an object with `className()`.

```js
const obj = new MyClass1();

ray().className(obj);
```

### Working with dates

Ray can display information about a date in a nicely formatted table using the `date()` method.
Specifying the format is optional. It uses the [dayjs formatting](https://day.js.org/docs/en/display/format) style.

```js
ray().date(new Date(), 'YYYY-MM-DD hh:mm:ss');
```

![screenshot](/docs/ray/v1/images/carbon.jpg)


### Measuring performance

You can use the `measure` function to display runtime and memory usage. When `measure` is called again, the time between
this and previous call is also displayed.

```js
const sleep = (seconds) => {
    const start = new Date().getTime();
    while (new Date().getTime() < start + (seconds * 1000)) { }
};

ray().measure();

sleep(1);

ray().measure();

sleep(2);

ray().measure();
```

![screenshot](/docs/ray/v1/images/measure.jpg)

The `measure` call optionally accepts a callable. Ray will output the time needed to run the callable and the maximum
memory used.

```js
const sleep = (seconds) => {
    const start = new Date().getTime();
    while (new Date().getTime() < start + (seconds * 1000)) { }
};

ray().measure(() => {
    sleep(5);
});
```

The `stopTime` method can remove a stopwatch if you've previous called `measure()` with a name:
```js

ray().measure('my timer');

sleep(1);

ray().measure('my timer');

ray().stopTime('my timer');
```

Calling `stopTime()` without specifying a name will delete all existing stopwatches.

### Showing events

You can information about an event that has executed by calling `event(name, data)`, with `data` being optional.

```js
ray().event('TestEvent', ['my argument']);
```

![screenshot](/docs/ray/v1/images/event.jpg)

### Feature demo

Here's a sample script that demonstrates a number of the features, both basic and advanced.

Save as `demo.js`, and run with `node demo.js`:

```js
const { ray } = require('node-ray');

function test1() { 
    return ray().count('test one');
}

function test2() {
    return ray().count();
}

async function alternatingColorCounter() {
    return new Promise(resolve => {
        const myRay = ray();

        for(const i in [...Array(10).keys()]) {
            setTimeout( () => {
                const colorName = ['green', 'red', 'blue', 'orange'][i % 4];

                myRay.html(`counter: <strong>${i + 1}</strong>`).color(colorName);

                if (i === 9) {
                    resolve(true);
                }
            }, i * 1500);
        }
    });
}

async function main() {
    ray().showApp();

    ray().xml('<one><two><three>3333</three></two></one>');

    ray().table(['a string', true, [1, 2, 3], {a:1, b:2}]);

    ray().sendCustom({ name: 'object' });

    await alternatingColorCounter();

    await ray().pause();

    [1,2].forEach(n => test1());

    [1,2,3,4].forEach(n => test2());

    ray().image('https://placekitten.com/200/300').blue();

    ray().html('<strong>hello world</strong>').small();
}

main();
```
