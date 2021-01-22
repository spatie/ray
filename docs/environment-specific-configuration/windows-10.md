---
title: Windows 10
weight: 3
---

## PhpStorm

Windows 10 does not support the PhpStorm protocol and therefore you will not be able to open PhpStorm given the link from Ray. 
As a work-around, @aik099 has a package to edits your registry to correlate the PhpStorm link protocol to your PhpStorm instance. 

Just goto [PhpStorm Protocol Package](https://github.com/aik099/PhpStormProtocol) and follow the directions. There is a 
chance you may need to reference this [issue](https://github.com/aik099/PhpStormProtocol/issues/32) if you are having 
problems getting it to work out of the box. However, be sure to update your run_editor.js to your current version of PhpStorm and everything should work.

## WSL2

When using WSL2 on Windows 10 we need to consider first how this technology works: essentially, the Linux running on WSL2, is an optimized VM runing on Hyper-V, but it is a "full-blown" Linux OS independent from your host that has some specific kernel changes to make things a bit smoother. Knowing this, we need to setup the [ray](https://github.com/spatie/ray) package accordingly to connect to the app.

Our Windows 10 host and our WSL2 Linux each have its own network configuration. What we need to achieve is that WSL2 (which is running our code) is able to send the ray debug events to the Windows 10 host. First off we need to know that WSL2 changes its IP address segment on each launch (each Windows 10 reboot) because that's how it works in Hyper-V, but it also configures automatically the routing necessary to reach our physical network segment (Windows 10 IP address segment) from within WSL2 for us.

There are 3 ways to reach the ray app from within WSL2 that is running our code. The first two are the recommended ways as you are required to configure them only once:
1. Set a static IP address manually for our Windows 10 host.
2. Set a DHCP reservation for our Windows 10 host on our DHCP server.
3. Reconfigure the ray package each time we boot our Windows 10 host and WSL2 to set `$windows_10_ip_addr` as the WSL2 gateway IP.

In all of them what you need to do is set the following configuration in ray:

```php
'host' => $windows_10_ip_addr,      // this could be the windows 10 IP addres (1. and 2.) or the WSL2 gateway IP address (3. which you would have to change on each reboot)

// this entries are to enhance ray by allowing to open the files directly in your editor
'remote_path' => $path_to_code_inside_wsl2,     // usually something like /home/spatie/code/my-project
'local_path' => $path_to_code_to_wsl2_in_windows,       // usually something like \\\\wsl$\\Ubuntu\\home\\spatie\\code\\my-project      <- don't forget to escape the \ if using double-quotes like in the example
```

_Also note that you could run into issues with the Windows firewall or your antivirus software that could block the network communication between your WSL2 VM and your Windows 10 host, so check first if nothing is being blocked if you do not succeed at first to send ray debug events to the app.__
