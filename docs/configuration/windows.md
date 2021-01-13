---
title: Windows 10 using Homestead
weight: 3
---

Windows 10 does not support the PhpStorm protocol and therefore you will not be able to open PhpStorm given the link from Ray. 
As a work-around, @aik099 has a package to edits your registry to correlate the PhpStorm link protocol to your PhpStorm instance. 

Just goto [PhpStorm Protocol Package](https://github.com/aik099/PhpStormProtocol) and follow the directions. There is a 
chance you may need to reference this [issue](https://github.com/aik099/PhpStormProtocol/issues/32) if you are having 
problems getting it to work out of the box. However, be sure to update your run_editor.js to your current version of PhpStorm and everything should work.
