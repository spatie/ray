---
title: Developing a Ray Library
weight: 2
---

The [Ray app](https://myray.app) is not a language-specific debugging app - as long as there's an integration library, it can be used with any language.

If you're interested in developing a Ray library for your language of choice, this document will help guide you through the process.  As an example, we'll be creating an example Ray integration library for Javascript/NodeJS; however, the concepts apply to any language.

Estimated time investment: `1 hour`
Required Tools: [Typescript](https://github.com/Microsoft/TypeScript), [ESBuild](https://github.com/evanw/esbuild), [ray-proxy](https://github.com/permafrost-dev/ray-proxy)
Companion Repository: [permafrost-dev/creating-a-ray-integration](https://github.com/permafrost-dev/creating-a-ray-integration)

# Creating a javascript integration for Ray

In this guide, we'll create a new javascript library that communicates with Ray.  There are already comprehensive third-party libraries in this space, such as [node-ray](https://github.com/permafrost-dev/node-ray), but this will serve well as an example.

## Goals

Create a javascript library that communicates with Ray that implements the following methods:
- `color()`
- `html()`
- `ray()`
- `charles()`
- `send()`
- `sendRequest()`

We'll also need to create the following payloads:
- `ColorPayload`
- `HtmlPayload`
- `LogPayload`
- `Payload`

## Getting Started

The [`spatie/ray`](https://github.com/spatie/ray) PHP package should be used as a reference - it is the primary library for Ray, and all new functionality is always added here first.  We'll reference its source code as we write our library.

Create a new directory named `ray-library-reference`, and run:

```bash
cd ./ray-library-reference
composer init
composer require spatie/ray
```

Next, create a PHP script for testing:

```php
<?php

// ray-test.php

require_once(__DIR__.'/vendor/autoload.php');

ray('test one')->color('red');
ray()->html('<strong>this is a bold</string>')->color('blue');
ray()->send('this is a test');
```

## Tools

To help determine what payload data is actually being sent to Ray from our test PHP script, we'll use the third-party [`permafrost-dev/ray-proxy`](https://github.com/permafrost-dev/ray-proxy) package to intercept and display all payloads being sent to Ray.

You'll first need to install the `ray-proxy` package:

```bash
mkdir ./ray-lib-app
cd ./ray-lib-app
npm install ray-proxy
```

When you're ready to start intercepting data, start Ray app and set the port to `23516`.  Then start the proxy:

```bash
node ./node_modules/.bin/ray-proxy
```

## Technology/Package choices

For the development, we'll use [TypeScript](https://www.typescriptlang.org/docs/) as the primary language and the `esbuild` package to compile and bundle our library.  

_Note that if you were creating this library to be consumed on a website via a `<script>` tag, using the `rollup` package would be a better choice._

We'll use the `superagent` npm package for sending data to Ray, and the `uuid` package for generating the required `UUIDv4` values for creating valid payloads.

```bash
cd ./ray-lib-app

npm install --save-dev typescript esbuild
npm install superagent uuid
```

Next, let's set up our project:

```bash
mkdir ./src
mkdir ./dist

touch ./dist/test.js
touch ./src/Origin.ts
touch ./src/Ray.ts
touch ./src/payloadUtils.ts
```

## Structure of a Payload

Let's start with the raw contents of a payload that is sent from your code to the Ray app.  You may view it in `ray-proxy` by running the following:

```bash
php ./ray-library-reference/ray-test.php
```

```json
{
  "uuid": "ca539a19-afd7-4c5e-8142-0d4b94512241",
  "payloads": [
    {
      "type": "custom",
      "content": {
        "content": "string 1 1615239018342",
        "label": "HTML"
      },
      "origin": {
        "function_name": "test",
        "file": "/home/user/projects/test-project/test.js",
        "line_number": 16,
        "hostname": "my-hostname"
      }
    }
  ],
  "meta": {
    "php_version": "7.4.16",
    "php_version_id": 70416,
    "ray_package_version": "1.20.1.0"
  }
}
```

Here we see that a payload has 3 parts: the `type`, the `content`, and the `origin`.  It appears that multiple payloads can be sent at once _(because the `payloads` property is an array)_.

Sent along with the payload is a `UUIDv4` and a `meta` object, which seems to contain the name and version of the Ray library we're using.

Since this is a basic walk though, we'll use hard-coded `Origin` data - data about where the call originated from - placed in `./src/Origin.ts` _(it is left to the reader to implement a working Origin class)_:

```typescript
// src/Origin.ts
export const OriginData = {
	function_name: 'my_test_func',
	file: 'my-file.js',
	line_number: 16,
	hostname: 'my-hostname',
};
```

Then create `./src/payloadUtils.ts`, which will contain helper functions for creating payloads:

```typescript
// src/payloadUtils.ts
import { v4  as  uuidv4 } from  'uuid';
import { OriginData } from './Origin';

export function createSendablePayload(payloads: any[] = [], uuid: string | null = null): any {
	uuid = uuid ?? uuidv4({}).toString();
	return { uuid, payloads, meta: { my_package_version: "1.0.0" } };
}

export function createPayload(type: string, label: string | undefined, content: any, contentName: string = 'content'): any {
	let result = {
		type: type,
		content: {
	        [contentName]: content,
	        label: label,
        },
        origin: OriginData,
	};
	
	if (result.content.label === undefined) {
		delete result.content['label'];
	}
	
	return result;
}

// change the color of a previously sent payload in Ray
export function createColorPayload(colorName: string, uuid: string | null = null) {
	const payload = createPayload('color', undefined, colorName, 'color');
	return createSendablePayload([payload], uuid);
}

// create an "HTML" payload to display custom HTML in Ray
export function createHtmlPayload(htmlContent: string, uuid: string | null = null) {
	const payload = createPayload('custom', 'HTML', htmlContent);
	return createSendablePayload([payload], uuid);
}

// create a "log" payload to display basic text in Ray
export function createLogPayload(text: string|string[], uuid: string | null = null) {
	const payload = createPayload('log', 'log', text);
	return createSendablePayload([payload], uuid);
}
```

Now, we'll need our main class - `./src/Ray.ts`:

```typescript
const  superagent  =  require('superagent');
import { createLogPayload, createColorPayload, createHtmlPayload } from './payloadUtils';

export class Ray {
	public uuid: string | null = null;
	
	public color(name: string): Ray {
		const payload = createColorPayload(name, this.uuid);		
		return this.sendRequest(payload);
	}
	
	public html(name: string): Ray {
		const payload = createHtmlPayload(name, this.uuid);		
		return this.sendRequest(payload);	
	}

	public send(...args: any[]): Ray {
		args.forEach(arg => {
			const payload = createLogPayload('log', null, arg);			
			this.sendRequest(payload);
		});
		
		return this;
	}

    public ban(): Ray {
	    return this.send('🕶');
    }
    
	public charles(): Ray {
	    return this.send('🎶 🎹 🎷 🕺');
    }
	
	public sendRequest(request: any): Ray {
		this.uuid = request.uuid;
        superagent.post(`http://localhost:23517/`).send(request)
	        .then(resp => { })
	        .catch(err => {});
	
		return this;
	}
}

export default Ray;
```

## Building the library

We'll be using `ESBuild` to compile our library.  The following command tells `ESBuild` to bundle all files into a single output file, that it will be run on the `node` platform _(instead of in a browser)_, to target node v12 as the minimum node version to support, and to treat the `superagent` npm package as external _(meaning it should not be packaged as part of our outfile)_.

```bash
./node_modules/.bin/esbuild --bundle \
  --target=node12 --platform=node \
  --format=cjs --external:superagent \
  --outfile=dist/index.js src/Ray.ts
```

if you'd like to add a shortcut, modify the `scripts` section in your `package.json` file to the following:
```json
  "scripts": {
    "build": "./node_modules/.bin/esbuild --bundle --target=node12 --platform=node --format=cjs --external:superagent --outfile=dist/index.js src/Ray.ts"
  },
```

Once saved, you may run the `npm run build` command instead of the `./node_modules/.bin/esbuild ...` command.

After running the build command you choose, you'll see that the file `./dist/index.js` exists.

Finally, we're ready to test our library.

## Testing the library

First, edit the file named `./dist/test.js`:

```javascript
// ./dist/test.js
const { Ray } = require('./index');
(new Ray()).html('<em>hello world</em>').color('red');
(new Ray()).send('hello world 2').color('blue');
```

Finally, make sure the Ray app is running and run the following command in your terminal:
```bash
node ./dist/test.js
```

With any luck, you'll see the message "_hello world_" with a red marker next to it in the Ray app - but not the _"hello world 2"_ blue message.

## Debugging Issues

If you were to test the `send()` method, you'd notice that nothing appears in the Ray app.  No problem, though - since we've got access to the PHP package as well as the `ray-proxy` app running, we can debug this in no time.

Here's what is being sent from our library to Ray, according to the proxy:

```json
{
  "uuid": "8bd2e386-e000-47b6-bd31-a655fd66376d",
  "payloads": [
    {
      "type": "log",
      "content": {
        "content": "log",
        "label": "log"
      },
      "origin": {
        "function_name": "my_test_func",
        "file": "my-file.js",
        "line_number": 16,
        "hostname": "my-hostname"
      }
    }
  ],
  "meta": {
    "my_package_version": "1.0.0"
  }
}
```

Run the following, to see what we should be sending:

```bash
php ./ray-library-reference/ray-test.php
```

And here's what SHOULD be sent _(relevant parts only)_:

```json
{
  ...
  "payloads": [
    {
      "type": "log",
      "content": {
        "values": [
          "Hello World!"
        ]
      },
	...
    }
  ],
  "meta": {
	...
  }
}
```

The issue appears to be with our `createLogPayload` function, so let's change a few things:

```typescript
export function createLogPayload(text: string|string[], uuid: string | null = null) {
	// make sure we pass an array of values
    if (!Array.isArray(text)) {
        text = [text];
    }
    // add the last parameter to send "content.values" in the payload
	const payload = createPayload('log', 'log', text, 'values');
	return createSendablePayload([payload], uuid);
}
```

And lastly, we need to update the `send()` method on the `Ray` class:

```typescript
public send(...args: any[]): Ray {
	const payload = createLogPayload(args, this.uuid);			
	this.sendRequest(payload);
	
	return this;
}
```

Let's compile again, and re-run our test script:

```bash
./node_modules/.bin/esbuild --bundle \
  --target=node12 --platform=node \
  --format=cjs --external:superagent \
  --outfile=dist/index.js src/Ray.ts

node dist/test.js
```

Success!  You should see two "hello world" messages, one red and one blue.

Let's make one more change to make using our library easier: adding a `ray()` function to `./src/Ray.ts`:

```typescript
// ... Ray class code here

export function ray(...args: any[]) {
    return (new Ray()).send(...args);
}

export default Ray;
```

You can now modify your test script to something like:

```typescript
const { ray } = require('./index');

ray('hello world').color('red');
ray().html('<strong>bold text</strong>');
```

That's it! Make sure to take a look at the [companion repository](https://github.com/permafrost-dev/creating-a-ray-integration) to check out the final project code.

> Don't forget to stop the `ray-proxy` app and change your Ray port back to 23517.

