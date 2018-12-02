<?php

namespace IhsGetWell\Shortcodes;

class GetWellForm{

    /**
     * @var string this is a special name that the wordpress ajax needs
     */
    protected $ajaxCallName = 'ihs-get-well';

    static function instance($mailSend)
    {
        static $obj;
        return isset($obj) ? $obj : $obj = new self($mailSend);
    }

    private function __construct($mailSend)
    {
        $this->mailSend = $mailSend;
    }

    public function start()
    {
        // Creacion del shortcode y el su-shortcode (shortcoes ultimate)
        add_shortcode('get-well-form', array($this, 'shortcode'));
        add_shortcode(get_option('su_option_prefix'). 'get-well-form', 'shortcode');

        // Creacion de la interfaz en shortocodes ultimate
        add_filter( 'su/data/shortcodes', array($this, 'ultimate'));

        // Add ajax endpoint
        add_action( 'wp_ajax_nopriv_'.$this->ajaxCallName, array($this, 'ajaxProcess') );
        add_action( 'wp_ajax_'.$this->ajaxCallName, array($this, 'ajaxProcess') );
        add_action( 'wp_enqueue_scripts', array($this, 'registerScripts') );
    }

    public function shortcode($atts)
    {
        $atts = shortcode_atts( array(
            'form_id' => uniqid('get-well-form-'),
            'locations' => str_replace("\n", ",", get_option('ihs_get_well_locations'))
        ), $atts);
        wp_localize_script( 'ihs-get-well', 'ihs_get_well', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'form_id' => $atts['form_id'],
            'action' => $this->ajaxCallName,
            'notices_el' => '.get-well-notices' // Where to put the error or success messages
        ) );
        wp_enqueue_script( 'ihs-get-well');
        wp_enqueue_script( 'jquery-form-validator');
        wp_enqueue_script( 'jquery-ui-datepicker');
        wp_enqueue_style( 'jquery-ui-smoothness');
?>
<div class="get-well-wrapper">
    <form id="<?php echo $atts['form_id'] ?>" method="post">
        <h3><?php _e('Patient Information', 'ihs-get-well') ?></h3>
        <div class="get-well-group name">
            <label for="get-well-name"><?php _e('Patient Name', 'ihs-get-well') ?></label>
            <input id="get-well-name" class="get-well-input" type="text" name="get_well_name" data-validation="">
        </div>
        <div class="get-well-group room">
            <label for="get-well-name"><?php _e('Room Number', 'ihs-get-well') ?></label>
            <input id="get-well-room" class="get-well-input" type="text" name="get_well_room" data-validation="">
        </div>
        <div class="get-well-group location">
            <label for="get-well-location"><?php _e('Hospital Location', 'ihs-get-well') ?></label>
            <select id="get-well-location" class="get-well-select" name="get_well_location" data-validation="">
                <option value=""></option>
                <?php foreach (explode(',', $atts['locations']) as $location): ?>
                    <?php
                        list($name, $email) = explode('|', $location);
                        $name = trim($name); $email = trim($email);
                        if (empty($name) || empty($email)) continue;
                    ?>
                    <option value="<?php echo $email; ?>"><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <h3><?php _e('Sender Information', 'ihs-get-well') ?></h3>
        <div class="get-well-group my-name">
            <label for="get-well-my-name"><?php _e('My Name', 'ihs-get-well') ?></label>
            <input id="get-well-my-name" class="get-well-input" type="text" name="get_well_my_name" data-validation="">
        </div>
        <div class="get-well-group my-email">
            <label for="get-well-my-email"><?php _e('My Email', 'ihs-get-well') ?></label>
            <input id="get-well-my-email" class="get-well-input" type="email" name="get_well_my_email" data-validation="">
        </div>
        <div class="get-well-group my-phone">
            <label for="get-well-my-phone"><?php _e('My Phone', 'ihs-get-well') ?></label>
            <input id="get-well-my-phone" class="get-well-input" type="tel" name="get_well_my_pone" data-validation="">
        </div>


        <h3><?php _e('Card details', 'ihs-get-well') ?></h3>
        <div class="get-well-group message">
            <label for="get-well-message"><?php _e('Card Message', 'ihs-get-well') ?></label>
            <textarea id="get-well-message" class="get-well-textarea" name="get_well_message"></textarea>
        </div>
        <div class="get-well-group delivery-date">
            <label for="get-well-delivery-date"><?php _e('Delivery Date', 'ihs-get-well') ?></label>
            <input id="get-well-delivery-date" class="get-well-input" type="text" name="get_well_delivery_date" autocomplete="off">
        </div>
        <?php wp_nonce_field( plugin_basename( __FILE__ ), 'ihs-get-well-nonce' ); ?>

        <div class="get-well-button">
            <button id="get-well-preview-button"><?php _e('Preview', 'ihs-get-well') ?></button>
        </div>

        <iframe id="#ihs-get-well-pdf" src="https://wp.devenv/ihs/?ihs-get-well-pdf=1#toolbar=0" style="width: 100%; height: 50vh"></iframe>

        <div class="get-well-notices"></div>

        <div class="get-well-submit">
            <input id="get-well-submit-button" class="get-well-submit-button" type="submit" name="get_well_submit" value="<?php _e('Submit Data', 'ihs-get-well'); ?>">
        </div>
    </form>



</div>
<?php
    }

    public function ultimate($shortcodes)
    {
        return $shortcodes;
    }

    public function ajaxProcess()
    {
        $vals = array(
            'success' => true
        );
        foreach ( explode('&', str_replace('&amp;', '&', $_POST['values'])) as $value) {
            list($key, $val) = explode('=', $value);
            $vals[$key] = $val;
        }

        if (isset($vals['ihs-get-well-nonce']) && !wp_verify_nonce( $vals['ihs-get-well-nonce'], plugin_basename( __FILE__ ) )) {
            return wp_send_json(array(
                'success' => false,
                'error_msg' => __('Invalide nonce', 'ihs-get-well')
            ));
        }

        $this->mailSend->body('ajax');
        $ret = $this->mailSend->send();
        if (!$ret) {
            return wp_send_json(array(
                'success' => false,
                'error_msg' => __('Error sending email. Please contact the administrator', 'ihs-get-well')
            ));
        }

        wp_send_json($vals);
    }

    public function registerScripts()
    {
        wp_register_script( 'ihs-get-well', plugin_dir_url(dirname(__DIR__)).'js/ihs-get-well.js', array( 'jquery' ) );
        wp_register_script( 'jquery-form-validator', '//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js', array( 'jquery' ) );
        wp_register_style( 'jquery-ui-smoothness', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
    }
}
