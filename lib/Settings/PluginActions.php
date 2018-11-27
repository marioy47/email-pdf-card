<?php

namespace IhsGetWell\Settings;

class PluginActions
{
    /** @var string The plugin path for the settings link */
    protected $basename;

    protected $slug = 'ihs-get-well';

    /**
     * @var $basename string Normaly is the return of plugin_basename(__FILE__)
     */
    static function instance($basename)
    {
        static $obj;
        return isset($obj) ? $obj : $obj = new self($basename);
    }

    private function __construct($basename)
    {
        $this->basename = $basename;
    }

    public function start()
    {
        add_filter( 'plugin_action_links_' . $this->basename, array($this, 'actionLinks'));
    }

    public function actionLinks($links)
    {
       $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page='.$this->slug) ) .'">'.__('Settings').'</a>';
       return $links;
    }
}
