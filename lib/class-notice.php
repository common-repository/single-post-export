<?php

namespace HtmExport\Plugin;

if (!defined('ABSPATH')) exit;

/**
 * Register Functions
 **/
class Notice
{

    public function register()
    {
        // Hook get's run on plugin activation
        register_activation_hook(PLUGIN_FILE_URL, function () {
            set_transient('admin-activation-notice', true, 5);
        });


        add_action('admin_notices', function () {

            if (get_transient('admin-activation-notice')) {
?>
                <div class="notice notice-success is-dismissible">
                    <p>Upgrade Single Export to <strong>Pro</strong> for a better experience</p>
                </div>
<?php
            }
        });
    }
}
