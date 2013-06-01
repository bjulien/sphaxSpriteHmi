<<<<<<< HEAD
Sphax Sprite Bundle
==================
Easily manage your sprites in your Symfony2 environment


## Installation
### Dependancies
For this bundle to work, you have to install [Glue] (http://glue.readthedocs.org/en/latest/)

### Get the bundle

Add this in your composer.json

```json
{
	"require": {
		"sphax/sprite-bundle": "dev-master@dev"
	}
}
```

and then run

```sh
php composer.phar update
```
or 
```sh
composer update
```
if you installed composer globally.

### Add the classes to your Kernel
```php
new Sphax\SpriteBundle\SphaxSpriteBundle(),
```

### Configuration
You have to configure your sprite by puting the following lines in your ```config.yml``` file: 

Minimal configuration:
```yaml
sphax_sprite:
    sprite:
        spritename:
            sourceSpriteImage: "%kernel.root_dir%/../web/imp/sprites/spritename/"
            outputSpriteImage: "%kernel.root_dir%/../web/img/sprites/"
        another_spritename:
            sourceSpriteImage: "%kernel.root_dir%/../web/img/sprites/another/"
            outputSpriteImage: "%kernel.root_dir%/../web/img/sprites/"
```

Full configuration (with default value) :
```yaml
        spritename:
            nameBin: 'glue'
            sourceSpriteImage: "%kernel.root_dir%/../web/imp/sprites/spritename/"
            outputSpriteImage: "%kernel.root_dir%/../web/img/sprites/"
            force: false # erase old generated files
            options: 
                optipng: true
                cachebuster: true
                less: false
                namespace: "sprite" 
                separator: "-"
```

## Generate sprites
Generate all your sprites : 
```sh
$ php app/console sphax:sprite:generate
```

Generate one sprite : 
```sh
$ php app/console sphax:sprite:generate spritename
```

## Use in your templates
You have now to integrade your generated files in your templates.
Example : 
```twig
{% block stylesheets %}
    {% stylesheets
        "img/sprite/*.css"
        output="css/sprite.css"
    %}
        <link rel="stylesheet" type="text/css" href="{{ asset_url }}" />
    {% endstylesheets %}
{% endblock %}
```
=======
sphaxSpriteHmi
==============

web interface
>>>>>>> 26d313b75b990daf5c1c04aa2aff973c5cb675a4
