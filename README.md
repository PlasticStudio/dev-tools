# Description

Adds additional functionality to assist in developing SilverStripe websites.


# Dependencies

* SilverStripe 3.1+
* Betterbuttons


# Features

* Performance of page loads
* Visualisation of current site state (DEV/TEST/LIVE)
* Admins can emulate any other user
* `IconSelectField` to use icon libraries within the CMS
* Automatic redirection from development domains, when in LIVE mode
* `LogJam` logging to append system error log
* BugHerd integration


# Installation

1. Add to your composer requirements `composer require jaedb/dev-tools`
2. Edit your theme's `templates/Page.ss` template and add `$DebugTools` immediately before the `</body>` tag
3. Run /dev/build?flush=1
4. Toggle debug tools by turning your site to DEV or TEST modes (disabled on LIVE sites for obvious reasons)
5. Set the site's _BugHerd_ project key in `config.yml`. See [config/dev-tools.yml](https://github.com/PlasticStudio/dev-tools/blob/master/_config/dev-tools.yml) for an example.


# Usage

### Primary domain redirection

When you set your `SS_PRIMARY_DOMAIN` property on a `live` website, we automatically redirect to this primary domain. This avoids the need for bloated .htaccess redirections and allows control at the environment level.

* Set your preferred domain in `_ss_environment.php`
* Consider whether you want http vs https, and include this in your domain (but exclude trailing slash!).
* Example: `define('SS_PRIMARY_DOMAIN', 'https://plasticstudio.co.nz');` will redirect http://www.plasticstudio.co.nz/some-section/subpage to https://plasticstudio.co.nz/some-section/subpage.
* To completely disable this functionality set `disable_primary_domain_redirection: true` in your project's `config.yml`


### Debug Tools

![Debug tools](https://raw.githubusercontent.com/PlasticStudio/dev-tools/master/source/screenshot-debugtools.jpg)

* Manage the visibility of the Debug Tools from within the CMS, under _Settings_
* Manage ability to Emulate users, also under _Settings_

### IconSelectField

![IconSelectField](https://raw.githubusercontent.com/PlasticStudio/dev-tools/master/source/screenshot-iconselectfield.jpg)

* Set your `$db` field to type `Icon` (eg `'PageIcon' => 'Icon'`)
* `IconSelectField::create($name, $title, $iconFolder)`
* `$name` is the database field as defined in your class
* `$title` is the label for this field
* `$iconFolder` (optional) defines the directory where your icons can be found. Defaults to `/site/icons`.
* Use your icon in templates as you would any other property (eg `$PageIcon`). If your icon is an SVG, the SVG image data will be injected into the template. To prevent this, you can call `$PageIcon.IMG` instead to enforce use of `<img>` tags.

### LogJam

* `LogJam::Log($message, $environment)`
* `$message` a string that you'd like to log
* `$environment` (optional) the type of environment that should log this message (ie _test_, _dev_, _live_). Defaults to `dev`.

1. Enable LogJam in `_config.php` by adding `LogJam::EnableLog();`
2. Check your log file location. Set this in `_ss_environment.php` with the following:
```
ini_set('log_errors','on');
ini_set('error_log','/home/mywebsite/logs/php.log');
```
