NucleosSitemapBundle
====================
[![Latest Stable Version](https://poser.pugx.org/nucleos/sitemap-bundle/v/stable)](https://packagist.org/packages/nucleos/sitemap-bundle)
[![Latest Unstable Version](https://poser.pugx.org/nucleos/sitemap-bundle/v/unstable)](https://packagist.org/packages/nucleos/sitemap-bundle)
[![License](https://poser.pugx.org/nucleos/sitemap-bundle/license)](https://packagist.org/packages/nucleos/sitemap-bundle)

[![Total Downloads](https://poser.pugx.org/nucleos/sitemap-bundle/downloads)](https://packagist.org/packages/nucleos/sitemap-bundle)
[![Monthly Downloads](https://poser.pugx.org/nucleos/sitemap-bundle/d/monthly)](https://packagist.org/packages/nucleos/sitemap-bundle)
[![Daily Downloads](https://poser.pugx.org/nucleos/sitemap-bundle/d/daily)](https://packagist.org/packages/nucleos/sitemap-bundle)

[![Continuous Integration](https://github.com/nucleos/NucleosSitemapBundle/workflows/Continuous%20Integration/badge.svg?event=push)](https://github.com/nucleos/NucleosSitemapBundle/actions?query=workflow%3A"Continuous+Integration"+event%3Apush)
[![Code Coverage](https://codecov.io/gh/nucleos/NucleosSitemapBundle/graph/badge.svg)](https://codecov.io/gh/nucleos/NucleosSitemapBundle)
[![Type Coverage](https://shepherd.dev/github/nucleos/NucleosSitemapBundle/coverage.svg)](https://shepherd.dev/github/nucleos/NucleosSitemapBundle)

This bundle provides some classes for an automatic **sitemap.xml** generation.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
composer require nucleos/sitemap-bundle
```

### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Nucleos\SitemapBundle\NucleosSitemapBundle::class => ['all' => true],
];
```

### Configure the Bundle

Create a configuration file called `nucleos_sitemap.yaml`:

```yaml
# config/routes/nucleos_sitemap.yaml

nucleos_sitemap:
    resource: '@NucleosSitemapBundle/Resources/config/routing/sitemap.yml'
    prefix: /
```

If you want to use symfony cache, you should define a new cache pool (PSR 6) and create an adapter to map it to a simple cache (PSR 16):

```yaml
nucleos_sitemap:
    cache:
        service: 'sitemap.cache.simple'

framework:
    cache:
        pools:
            sitemap.cache:
                adapter: cache.app
                default_lifetime: 60

services:
    sitemap.cache.simple:
        class: 'Symfony\Component\Cache\Psr16Cache'
        arguments:
            - '@sitemap.cache'
```


### Add static entries

You can add static entries in your yaml config:

```yaml
# config/packages/nucleos_sitemap.yaml

nucleos_sitemap:
    static:
        - { url: 'http://example.com', priority: 75, changefreq: 'weekly' }
```

### Add a custom sitemap

If you want to create a custom sitemap, the only thing you have to do is to create a service that uses
`Nucleos\SitemapBundle\Sitemap\SitemapServiceInterface` and tag the service with `nucleos.sitemap`.

```xml
    <service id="App\Sitemap\CustomSitemap">
      <tag name="nucleos.sitemap"/>
    </service>
```

## License

This bundle is under the [MIT license](LICENSE.md).
