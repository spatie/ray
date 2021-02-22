# Changelog

All notable changes to `ray` will be documented in this file

## 1.20.0 - 2021-02-22

- add `exception` method

## 1.19.5 - 2021-02-17

- allow instances of `CarbonInterface` to be used for `CarbonPayload` (#316)

## 1.19.4 - 2021-02-11

- fix enabled status (#301)

## 1.19.3 - 2021-02-09

- fix Client cache fingerprint initialization (#292)

## 1.19.2 - 2021-02-09

- add curl throttling after failed connection (#286)

## 1.19.1 - 2021-02-08

- allow symfony/stopwatch 4.0 (#284)

## 1.19.0 - 2021-02-03

- send XML payloads (#272)

## 1.18.0 - 2021-02-03

- add `enable` and `disable` methods

## 1.17.4 - 2021-02-03

- fix: remote_path/local_path replacements (#269)

## 1.17.3 - 2021-02-02

- use http v1.1 instead of 1.0 (#267)

## 1.17.2 - 2021-02-02

- cache config file

## 1.17.1 - 2021-01-27

- add support for PHP 7.3

## 1.17.0 - 2021-01-25

- add `showApp` and `hideApp`

## 1.16.0 - 2021-01-22

- add `phpinfo` method

## 1.15.0 - 2021-01-22

- add `table` method

## 1.14.1 - 2021-01-22

- fix bug when `remote_path` is also in `filePath` (#227)

## 1.14.0 - 2021-01-20

- Add support for CraftRay

## 1.13.0 - 2021-01-18

- the package will now select the best payload type when passing something to `ray()`
- added `html` method
- added `NullPayload`
- added `BoolPayload`

## 1.12.0 - 2021-01-18

- add `carbon`

## 1.11.1 - 2021-01-17

- lower deps

## 1.11.0 - 2021-01-15

- add `image()`

## 1.10.0 - 2021-01-15

- add `clearAll`

## 1.9.2 - 2021-01-15

- fix bugs around settings

## 1.9.1 - 2021-01-15

- improve helper functions

## 1.9.0 - 2021-01-15

- add `count`

## 1.8.0 - 2021-01-14

- add a check for YiiRay's instance

## 1.7.2 - 2021-01-13

- when passing `null`, let argument convertor return `null`

## 1.7.1 - 2021-01-13

- improve return type of ray function

## 1.7.0 - 2021-01-13

- support multiple arguments to `toJson()` and `json()` (#148)

## 1.6.1 - 2021-01-13

- prevent possible memory leak (#143)

## 1.6.0 - 2021-01-13

- add `file` function (#134)

## 1.5.10 - 2021-01-13

- allow better compatibility with WordPress

## 1.5.9 - 2021-01-13

- ignore package version errors

## 1.5.8 - 2021-01-13

- ignore package check errors

## 1.5.7 - 2021-01-13

- remove unneeded symfony/console dependency

## 1.5.6 - 2021-01-13

- allow lower dependencies

## 1.5.5 - 2021-01-11

- split origin factory in overridable functions

## 1.5.4 - 2021-01-11

- support WordPressRay

## 1.5.3 - 2021-01-10

- fix for traces of WordPress

## 1.5.2 - 2021-01-10

- colorize app frames

## 1.5.1 - 2021-01-10

- polish json functions

## 1.5.0 - 2021-01-09

- add `json` function

## 1.4.0 - 2021-01-09

- add `rd` function

## 1.3.7 - 2021-01-09

- add `vendor_frame` attribute to frames

## 1.3.6 - 2021-01-09

- allow older version of uuid package

## 1.3.5 - 2021-01-09

- fix search for `$indexOfRay` to include calls from the parent directory (#80)

## 1.3.4 - 2021-01-08

- prevent warning if `open_basedir` is enabled

## 1.3.3 - 2021-01-08

- do not require Composer 2

## 1.3.2 - 2021-01-08

- prevent ray from blowing up when there is no config file

## 1.3.1 - 2021-01-08

- do not blow up when the Ray app is not running

## 1.3.0 - 2021-01-08

- add support for `remote_path` and `local_path` config values

## 1.2.0 - 2021-01-08

- add `pass` function

## 1.1.3 - 2021-01-08

- prevent exception when installing in an Orchestra powered testsuite

## 1.1.2 - 2021-01-08

- enforce Composer 2 requirement

### 1.1.1 - 2021-01-08

- fix for repeated calls to `ray()` throwing an exception (#30)

## 1.1.0 - 2021-01-07

- add `makePathOsSafe`
- fix tests

## 1.0.1 - 2021-01-07

- fix default settings

## 1.0.0 - 2021-01-07

- initial release
