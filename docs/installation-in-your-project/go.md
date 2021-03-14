---
title: Go
weight: 10
---



## Install

When using Go Modules, you do not need to install anything to start using Ray with your Go program. Import the module
and the go will automatically download the latest version of the module when you next build your program.

```go
import (
    "github.com/octoper/go-ray"
)
```

With or without Go Modules, to use the latest version of the SDK, run:

`go get github.com/octoper/go-ray`

Consult the [Go documentation on Modules](https://github.com/golang/go/wiki/Modules#how-to-upgrade-and-downgrade-dependencies) for more information on how to manage dependencies.