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

## TODO

- [x] Add composer installation to `gulpfile.js`
- [ ] Add zip taks on `gulpfile.js`
- [ ] Enable remove images in the settings page
- [ ] Organize the HTML for the image table on settings
- [ ] Make the TypeScript for the Settings Image Table work
- [ ] Fix [X-Frame-Option](https://developer.mozilla.org/es/docs/Web/HTTP/Headers/X-Frame-Options) for inline PDF (or open in a new window)
- [ ] Create the TypeScript that handles the form submission 
- [ ] Finish the ajax call
- [ ] Add some usage documentation
- [ ] Enable the usage of templates
- [ ] Enable the selection of background images
- [ ] Convert the admin image selector to TypeScript
