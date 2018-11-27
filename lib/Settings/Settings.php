<?php

namespace IhsGetWell\Settings;

class Settings
{

    static function instance()
    {
        static $obj;
        return isset($obj) ? $obj : $obj = new self();
    }

    private function __construct()
    {
    }

    public function start()
    {
        add_action('admin_menu', array($this, 'addAdminMenuAndPage'));
    }

    public function addAdminMenuAndPage()
    {
        add_options_page(
            __('IHS Get Well', 'ihs-get-well'),
            __('IHS Get Well.', 'ihs-get-well'),
            'manage_options',
            'ihs-get-well',
            array($this, 'createSettingsPage')
        );
    }

    public function createSettingsPage()
    {
?>
    <h1><?php _e('Select the images you want to display') ?></h1>
    <form action="options.php">
        <?php settings_fields('ihs-get-well-group') ?>
        <?php do_settings_sections('ihs-get-well') ?>
        <?php submit_button() ?>
    </form>

<?php
    }

}
