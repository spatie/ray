---
title: Linux
weight: 3
---

When using the Linux AppImage, the automatic updates rename the binary with the latest version _(i.e., Ray-1.12.0.AppImage)_.

Instead of symlinking the binary directly, you can use the following script to always run the latest version of Ray by placing it in the same directory as the Ray binary:



__ray-latest.sh__

```bash
#!/bin/bash

THIS_DIR=$(realpath `dirname $0`)
LATEST_BINARY=$(ls -1 $THIS_DIR/Ray-*.*.AppImage | sort -r | head -n 1)

$LATEST_BINARY
```


