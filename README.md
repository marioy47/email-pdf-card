# Email PDF Card WordPress Plugin

Fairly simple plugin that a allows a website visitor to create a PDF card and have it sent by email to a recipient.

Use cases for this kind of plugin are:

- Allow visitor to create "Get Well" cards and send them with email
- Create price lists and have them sent by email 

## Installation

This is a standard WordPress plugin, you can install it using the Dashboard or expanding the `.zip` file in the `wp-content/plugins` dir of a WordPress installation

## Developement

To contribute to the developement of this plugin, you need to have the following:

- NodeJS
- Composer
- Npm (you should have it if you already have NodeJS)

To setup the _dev_ environment just do:

```bash
cd /path/to/wordpress/wp-content/plugins
mkdir email-pdf-card
cd $_
git clone git@github.com:marioy47/email-pdf-card.git .
npm install
npm start
```
