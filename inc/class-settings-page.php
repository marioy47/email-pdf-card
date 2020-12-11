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
        add_action('admin_enqueue_scripts', array($this, 'enqueueAdminScripts'));

        add_action( 'admin_menu', array($this, 'addAdminMenuAndPage'));
        add_action( 'admin_init', array($this, 'createSectionsAndFields'));


    }

    public function enqueueAdminScripts()
    {
        wp_register_script('ihs-get-well-admin', plugin_dir_url(dirname(__DIR__)) . 'js/ihs-get-well-admin.js', array('jquery'), null, false);
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
    <div class="wrap">
        <h1><?php _e('Select the images you want to display') ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('ihs-get-well') ?>
            <?php do_settings_sections('ihs-get-well') ?>
            <?php submit_button() ?>
        </form>
    </div>

<?php
    }

    public function createSectionsAndFields()
    {
        add_settings_section(
            'images',
            __('Images', 'ihs-get-well'),
            array($this, 'sectionImages'),
            'ihs-get-well'
        );

        register_setting('ihs-get-well', 'ihs_get_well_images');

        add_settings_section(
            'locations',
            __('Location information', 'ihs-get-well'),
            array($this, 'sectionLocations'),
            'ihs-get-well'
        );

        register_setting('ihs-get-well', 'ihs_get_well_locations');
        add_settings_field(
            'ihs_get_well_locations',
            __('Locations', 'ihs-get-well'),
            array($this, 'fieldLocations'),
            'ihs-get-well',
            'locations'
        );
    }

    public function sectionImages()
    {
        wp_enqueue_media();
        wp_enqueue_script('ihs-get-well-admin');
        wp_enqueue_script('jquery-ui-sortable');
        $val = (array)get_option('ihs_get_well_images', array());
        _e('Choose the images you want as card backgrounds', 'ihs-get-well');

?>
<table class="form-table" id="ihs-get-well-images-table" width="90%" align="center">
    <thead>
        <tr>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr class="template row">
            <td class="image-url"><a target="_blank" href=""><img class="aligncenter" /></a></td>
            <td class="image-actions">
                <a class="ihs-remove-image-button button" href="#">Remove</a>
                <input type="hidden" name="ihs_get_well_images[]"  class="image-input" value="">
                &nbsp;
                <span class="dashicons dashicons-move"></span>
            </td>
        </tr>
        <?php foreach ($val as $v): ?>
        <tr class="row">
            <td class="image-url"><a target="_blank" href="<?php echo wp_get_attachment_url($v)?>"><?php echo wp_get_attachment_image($v); ?></a></td>
            <td class="image-actions">
                <a class="ihs-remove-image-button button" href="#">Remove</a>
                <input type="hidden" name="ihs_get_well_images[]"  class="image-input" value="<?php echo $v ?>">
                &nbsp;
                <span class="dashicons dashicons-move"></span>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div>
<button class="button" id="ihs-get-well-add-image">Add Image</button>
</div>
<?php
    }


    public function sectionLocations()
    {
        _e('Add a the location name and the location email separated by a | (pipe symbol) on each line');
    }

    public function fieldLocations()
    {
        $val = get_option('ihs_get_well_locations', '');
        echo '<textarea name="ihs_get_well_locations" placeholder="Hospital Name | email@hostpital-name.com" class="widefat" rows="10">'.$val.'</textarea>';
    }

}