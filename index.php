<?php

/**
 * Plugin Name: IHS Get Well Messages
 * Plugin URI: https://ihealthspot.com
 * Description: Enables a user to send a Get Well PDF to a patient
 * Version: 0.1
 * Author: Mario Yepes <mario.yepes@dazzet.co>
 *
 * Version:     0.0.1
 * License:     Commercial
 * License URI: license.txt
 * Text Domain: ihs-get-well
 * Domain Path: /languages
 */

namespace IhsGetWell;

require_once __DIR__ . '/vendor/autoload.php';

Settings\PluginActions::instance(plugin_basename(__FILE__))->start();
Settings\Settings::instance()->start();

$getWellMail = Mail\Send::instance();
Shortcodes\GetWellForm::instance($getWellMail)->start();
