---
title: Alpine.js
weight: 11
---

The third-party Alpine.js [package](https://github.com/permafrost-dev/alpinejs-ray) for Ray uses the [package for NodeJS](/docs/ray/v1/installation-in-your-project/nodejs) for 
most core functionality. See the [NodeJS reference](/docs/ray/v1/usage/nodejs) for a full list of available methods.

Once the plugin is installed, you may access the helper function as `$ray()` from within your Alpine components.

## Example Component

```html
<div x-data="onClickData()">
    <div x-show="show">Hi There Ray!</div>
    <button x-on:click="toggle()">Show/Hide (Ray)</button>
    <button @click="$ray('hello from alpine')">Send to Ray</button>
</div>

<script>        
function onClickData() {
    return {
        init() {
            this.$ray().html('<strong>init on-click-ray data</strong>');
        },
        toggle() {
            this.show = !this.show;
            this.$ray('toggled show value to ' + (this.show ? 'true' : 'false'));
        },
        show: false,
    };
}
</script>
```

## Tracking Data Stores

You may automatically send Alpine stores to Ray whenever the store data is updated.  Consider the following:

```js
window.Alpine.store('mydata', {
    showing: false,
});
 
setInterval( () => {
    window.Alpine.store('mydata').showing = !window.Alpine.store('mydata').showing;
}, 3000);
```

To watch the store and display changes in Ray, use the `$ray().watchStore('name')` method:

```html
<div x-data="componentData()">
    <div x-show="$store.mydata.showing">Hi There Ray!</div>
    <button x-on:click="toggle()">Show/Hide (Ray)</button>
</div>

<script>      
window.Alpine.store('mydata', {
    showing: false,
});
  
function componentData() {
    return {
        init() {
            this.$ray().watchStore('mydata');
        },
        toggle() {
            this.$store.mydata.showing = !this.$store.mydata.showing;
        },
    };
}
</script>
```
