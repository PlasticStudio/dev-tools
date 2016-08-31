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


# Installation

1. Add to your composer requirements `composer require jaedb/dev-tools`
2. Edit your theme's `templates/Page.ss` template and add `$DebugTools` immediately before the `</body>` tag
3. Run /dev/build?flush=1
4. Toggle debug tools by turning your site to DEV or TEST modes (disabled on LIVE sites for obvious reasons)


# Usage

### Debug Tools

![Debug tools](https://raw.githubusercontent.com/jaedb/dev-tools/master/source/screenshot-debugtools.jpg)

* Manage the visibility of the Debug Tools from within the CMS, under _Settings_
* Manage ability to Emulate users, also under _Settings_

### IconSelectField

![IconSelectField](https://raw.githubusercontent.com/jaedb/dev-tools/master/source/screenshot-iconselectfield.jpg)

* `IconSelectField::create($name, $title, $iconFolder)`
* `$name` is the database field as defined in your class
* `$title` is the label for this field
* `$iconFolder` (optional) defines the directory where your icons can be found. Defaults to `/site/icons`.

### LogJam

* `LogJam::Log($message, $environment)`
* `$message` a string that you'd like to log
* `$environment` (optional) the type of environment that should log this message (ie _test_, _dev_, _live_). Defaults to `dev`.

