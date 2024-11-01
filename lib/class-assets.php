<?php 
namespace HtmExport\Plugin;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Register Functions
**/
class Assets
{   
    public function __construct(){}
  
    public function register(){
        add_action('admin_enqueue_scripts', array($this, 'add_scripts'));
    }

    /**
     * Register the multi select
     *
     * @return void
     */
    public function add_scripts(){

        $screen = get_current_screen();

        $url = plugin_dir_url(__FILE__); 
        $new_url = str_replace('/lib', '/assets', $url);

        if($screen->id == 'tools_page_htmlpost-export-tools'){    
            wp_enqueue_style('htmexport-css', $new_url.'/css/multi-select.dist.css');
            wp_enqueue_script('htmlexport-js', $new_url.'/js/jquery.multi-select.js' , array('jquery'), null);  
        }


        $sitename = sanitize_key( get_bloginfo( 'name' ) );
        if ( ! empty( $sitename ) ) {
            $sitename .= '.';
        }
        $date        = gmdate( 'Y-m-d' );
        
        wp_enqueue_script('htmlexportadmin-js', $new_url.'/js/admin.js' , array('jquery'), null);  
        wp_localize_script('htmlexportadmin-js', 'HTMLEXPORT', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce'),
            'sitename' => $sitename,
            'date' => $date
        ));

    }
}