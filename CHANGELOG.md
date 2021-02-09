# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 4.2.0 - TBD

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 4.1.0 - 2021-02-09



-----

### Release Notes for [4.1.0](https://github.com/nucleos/NucleosSitemapBundle/milestone/1)



### 4.1.0

- Total issues resolved: **0**
- Total pull requests resolved: **3**
- Total contributors: **1**

#### dependency

 - [138: Add support for PHP 8](https://github.com/nucleos/NucleosSitemapBundle/pull/138) thanks to @core23
 - [68: Drop support for PHP 7.2](https://github.com/nucleos/NucleosSitemapBundle/pull/68) thanks to @core23

#### Feature Request

 - [59: Move configuration to PHP](https://github.com/nucleos/NucleosSitemapBundle/pull/59) thanks to @core23

## 4.0.0

### Changes

* Renamed namespace `Core23\SitemapBundle` to `Nucleos\SitemapBundle` after move to [@nucleos]

  Run

  ```
  $ composer remove core23/sitemap-bundle
  ```

  and

  ```
  $ composer require nucleos/sitemap-bundle
  ```

  to update.

  Run

  ```
  $ find . -type f -exec sed -i '.bak' 's/Core23\\SitemapBundle/Nucleos\\SitemapBundle/g' {} \;
  ```

  to replace occurrences of `Core23\SitemapBundle` with `Nucleos\SitemapBundle`.

  Run

  ```
  $ find -type f -name '*.bak' -delete
  ```

  to delete backup files created in the previous step.


## 3.2.0

### Changes

- Add missing strict file header [@core23] ([#31])
- Use default public setting for all actions [@core23] ([#15])

### 📦 Dependencies

- Add support for symfony 5 [@core23] ([#20])
- Drop support for symfony 3 [@core23] ([#27])
- Remove cache-adapter (dev) dependency [@core23] ([#32])

[#32]: https://github.com/nucleos/NucleosSitemapBundle/pull/32
[#31]: https://github.com/nucleos/NucleosSitemapBundle/pull/31
[#27]: https://github.com/nucleos/NucleosSitemapBundle/pull/27
[#20]: https://github.com/nucleos/NucleosSitemapBundle/pull/20
[#15]: https://github.com/nucleos/NucleosSitemapBundle/pull/15
[@nucleos]: https://github.com/nucleos
[@core23]: https://github.com/core23
