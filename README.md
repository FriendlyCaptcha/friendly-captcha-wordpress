# Friendly Captcha for WordPress

![FriendlyCaptcha widget solving screenshot](https://i.imgur.com/BNRdsxS.png) ![FriendlyCaptcha widget finished screenshot](https://i.imgur.com/HlMY7QM.png)

FriendlyCaptcha is a proof-of-work based CAPTCHA alternative that respects the user's privacy, see the [**Friendly Captcha website**](https://friendlycaptcha.com).

## Getting started

Install [**Friendly Captcha for WordPress**](https://wordpress.org/plugins/friendly-captcha/).

## Development

Make sure you have PHP installed (e.g. with `brew install php` on a Mac).

### Install Composer

```shell
cd friendly-captcha

mkdir -p bin
php -r "copy('https://getcomposer.org/installer', './bin/composer-setup.php');"
php bin/composer-setup.php --install-dir=bin --2.2
```

### Install dependencies

```shell
bin/composer.phar install
```

### Copy into Wordpress site

The `friendly-captcha` directory is now ready to be installed as a Wordpress plugin by copying it into the `wp-content/plugins` directory of your site.
