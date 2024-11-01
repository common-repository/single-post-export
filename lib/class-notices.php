<?php 
namespace HtmExport\Plugin;
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Register Functions
**/
class Notices
{   
    public function __construct(){}
 	
	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	private $MINIMUM_PHP_VERSION = '7.0';


    public function register()
    {
		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, $this->MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

        $pluginList = get_option( 'active_plugins' );
        $plugin = 'htmexporpro/htmexporpro.php'; 
        if ( in_array( $plugin , $pluginList ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_plugin_conflict' ) );
            return;
        }
    }



	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have htmexport installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_plugin_conflict() {
		deactivate_plugins( plugin_basename( HTMEXPORT ) );


		echo sprintf(
			wp_kses(
				'<div class="notice notice-warning is-dismissible"><p>The Pro version of this plugin is already activated</p></div>',
				array(
					'div' => array(
						'class'  => array(),
						'p'      => array(),
						'strong' => array(),
					),
				)
			),
			'Single Post Export',
			'htmexport'
		);
	}

	

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		deactivate_plugins( plugin_basename( HTMEXPORT ) );

		echo sprintf(
			wp_kses(
				'<div class="notice notice-warning is-dismissible"><p><strong>"%1$s"</strong> requires <strong>"%2$s"</strong> version %3$s or greater.</p></div>',
				array(
					'div' => array(
						'class'  => array(),
						'p'      => array(),
						'strong' => array(),
					),
				)
			),
			'Single Post Export',
			'htmexport',
			$this->MINIMUM_PHP_VERSION
		);
	}
}