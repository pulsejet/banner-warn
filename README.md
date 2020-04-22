# Roundcube Banner Warn

Displays a warning to roundcube users under some contexts.

## Types of warnings
* Email originating from outside your organization
* Maked as spam in X-Spam-Status
* Failing Received-SPF

## Screenshots
Warnings are displayed similar to Outlook \
<img src="screenshots/outlook.png" alt="Outlook" width="600"/>

**With this plugin** \
<img src="screenshots/external.png" alt="External Email" width="600"/>
<img src="screenshots/spam.png" alt="Spam Email" width="600"/>
<img src="screenshots/spffail.png" alt="SPF failing Email" width="600"/>

## Installation
To install, get the plugin with composer in your roundcube directory
```
composer require radialapps/banner-warn
```

## License
Permissively licensed under the MIT license

