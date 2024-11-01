<?php

namespace HtmExport\Plugin;

if (!defined('ABSPATH')) exit;

/**
 * Main entry point for the plugin
 **/
class Plugin
{
    public $version = 1.0;
    private static $instance;
    private $class_list;

    /**
     * Construct
     */
    public function __construct()
    {
    }
    /**
     * Our class instance
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Register the classes we need 
     */
    function register()
    {
        //Register a filter 
        add_filter('HtmExport_class_list', array($this, 'filter_class_list'), 10, 1);

        //Get the class list
        $this->class_list = array(
            'Tools' => new Tools(),
            'Ajax' => new Ajax(),
            'Assets' => new Assets(),
            'Notices' => new Notices(),
        );


        //Register all the hooks
        if (!empty($this->class_list)) {
            foreach ($this->class_list as $l) {
                $l->register();
            }
        }
    }
    /** 
     * Return the class List 
     **/
    public function filter_class_list($list)
    {
        return $list;
    }
}
