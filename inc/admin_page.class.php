<?php
defined( 'ABSPATH' ) OR exit;

add_action( current_filter(), array( 'SIPAdminPage', 'init' ), 20 );
class SIPAdminPage
{
	protected static $instance = null;

	// The page is accessible via this hook from @wp-hook 'admin_menu'
	public $page_hook = '';

	/**
	 * @wp-hook admin_menu
	 * @return  null|SIPAdminPage
	 */
	public static function init()
	{
		null === self::$instance AND self::$instance = new self;
		return self::$instance;
	}

	/**
	 * @wp-hook admin_menu
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'admin_page' ) );
		add_filter( 'set-screen-option', array( $this, 'set_screen_option' ), 0, 3 );
	}

	/**
	 * @wp-hook admin_menu
	 */
	public function admin_page()
	{
		$this->page_hook = add_submenu_page(
			'tools.php',
			__( 'Spam IPs', 'tsi_lang' ),
			__( 'Spam IPs', 'tsi_lang' ),
			'manage_options',
			'tsi',
			array( $this, 'render' )
		);

		add_action( "load-{$this->page_hook}", array( $this, 'add_screen_options' ) );
	}

	/**
	 * @wp-hook load-{$screen}
	 */
	public function add_screen_options()
	{
		isset( $_POST['wp_screen_options'] )
		AND is_array( $_POST['wp_screen_options'] )
			AND check_admin_referer( 'screen-options-nonce', 'screenoptionnonce' );

		add_screen_option( 'per_page', array(
			'label'   => _x( 'IPs per page', 'screen options', 'tsi_lang' ),
			'default' => 10,
			'max'     => 100,
			'option'  => get_current_screen()->id.'_per_page',
		) );
	}

	/**
	 * @wp-hook set-screen-option
	 */
	public function set_screen_option( $status, $option, $value )
	{
		if ( 'tools_page_tsi_per_page' === $option )
			return absint( $value );

		return esc_attr( $value );
	}

	public function render()
	{
		$target = admin_url( get_current_screen()->parent_file );
		$target = add_query_arg( 'page', $_REQUEST['page'], $target );
		$target = add_query_arg( 'amount', TSIData::get_amount(), $target );
		! isset( $_GET['export'] )
			AND $target = add_query_arg( 'export', 'true', $target );

		$link_text = isset( $_GET['export'] )
			? __( 'Back', 'tsi_lang' )
			: __( 'Export IPs', 'tsi_lang' )
		;
		?>
		<div class="wrap">
			<?php
			screen_icon();
			printf(
				'<h2>%s<a href="%s" class="add-new-h2">%s</a></h2>',
				__( 'Spam Comment IPs', 'tsi_lang' ),
				$target,
				$link_text
			);

			if (
				isset( $_GET['export'] )
				AND $_GET['export']
				)
			{
				$data = new TSIData();
				new TSIExport( $data );
			}
			else
			{
				require_once plugin_dir_path( __FILE__ )."list_table.class.php";
				new TSIListTable();
			}
			?>
		</div>
		<?php
	}
}
