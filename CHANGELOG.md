# Changelog

All notable changes to `ray` will be documented in this file

## 1.3.7 - 2020-01-09

add `vendor_frame` attribute to frames

## 1.3.6 - 2020-01-09

- allow older version of uuid package

## 1.3.5 - 2020-01-09

- fix search for `$indexOfRay` to include calls from the parent directory (#80)

## 1.3.4 - 2020-01-08

- prevent warning if `open_basedir` is enabled

## 1.3.3 - 2020-01-08

- do not require Composer 2

## 1.3.2 - 2020-01-08

- prevent ray from blowing up when there is no config file

## 1.3.1 - 2020-01-08

- do not blow up when the Ray app is not running

## 1.3.0 - 2020-01-08

- add support for `remote_path` and `local_path` config values

## 1.2.0 - 2020-01-08

- add `pass` function

## 1.1.3 - 2020-01-08

- prevent exception when installing in an Orchestra powered testsuite

## 1.1.2 - 2020-01-08

- enforce Composer 2 requirement

### 1.1.1 - 2020-01-08

- fix for repeated calls to `ray()` throwing an exception (#30)

## 1.1.0 - 2020-01-07

- add `makePathOsSafe`
- fix tests

## 1.0.1 - 2021-01-07

- fix default settings

## 1.0.0 - 2021-01-07

- initial release
