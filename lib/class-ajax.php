<?php 
namespace HtmExport\Plugin;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Ajax Functions
**/
class Ajax
{   
    public function __construct(){}
  
    public function register(){
        add_action('wp_ajax_htmgenpostlist', array($this, 'get_post_list'));
        add_action('wp_ajax_htmlexportlist', array($this, 'export_posts'));
    }

    private function verify_post(){
        //Did nonce verify 
        if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'ajax-nonce' ) ) {
            wp_send_json_error('Sorry you cannot access this endpoint');
        }

        if(!is_user_logged_in()){
            wp_send_json_error('Sorry you cannot access this endpoint');
        }

        //Are we admin ? 
        $user = wp_get_current_user();
        $allowed_roles = array('administrator');
        if(!array_intersect($allowed_roles, $user->roles ) ) {
            wp_send_json_error('Sorry you cannot access this endpoint');
        }
    }

    /**
     * Get all the posts
     *
     * @return void
     */
    public function get_post_list(){

        $this->verify_post(); 

        $post_types = get_post_types(array('public' => true), 'names'); 
        $post_type = sanitize_text_field($_REQUEST['postType']);

        $match = false;
        foreach($post_types as $p){
            if($p == $post_type){
                $match = true;
                break;
            }
        }        

        if(!$match){
            wp_send_json_error('Invalid post type');
        }
        
        //Grab all the posts
        $posts = get_posts(array(
            'post_type' => $post_type, 
            'numberposts' => -1, 
            'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')    
        ));
        
        if(empty($posts)){
            wp_send_json_error('No posts were found');
        }

        $result = array(); 
        foreach($posts as $p){
            $title =  $p->post_title;
            $title = $p->status != 'publish' ? $title.' ('. $p->status.')' : $title;
            $result[] = array('id' => $p->ID, 'title' => $p->post_title);
        }       
        wp_send_json_success($result);
        exit();     

    }

    /**
     * Do the export
     *
     * @return void
     */
    public function export_posts(){
        $this->verify_post(); 

        //Sanitizie and force to an integer
        $posts = array(); 
        if(!empty($_REQUEST['posts'])){
            foreach($_REQUEST['posts'] as $p){
                $sanitised_post = sanitize_text_field($p);
                $replaced_post = preg_replace('[^0-9\,]', '', $sanitised_post); 
                $posts[] = intval($replaced_post);
            }
        }

        if(empty($posts)){
            wp_send_json_error('You must supply some posts');
        }

        //Replace any non numeric or comma 
        $string  = implode(',', $posts);
        $post_list = array_map('intval', explode(',', $string));

        $filtered_list = array_filter($post_list);

        if(empty($filtered_list)){
            wp_send_json_error('You must supply some posts');
        }

        //Do the export bit
        $export = new Export(); 
        $export->export_wp(array('ids' => $filtered_list));
        exit;
    }
}