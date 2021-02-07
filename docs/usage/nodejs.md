---
title: NodeJS
weight: 7
---

The API for the NodeJS package closely mirrors the official `spatie/ray` package API, so it's likely that if it exists there, it's available to use in your NodeJS project.

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
ray().json('{"name": "John"}');

ray().toJson({name: 'my object'});

ray().file('test.txt');

ray().image('https://placekitten.com/200/300');

ray().html('<strong>hello</strong> world');

ray().xml('<one><two>22</two></one>');
```

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

Ray can display an array of items formatted in a table.  Complex items such as arrays and objects are pretty-printed and highlighted to make their contents pleasant to read.

```js
ray().table(['hello world', true, [1, 2, 3], {name: 'John', age: 32}]);
```

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
