<?php

namespace IhsGetWell\Pdf;

class Generate
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
        add_filter('query_vars', array($this, 'queryVars'));
        add_action('parse_request', array($this, 'parseRequest'));
    }

    public function queryVars($vars)
    {
        $new_vars = array('ihs-get-well-pdf');
        $vars = $new_vars + $vars;
        return $vars;
    }

    public function parseRequest($wp)
    {
        if (!array_key_exists('ihs-get-well-pdf', $wp->query_vars)) return;

        $mpdf = new \Mpdf\Mpdf(array(
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'orientation' => 'L'
        ));
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML('<h1>Hello world!</h1>');
        $mpdf->Output();
    }


}
