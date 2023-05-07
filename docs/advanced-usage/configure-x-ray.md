---
title: Configure X-Ray to detect ray calls
weight: 2
---

Ray provides a first-party package named [x-ray](https://github.com/spatie/x-ray), allowing you detect remaining ray calls in your application.

The primary use case is when calls to ray() cannot be left in source code before deploying, even if ray is disabled. This package does NOT remove the calls, it simply displays their locations so they can be removed manually.

The exit code of the x-ray command is zero if no ray calls are found, and non-zero if calls are found. This allows the package to be used in an automated environment such as Github Workflows.

## Installation

```bash
composer require spatie/x-ray --dev
```

## Usage

Specify one or more valid path names and/or filenames to scan:

```bash
./vendor/bin/x-ray ./app/Actions/MyAction.php ./app/Models/*.php ./tests --snippets
```

Display a summary table of the located calls within `./src` and `./tests` while also ignoring some files:

```bash
./vendor/bin/x-ray \
  --summary \
  --ignore src/MyClass.php \
  --ignore 'test/fixtures/*.php' \
  ./src ./tests
```

Display each filename & pass/fail status, along with compact results:

```bash
./vendor/bin/x-ray ./app --compact --verbose
```

## Available Options

| Flag | Description
|---|---|
|`--compact` or `-c` | Minimal output.  Display each result on a single line. |
|`--github` or `-g` | GitHub Annotation output.  Use `error` command to create annotation. Useful when you are running x-ray within GitHub Actions. |
|`--ignore` or `-i` | Ignore a file or path, can be specified multiple times. Accepts glob patterns. |
|`--no-progress` or `-P` | Don't display the progress bar while scanning files |
|`--snippets` or `-S` | Display code snippets from located calls |
|`--summary` or `-s` | Display a summary of the files/calls discovered |
|`--verbose` or `-v` | Display each filename and pass/fail status while scanning. Implies `--no-progress`. |

See the complete documentation with examples, including Github Workflow Action examples, at [spatie/x-ray](https://github.com/spatie/x-ray).
