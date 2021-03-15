---
title: Alpine.js
weight: 11
---

The third-party Alpine.js [package](https://github.com/permafrost-dev/alpinejs-ray) for Ray uses the [package for NodeJS](/docs/ray/v1/installation-in-your-project/nodejs) for 
most core functionality. See the [NodeJS reference](/docs/ray/v1/usage/nodejs) for a full list of available methods.

Once the plugin is installed, you may access the helper function as `$ray()` from within your Alpine components.

## Example Component

```html
<div x-data="onClickData()" x-init="init()">
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

## Tracking Spruce Data Stores

Spruce data store are automatically tracked if [Spruce](https://github.com/ryangjchandler/spruce) is installed.  Consider the following:

```js
window.Spruce.store('mydata', {
    showing: false,
    toggle() {
        this.showing = !this.showing;
        ray().html('<strong>[spruce]</strong> showing = ' + this.showing);
    }
});
 
setInterval( () => {
    window.Spruce.stores.mydata.showing = !window.Spruce.stores.mydata.showing;
}, 3000);
```
