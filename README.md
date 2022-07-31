## Installation

 At the moment this plugin is not on any public repository to install it.
1. define a separate repository in `composer.json` file
```
// ...
"repositories":  [
	{
		"type":  "vcs",
		"url":  "git@github.com:blush-commerce/SyliusBankOfGeorgiaPlugin.git"
	}
],
// ...
```

2. Run `composer install gigamarr/sylius-bank-of-georgia-plugin`

## Usage
1. Create a new payment method in Sylius admin panel using `Bank of Georgia` option in create button dropdown.
2.  Set up your client credentials and preferences in the configuration form. Currently plugin supports pre-authorized capturing as well as automatic capturing. Recurring payments are not currently supported.

> Pre-authorized payments and currencies other than GEL are not enabled by default, contact Bank of Georgia.

## Debugging
If you're experiencing issues check `var/log/bank_of_georgia.log`
