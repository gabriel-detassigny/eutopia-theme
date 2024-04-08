Local Setup Guide

:back: [Home](../README.md)

Table of Contents

- [Installation](#installation)
	- [Requirements](#requirements)
- [Setup](#setup)
	- [Advanced Custom Fields Pro](#advanced-custom-fields-pro)
	- [Installing dependencies](#installing-dependencies)
	- [Starting the local environment](#starting-the-local-environment)
- [Debugging](#debugging)
	- [Resolving Port Conflicts](#resolving-port-conflicts)
- [Extras](#extras)
	- [Load images from the live environment locally](#load-images-from-the-live-environment-locally)
	- [Code Editor and Git Client](#code-editor-and-git-client)

## Installation

### Requirements

Eutopia Theme requires the following dependencies:

- PHP 8.2
- [Composer :arrow_upper_right:](https://getcomposer.org)
- [Node.js :arrow_upper_right:](https://nodejs.org) version 16
- [Docker with Docker Compose :arrow_upper_right:](https://docs.docker.com/compose/install/)

Use a package manager like [Homebrew :arrow_upper_right:](https://brew.sh) on macOS or [Chocolatey :arrow_upper_right:](https://chocolatey.org) for Windows to install the project dependencies.

```sh
brew install php@8.2 composer node@16
brew install --cask docker
```

Use [NVM :arrow_upper_right:](https://github.com/nvm-sh/nvm) to switch between the versions of Node required for each project (set in the `.nvmrc`):

```sh
nvm install
nvm use
```

## Setup

To start using all the tools that come with `eutopia-theme` you need to install the necessary Composer and Node dependencies.

### Advanced Custom Fields Pro

The theme is bundled with Advanced Custom Fields Pro (ACF Pro) for creating blocks and adding a theme settings page. It is added via `composer.json` from the [Gabriel de Tassigny private packagist :arrow_upper_right:](#).

### Installing dependencies

In order to install Composer and NPM dependencies, run:

```sh
composer install
npm install
```

### Starting the local environment

To start the local environment, run:

```sh
npm run start
```

Default login credentials are username: `admin` and password: `password`. ([the standard in wp-env :arrow_upper_right:](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/#starting-the-environment))

## Debugging

### Resolving Port Conflicts

Docker engine shares the networking interface with the host computer so all the ports used by the containers need to be free and unused by any other services such as a DNS resolver on port 53, MySQL service on port 3306 or another web server running on port 80.

Use the included `npm run stop-all` command to stop all containers running Docker containers on the host machine.

On Debian and Ubuntu systems use `sudo systemctl stop ...` to disable those services. For example:

- `sudo systemctl stop mysql` to stop MySQL
- `sudo systemctl stop apache2` to stop Apache
- `sudo systemctl stop systemd-resolved` to stop the local name server.

On macOS if you're using valet or valet plus you can run following:

- `valet stop` to stop valet services.
- `brew services list` to see all services running; There could be a service running with superuser in that case use `sudo brew services list` to see those and stop those by appending `sudo` to following commands.
- `brew services stop dnsmasq` to stop dnsmasq service.
- `brew services stop mariadb` to stop database service. (or mysql whichever database you're using.)
- `brew services stop redis` to stop redis service.

Alternatively, you can adjust the port mappings in `docker-compose.yml` to use different ports.

## Extras

### Load images from the live environment locally

Create a new file `local-config.php` in the `mu-plugins` directory with the following content, this is a starting point example:

```php
<?php
/**
 * Proxies the local image requests to the live website.
 */

$local_wp_url = [ 'https://local.example.net/', 'http://local.example.net/' ];
$remote_url   = 'https://example.net/';
add_filter(
	'upload_dir',
	function ( $upload_dir_data ) use ( $local_wp_url, $remote_url ) {

		if ( ! empty( $upload_dir_data['baseurl'] ) ) {
			$upload_dir_data['baseurl'] = str_replace( $local_wp_url, $remote_url, $upload_dir_data['baseurl'] );
		}
		if ( ! empty( $upload_dir_data['url'] ) ) {
			$upload_dir_data['url'] = str_replace( $local_wp_url, $remote_url, $upload_dir_data['url'] );
		}

		return $upload_dir_data;
	}
);
```

### Code Editor and Git Client

This repository includes a list of suggested extensions for the [Visual Studio Code editor](https://code.visualstudio.com) and Xdebug support in the `.vscode` directory.

A user-friendly Git client such as [GitHub Desktop](https://desktop.github.com) or [Tower](https://www.git-tower.com/mac) enables smaller commits and simplifies merge conflict resolution.
