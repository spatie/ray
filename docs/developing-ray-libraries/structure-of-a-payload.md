---
title: Structure of a payload
weight: 2
---

Every time any data is sent to Ray, a payload is generated with the same basic structure before being sent to the Ray app.

If you run the following test PHP script, you'll get the payload example below sent via `HTTP POST` to the Ray app.

```php
ray()->html('<em>hello world!</em>');
```

## Payload example 

```json
{
  "uuid": "ca539a10-bfd5-3e5a-6271-0c4a95612132",
  "payloads": [
    {
      "type": "custom",
      "content": {
        "content": "<em>hello world!</em>",
        "label": "HTML"
      },
      "origin": {
        "function_name": "test",
        "file": "/home/user/projects/test-project/test.php",
        "line_number": 16,
        "hostname": "my-hostname"
      }
    }
  ],
  "meta": {
    "php_version": "7.4.16",
    "php_version_id": 70416,
    "ray_package_version": "1.20.1.0"
  }
}
```

## Payload sections overview

> All of the listed payload sections are **required** when sending a payload from an integration library.

- The `"uuid"` section contains a valid `UUIDv4` value.  This value is important, as it can be used in future to modify the payload after it's been sent to Ray _(i.e., changing its color)_.
- The `"meta"` section contains metadata about the Ray integration library as well as the current language and its version.
- The `payloads[0].origin` section contains information about where the call to `ray()` originated from.  It's used to tell Ray what file to open when the related file link is clicked.
- The `payloads[0].type` value contains the type of payload being sent.
- The `payloads[0].content` value contains the payload-specific content to display within the Ray app.  This varies depending on the type of payload.

