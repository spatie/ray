---
title: Vue
weight: 8
---

The third-party Vue package for Ray uses the [package for NodeJS](/docs/ray/v1/installation-in-your-project/nodejs) for 
most core functionality. See the [NodeJS reference](/docs/ray/v1/usage/nodejs) for a full list of available methods.

Once the plugin is installed, you may access the helper function as `this.$ray()` from within your Vue components.

### Component data

A component's data object can be displayed in Ray with the `data()` method.

```vue
<template>
    <div class="w-full">{{ msg }}</div>
</template>

<script>
export default {
    data() {
        return {
            msg: 'hello world'
        }
    },
    mounted() {
        this.$ray().data();
    }
}
</script>
```

### Component props

A component's props _(names and values)_ can be pretty-printed in Ray with the `props()` method.

```vue
<template>
    <div class="w-full">{{ msg }}</div>
</template>

<script>
export default {
    props: ['msg'],
    mounted() {
        this.$ray().props();
    }
}
</script>
```

### Displaying refs

A named ref's `innerHTML` contents can be sent to ray with the `ref()` method.


```vue
<template>
    <div ref="msg" class="w-full text-blue-500 p-5">
        <strong>hello world</strong>
    </div>
</template>

<script>
export default {
    mounted() {
        this.$ray().ref('msg');
    }
}
</script>
```

## Tracking component data

Changes to any of a component's data variables can be tracked and displayed in real time using the `track(name)` method.

```vue
<script>
export default {
    props: ['title'],
    data() {
        return {
            one: 100,
            two: 22,
        };
    },
    created() {
        this.$ray().data();
        this.$ray().track('one');
    },
    mounted() {
        setInterval( () => { this.one += 3; }, 4000);
    }
}
</script>
```

### Example component

```vue
<template>
    <div class="flex-col border-r border-gray-200 bg-white overflow-y-auto w-100">
        <div class="about">
            <h1>{{ title }}</h1>
            <a @click="sendToRay()">send ref to Ray</a><br>
            <a @click="incrementOne()">increment data var 'one'</a><br>
        </div>
        <div ref="div1" class="w-full flex flex-wrap">
            <div ref="div1a" class="w-4/12 inline-flex">one</div>
            <div ref="div1b" class="w-4/12 inline-flex">two</div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['title'],
    data() {
        one: 1,
    },
    created() {
        this.$ray().html('<em>test component created!</em>');
    },
    mounted() {
        this.$ray('test component mounted!');
        this.$ray().props();
        this.$ray().track('one');

        //if using vuex and the vue-ray vuex plugin, display vuex state changes:
        this.$store.commit('incrementOne');
    },
    methods: {
        sendToRay() {
            this.$ray().ref('div1');
        },
        incrementOne() {
            this.one += 1;
        },
    }
};
</script>
```
