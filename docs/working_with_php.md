Working with PHP

:back: [Home](../README.md)

Table of Contents

- [Components](#components)
	- [The `init()` method](#the-init-method)
	- [The `enabled()` method](#the-enabled-method)
- [Debugging PHP](#debugging-php)

## Components

The fundamental building block of the theme is a **component**.

A component is a class that extends the `Component` abstract class.
It provides a set of utilities that abstract away and simplify common tasks like registering WordPress hooks or [defining theme options](#working-with-theme-options).

Components are stored in the `/php/components` directory.

Every component is initialized in the [`functions.php`](/functions.php) file and passed into the global `Theme` object where it is registered and stored.
This gives you the opportunity to pass dependencies into your component's constructor:

```php
$theme = new Theme(
	[
		// ...
		new Eutopia_Theme\Components\My_Component( new My_Dependency() ),
		// ...
	],
);
```

Thanks to the fact that every component is stored in the `Theme` object, we can retrieve its instance and call its public methods from anywhere like:

```php
<?php My_Component::get_instance()?->my_template_method(); ?>
```

Another benefit of this architecture is that your IDE will figure out what public methods a given `Component` exposes just by getting its instance via the `::get_instance()` method.

### The `init()` method

The `init()` method is used to register WordPress hooks used by a component.
A default empty `init()` method already in place. Only components that use WordPress hooks should override this method.

### The `enabled()` method

If your component provides a custom `enabled()` method and it returns `false`, the component will not be registered in the `Theme` object (and thus its `init()` method won't be called at all).

Because some components may be "turned off", it's advisable to use the [nullsafe operator `?->`](https://wiki.php.net/rfc/nullsafe_operator) anywhere you're calling a class's public method, e.g.:

```php
<?php Component::get_instance()?->do_something( 'argument' ); ?>
```

This will cause the method call to fail silently in case the `SVG` class is not available.

## Debugging PHP

The included `@wordpress/env` [development environment :arrow_upper_right:](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) supports Xdebug which can be enabled by starting the development environment with additional flags:

```sh
npm run start -- --xdebug=debug
```

where `--xdebug` is a comma-separated list of the [supported :arrow_upper_right:](https://xdebug.org/docs/all_settings#mode) Xdebug modes.
