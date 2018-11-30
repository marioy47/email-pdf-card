<?php

namespace IhsGetWell\Mail;

require_once ABSPATH . '/wp-load.php';

class Send
{
    static function instance()
    {
        static $obj;
        return isset($obj) ? $obj : $obj = new self();
    }

    private function __construct()
    {
        $this->headers = array(
            'Replay-To' => 'Mario Yepes <mario.yepes@dazzet.co>'
        );

        $this->_to = 'marioy47@gmail.com';
        $this->_subject = 'Get Well Card';
        $this->_body = 'Message body';
        $this->_attachments = array();
    }

    /**
     * Adds the required actions to Wordpress
     * - New mail error handling functinos
     * - New page on the submenu
     */
    public function start()
    {
        add_action( 'wp_mail_failed', array($this, 'onMailError'), 10, 1 );
        add_filter( 'wp_mail_content_type', array($this, 'mailContentType') );
    }

    /**
     * Mail error handling function
     */
    public function onMailError($wp_error)
    {
        echo '<div class="error"><p><pre>';
        print_r($wp_error->errors);
        echo '</pre></div>';
    }

    public function mailContentType()
    {
        return 'text/html';
    }

    public function setHeader($name, $val)
    {
        $this->headers[$name] = $val;
        return $this;
    }

    public function to($val)
    {
        $this->_to = $val;
        return $this;
    }

    public function subject($val)
    {
        $this->_subject = $val;
        return $this;
    }
    public function body($val)
    {
        $this->_body = $val;
        return $this;
    }

    public function attachment($val)
    {
        $this->_attachments[] = $val;
        return $this;
    }

    public function send()
    {
        return wp_mail($this->_to, $this->_subject, $this->_body, $this->_headers, $this->_attachments);
    }

}
