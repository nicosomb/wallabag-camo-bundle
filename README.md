# Plugin for wallabag: add an http proxy to route images through SSL

This bundle allows you to add a proxy for your wallabag images thanks to [camo](https://github.com/atmos/camo).

## Requirements

* wallabag >= 2.2.2

## Installation

### Download the bundle

```
composer require nicosomb/wallabag-camo-bundle
```

### Enable the bundle

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Nicosomb\WallabagCamoBundle\NicosombWallabagCamoBundle(),
        );

        // ...
    }

    // ...
}
```

### Configure your application

```yml
# app/config/config.yml

nicosomb_wallabag_camo:
    key: 8201937eca2c9d34f3c8b3edd18c3113000291847bbceebec8c4aa481e82b1dc
    domain: your-wallabag.herokuapp.com
```

### Setting `camo_images_enabled`

You need to create a new setting, `camo_images_enabled`. 

```sql
INSERT INTO `wallabag_craue_config_setting` (`name`, `value`, `section`) VALUES ('camo_images_enabled', 1, 'misc');
```