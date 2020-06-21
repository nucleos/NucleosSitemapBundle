# 4.0.0

## Changes

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


# 3.2.0

## Changes

- Add missing strict file header @core23 (#31)
- Use default public setting for all actions @core23 (#15)

## 📦 Dependencies

- Add support for symfony 5 @core23 (#20)
- Drop support for symfony 3 @core23 (#27)
- Remove cache-adapter (dev) dependency @core23 (#32)
