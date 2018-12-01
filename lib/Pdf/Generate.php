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

        $html =<<<EOF
<style>
@page {
    margin-top: 25%;
    margin-left: 45%;
    background-image: url(https://images.unsplash.com/photo-1532274402911-5a369e4c4bb5?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=81a5f1725ca68c549e0054dcfdf269de&w=1000&q=80);
    background-image-resize: 6;
    padding-right: 25%;
}

body {
    font-family: sans-serif;

}
</style>
<body>
<h1>mPDF</h1>
<h2>Backgrounds & Borders</h2>
</body>
EOF;

        $mpdf = new \Mpdf\Mpdf(array(
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'orientation' => 'L'
        ));
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        exit;
    }


}
