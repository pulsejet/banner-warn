# Roundcube Banner Warn

Displays avatars in roundcube message list and banner warnings under some contexts.

## Screenshots

The plugin adds avatars to the message list that display the user's contact photo or a colored first letter, and warns the user for various contexts. \
<img src="screenshots/avatars1.png" alt="Avatars" width="800"/>

On multiselecting emails (which can be done by clicking the avatar), the avatars animate similar to the Gmail app \
<img src="screenshots/avatars2.png" alt="Avatars" width="800"/>

### Banner Warnings

Warnings are displayed similar to Outlook \
<img src="screenshots/outlook.png" alt="Outlook" width="600"/>

**With this plugin** \
<img src="screenshots/external.png" alt="External Email" width="600"/>
<img src="screenshots/spam.png" alt="Spam Email" width="600"/>
<img src="screenshots/spffail.png" alt="SPF failing Email" width="600"/>

## Types of warnings

* Email originating from outside your organization
* Marked as spam in X-Spam-Status
* Failing Received-SPF

## Name of this plugin

This plugin is named as such for historical reasons. The chief functionality currently is showing avatars beside the message list, and banner warnings are displayed for individual emails as well.

## Installation

To install, get the plugin with composer in your roundcube directory
```
composer require radialapps/banner-warn
```

_NOTE:_ Answer `N` when composer ask you about plugin activation.

Activate the plugin by editing the `HOME_RC/config/config.inc.php` file: 

```php
$config['plugins'] = [
  // ... other plugins
  'banner_warn',
];
```

## Configuration

Optionally, you can now configure the plugin by editing the `HOME_RC/plugins/banner_warn/config.inc.php` file:

```php
<?php

$config = array();

// Regex to match against the email to determine if from your organization
$config['org_email_regex'] = "/@(.*\.|)iitb\.ac\.in/i";

// Turn on letter avatars
$config['avatars'] = true;

// Make external avatars hexagon
$config['avatars_external_hexagon'] = true;

// Header that marks the message as SPAM. ('Yes').
$config['x_spam_status_header'] = 'x-spam-status';

// Header to check spam level. Counts number of asterisk in this.
$config['x_spam_level_header'] = 'x-spam-level';

// Spam threshold for X-Spam-Level to alert user for
$config['spam_level_threshold'] = 4;

// Header that marks the message as SPF fail. ('Pass' to pass).
$config['received_spf_header'] = 'received-spf';

// Display images for avatars
// If you don't use images for avatars, set `false` to save performance
$config['avatar_images'] = true;
```

For example update the `$config['org_email_regex']` to your domain. This configuration file will overrule configuration settings from the `config/config.inc.php` file.

## License

Permissively licensed under the MIT license

