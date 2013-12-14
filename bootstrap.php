<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Plugin Name: (WCM) Top Spam IPs
 * Description: Adds a toolbar admin menu page that lists the top spam commenter IPs. Allows exporting to quickly build cross domain generated block lists in case of botnet attacks.
 * Plugin URI:  https://github.com/wecodemore/
 * Author:      Franz Josef Kaiser <wecodemore@gmail.com>
 * Author URI:  http://unserkaiser.com
 * Version:     1.0
 * Domain Path:
 * Text Domain: tsi_lang
 * Network:
 * License:     MIT
 */

register_uninstall_hook(    __FILE__, array( 'TSIBootstrap', 'on_uninstall' ) );
register_activation_hook(   __FILE__, array( 'TSIBootstrap', 'on_activate' ) );
register_deactivation_hook( __FILE__, array( 'TSIBootstrap', 'on_deactivate' ) );

add_action( 'plugins_loaded', array( 'TSIBootstrap', 'init' ), 5 );
final class TSIBootstrap
{
	protected static $instance = null;

	/**
	 * @wp-hook plugins_loaded
	 * @return null|TSIBootstrap
	 */
	public static function init()
	{
		null === self::$instance AND self::$instance = new self;
		return self::$instance;
	}

	/**
	 * @wp-hook plugins_loaded
	 */
	public function __construct()
	{
		add_action( 'auth_redirect', array( $this, 'load_files' ) );
		add_action( 'setup_theme', array( $this, 'load_l18n' ) );
	}

	/**
	 * @wp-hook auth_redirect
	 */
	public function load_files()
	{
		$files = glob( plugin_dir_path( __FILE__ )."inc/*.php" );
		$key = array_search(
			plugin_dir_path( __FILE__ )."inc/list_table.class.php",
			$files,
			true
		);
		unset( $files[ $key ] );
		foreach ( $files as $file )
			require_once $file;
	}

	/**
	 * @wp-hook setup_theme
	 * @return  bool
	 */
	public function load_l18n()
	{
		return load_plugin_textdomain(
			'tsi_lang',
			false,
			plugin_basename( dirname( __FILE__ ) ).'/lang'
		);
	}

	public function on_activate()
	{
	}

	public function on_deactivate()
	{
		$meta_key = 'tools_page_tsi_per_page';
		$query_args = array(
			'orderby'  => 'ID',
			'order'    => 'DESC',
			'meta_key' => $meta_key
		);
		$query = new WP_User_Query( $query_args );
		$users = $query->results;
		if ( empty( $users ) )
			return;

		$user_ids = wp_list_pluck( $users, 'ID' );

		foreach ( $user_ids as $user_id )
			delete_user_meta( $user_id, $meta_key );
	}

	public function on_uninstall()
	{
		if ( __FILE__ != WP_UNINSTALL_PLUGIN )
			return;

		$this->on_deactivate();
	}
}