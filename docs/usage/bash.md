---
title: Bash
weight: 12
---

You can debug your CLI scripts with the `node-ray-cli` package or simply send data to Ray from the command line.

### Running the application

`ray <command name> <args, ...>`

When calling commands that send modifiable payloads, the payload uuid is sent to stdout **if** the `--show-uuid` option flag is provided.  For example, you may modify the color of a payload after it has been sent by using the `color` command:

```bash
ray 'hello world' --show-uuid # writes "ae625128-ed3a-2b92-1a3f-2e0ebf7a2ad1" to stdout
ray color ae625128-ed3a-2b92-1a3f-2e0ebf7a2ad1 green
```

...or remove the payload from Ray entirely:
```bash
ray 'hello world' --show-uuid # writes "ae625128-ed3a-2b92-1a3f-2e0ebf7a2ad1" to stdout
ray remove ae625128-ed3a-2b92-1a3f-2e0ebf7a2ad1
```

Some other usage examples: 

```bash
ray 'hello world' --blue
ray pause
ray html '<em>hello world</em>'
ray file message.txt
```

## Disabling node-ray-cli

The `ray` command can be disabled by setting the `NODE_RAY_DISABLED` environment variable to `"1"`:

```bash
export NODE_RAY_DISABLED="1"
```

## Available option flags

There are several option flags that can be used with any command:

| Flag | Description |
| --- | --- |
| `--hide` | Display the payload as collapsed by default |
| `--if=value` | Don't send the payload if `value` is `"false"`, `0`, or `"no"` |
| `--large` | Display large text |
| `--show-uuid` | Write the payload uuid to stdout |
| `--small` | Display small text |
| `--blue` | Display the payload as blue  |
| `--gray` | Display the payload as gray  |
| `--green` | Display the payload as green  |
| `--orange` | Display the payload as orange  |
| `--purple` | Display the payload as purple  |
| `--red` | Display the payload as red  |

## Example Bash Script

```bash
#!/bin/bash

RAYUUID=$(ray "arg count: $#" --show-uuid)
ray color $RAYUUID blue

if [ $# -eq 0 ]; then
    echo "no filename provided"
    exit 1
fi

FILENAME="$1"

ray "$FILENAME"
ray file "$FILENAME" --purple --small --hide
ray show-app

if [ ! -e "$FILENAME" ]; then
    ray send "file missing: $FILENAME" --red
    exit 1
fi

ray pause

cat "$FILENAME" | wc -l
```
