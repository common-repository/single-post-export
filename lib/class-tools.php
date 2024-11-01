<?php

namespace HtmExport\Plugin;

if (!defined('ABSPATH')) exit;

/**
 * Tools page 
 **/
class Tools
{
    public function __construct( ) {
    }

    public function register() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    }
    /*
       Add options page
    */
    public function add_plugin_page()
    {
        add_management_page(
            'Export Specific Posts',
            'Export Specific Posts',
            'manage_options',
            'htmlpost-export-tools',
            array($this, 'create_tools_page')
        );
    }

    /**
     * Options page callback
     */
    public function create_tools_page()
    {
        // Set class property
?>
        <div class="wrap ">
            <div class="htm-tool-wrap">
                <div class="htm-tool-wrap-header">
                   <h2>Export Specific Posts</h2>
                </div>
                <div class="dropdown-wrap">
                    <div><label for="postType">Post Type</label></div>
                    <div>
                        <select name="postType" id="postType" class="widefat">
                            <option value="">Please Select...</option>
                                <option value="post">Post</option>
                                <option value="page">Page</option>
                                <option value="attachment">Attachment</option>
                        </select>
                    </div>
                </div>

                <div id="post-grid-list">
                    <div class="inner-input">
                        <select multiple="multiple" id="post-list" name="post-list[]">
                        </select>

                    </div>

                    <button type="" class="is-primary button" id="performDownload">Download Export File</button>
                </div>

                <div class="loadinggif">
                    <div></div>
                </div>
            </div>
           <?php /* <div class="htm-info-wrap">
                <img class="w-100" src="../assets/img/logo.svg" alt="HTML Digital">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium dolores repudiandae minus ea et obcaecati eos, veritatis ratione perspiciatis tempora facere laborum facilis deserunt totam quibusdam possimus fuga eveniet quae?</p>
            </div>*/ ?>
        </div>
    <?php
        $this->do_script();
    }

    private function do_script()
    {
        $sitename = sanitize_key(get_bloginfo('name'));
        if (!empty($sitename)) {
            $sitename .= '.';
        }
        $date        = gmdate('Y-m-d');
        $url = plugin_dir_url(__FILE__);
        $new_url = str_replace('/lib', '/assets', $url);
    ?>
        <style>
            .htm-tool-wrap .loadinggif {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                display: none;
                background-repeat: no-repeat;
                background-position: center center;
                background: rgba(255, 255, 255, .3);
            }

            .wrap {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
            }

            .header {
                display: flex;
                justify-content: space-between;
            }

            .htm-info-wrap {
                display: flex;
                flex-direction: column;
                grid-column-start: 4;
            }

            .htm-tool-wrap .loadinggif div {

                background-image: url("<?php echo $new_url . 'img/loading.gif'; ?>");
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-repeat: no-repeat;
                background-position: center center;
            }

            .htm-tool-wrap {
                padding: 15px;
                background: #FFF;
                border: 1px solid #EFEFEF;
                width: auto;
                display: table;
                margin: auto;
                min-width: 400px;
                position: relative;
            }

            @media(max-width:500px) {
                .htm-tool-wrap {
                    width: 100%;
                    min-width: 0;
                }
            }
            .notice{
                display:none !important;
            }

            .htm-tool-wrap h1 {
                padding-top: 0 !important;
                margin-top: 0 !important;
                margin-bottom: 15px;
            }

            .htm-tool-wrap label {
                font-weight: bold;
                font-size: 1.0rem;
                padding-right: 10px;
            }

            .dropdown-wrap {
                display: flex;
                justify-content: flex-start;
                align-items: center;
            }

            #post-grid-list {
                padding-top: 30px;
            }

            #performDownload {
                margin: 30px auto;
                display: table;
            }
        </style>

        <script>
            jQuery(document).ready(function($) {
                $('#post-grid-list').hide();



                let nonce = '<?php echo wp_create_nonce('ajax-nonce'); ?>';

                function showExportLoading() {
                    $('.loadinggif').fadeIn()
                }

                function hideExportLoading() {
                    $('.loadinggif').fadeOut()
                }

                $('#postType').on('change', function(e) {
                    e.preventDefault();

                    $('#post-grid-list').hide();

                    if ($('#postType').val() == '') {
                        $('.inner-input').empty();
                        return;
                    }

                    showExportLoading();

                    var url = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php';
                    jQuery.ajax({
                        type: 'POST',
                        url: url,
                        timeout: 99999,
                        data: {
                            action: 'htmgenpostlist',
                            postType: $('#postType').val(),
                            nonce: nonce
                        },
                        success: function(response) {

                            hideExportLoading();
                            if (undefined !== response.success && response.success == false) {
                                alert(response.data);
                                return;
                            }

                            //Do data 
                            $('#post-list').val('');
                            $('#post-list option').remove();

                            for (var i = 0; i < response.data.length; i++) {
                                $('#post-list').append("<option value='" + response.data[i].id + "'>" + response.data[i].title + "</option>");
                            }
                            $('#post-list').multiSelect();
                            $('#post-grid-list').show();
                            $('#post-list').multiSelect('refresh');
                        },
                        error: function(response, b, c) {
                            hideExportLoading();
                            if (undefined !== response.data && response.data == false) {
                                alert(response.data);
                                return;
                            }
                        }
                    });
                });

                $('#performDownload').on('click', function(e) {
                    e.preventDefault();

                    if ($('#post-list').val() == '') {
                        alert('You must make a selection');
                        return;
                    }


                    showExportLoading();

                    let postList = [];
                    let value = $('#post-list').val();
                    for (var i = 0; i < value.length; i++) {
                        postList.push('posts[]=' + value[i]);
                    }

                    hideExportLoading();

                    var link = document.createElement('a');
                    link.href = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php?action=htmlexportlist&nonce=' + nonce + '&' + postList.join('&');
                    link.target = '_blank';
                    link.click();
                });

            });
        </script>
<?php
    }
}
