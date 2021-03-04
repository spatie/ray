---
title: Discovering the Ray app
weight: 2
---

[Ray](https://myray.app) is a very simple app to use. You can use any of the `ray` calls that are documented in [the usage section](/docs/ray/v1/usage/introduction).

![screenshot](/docs/ray/v1/images/empty.jpg)

## Clearing the screen

When debugging, you probably will need a couple of attempts to find the source of the bug. For each attempt, you could opt to use a new screen, so you will only see the output of your current attempt. There are three ways for creating a new screen.

1. Clicking the `+` button in the menubar
2. By executing `ray()->newScreen()` somewhere in your code. Optionally, you can pass a screen name as an argument to `newScreen`.
3. By pressing `cmd+K` on Mac or `ctrl+K` on Windows when the Ray app is active.  When the Ray app is not the active one, press `cmd+shift+K on Mac or `ctrl+shift+K` on Windows to clear the screen.

## Using color filters

You can give an item a color using one of the [color functions](/docs/ray/v1/usage/framework-agnostic-php-project#using-colors). On top of the screen you can turn on a color filter to only see items with a specific color.

## Keep Ray on top

If you want Ray always to be on top, just toggle on the `Keep on top` toggle on the menubar. In our experience, this is pretty handy to make sure that Ray is always visible, event when switching applications or workspaces.

## Cleaning up after yourself

At the bottom of every item that displayed in Ray, you'll see a link that, when clicked, will take you to where this item was sent to ray. You can use this to, after debugging, quickly find locations where `ray()` calls are made, so you can remove them.

![screenshot](/docs/ray/v1/images/clean.jpg)

When a Ray screen is displaying many items, it might be bothersome to click each item. In this case, you can click "List files" to get a unique list of locations where a Ray call is in your source code.

![screenshot](/docs/ray/v1/images/list-files.jpg)

Should you forget to remove a `ray()` call in a Laravel app, and push your code to production, no worries. Ray will not try to transmit any info in a production environment.

## Hiding the app using the hotkey

You can press `cmd+shift+L` to hide or show the app, no matter which application is the active on.

You can customize this key in the preferences.

![screenshot](/docs/ray/v1/images/hotkeys.jpg)

## Connecting to remote servers

When connected to a remote server, Ray will display the output of `ray` calls that happen on your remote server. The output is transferred securely from to server to your local machine via SSH.

To get started, click the "Servers" icon on the top right of the Ray app

On this screen you can define a server.

![screenshot](/docs/ray/v1/images/define-server.png)

When the server is defined, click the connect button.

![screenshot](/docs/ray/v1/images/connect.png)

The output of any `ray` calls on the remote server will now be shown.

![screenshot](/docs/ray/v1/images/remote-call.png)



