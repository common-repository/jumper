<?php
/**
 * Plugin Name: Jumper
 * Plugin URI: https://www.combar.co.il
 * Description: The ultimate WordPress flexible popup sidebar tool that is triggered by a floating button and displays a variety of contact options
 * Version: 1.1.2
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * Author: Combar
 * Author URI: https://www.combar.co.il/contact-us/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: combar-fs
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

/*
 * Set plugin version for internal use
*/
define('COMBAR_FS_VERSION', '1.1.2');
define('COMBAR_FS_DIR', plugin_dir_url(__FILE__));

/*
 * Add plugin to admin panel menu
*/
function combar_fs_admin_menu() {
	$icon = 'dashicons-align-pull-left';
	if (is_rtl()) {
		$icon = 'dashicons-align-pull-right';
	}
	add_menu_page(__('Jumper Floating Sidebar', 'combar-fs') , __('Jumper sidebar', 'combar-fs') , 'manage_options', 'combar-fs', 'combar_fs_callback', $icon, 80);	
}
add_action('admin_menu', 'combar_fs_admin_menu');

/*
 * Admin pages callback
*/
function combar_fs_callback() {
	require_once __DIR__ . '/admin/main.php';
}

/*
 * Remove all admin notices on edit mode
*/
function combar_fs_remove_updates_notice_on_editor() {
	if ( isset($_GET['page'])) {
		if ( false !== strpos($_GET['page'], 'combar-fs')) {
			if (!current_user_can('update_core')) {
				return;
			}
			add_action('init', create_function('$a', "remove_action( 'init', 'wp_version_check' );") , 2);
			add_filter('pre_option_update_core', '__return_null');
			add_filter('pre_site_transient_update_core', '__return_null');
		}
	}
}
//add_action('after_setup_theme', 'combar_fs_remove_updates_notice_on_editor');


/*
 * Add 'Setting' link in plugins list
*/
function combar_fs_add_action_links($links_array) {
	array_unshift($links_array, '<a href="' . admin_url('admin.php?page=combar-fs') . '">' . __('Settings') . '</a>');
	return $links_array;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__) , 'combar_fs_add_action_links');

/*
 * Register required settings
*/
function combar_fs_reg_settings() {

	//delete_option('combar_fs');
	

	register_setting('combar_fs', 'combar_fs', array(
		'type' => 'array'
	));

	if (empty(get_option('combar_fs'))) {
		$defaults = combar_fs_defaults_options();
		update_option('combar_fs', $defaults);
	}

}
add_action('admin_init', 'combar_fs_reg_settings');

/*
 * Set default options when active the plugin
*/
function combar_fs_defaults_options() {
	
	// default Logo element
	$deafultLogo = array();
	$deafultLogo['type'] = 'logo';
	$deafultLogo['id'] = 'eaR6V';
	$deafultLogo['title'] = __('Set your title', 'combar-fs');
	$deafultLogo['subtitle'] = __('You can also change this backgrond', 'combar-fs');
	$deafultLogo['background'] = 'rgb(7,127,222)';
	$deafultLogo['title_color'] = 'rgb(255,255,255)';
	$deafultLogo['subtitle_color'] = 'rgb(255,255,255)';
	$deafultLogo['align'] = 'center';
	
	// default Hello element
	$deafultWYS = array();
	$deafultWYS['type'] = 'wysiwyg';
	$deafultWYS['id'] = 'q5YVM';
	$deafultWYS['icon'] = 'fas fa-smile';
	$deafultWYS['title'] = __('Set your jumper', 'combar-fs');
	$deafultWYS['subtitle'] = __('Go to Blocks tab', 'combar-fs');
	$deafultWYS['wysiwyg'] = __('Go to Blocks tab to change the content of the sidebar.', 'combar-fs');
	
	// default Address element
	$deafultAddress = array();
	$deafultAddress['type'] = 'text';
	$deafultAddress['id'] = 'B1JFS';
	$deafultAddress['icon'] = 'fas fa-home';
	$deafultAddress['title'] = __('Visit us', 'combar-fs');
	$deafultAddress['subtitle'] = __('Manhattan, NY 10036, US', 'combar-fs');
	
	// default Phone element
	$deafultPhone = array();
	$deafultPhone['type'] = 'phone';
	$deafultPhone['id'] = 'Os6lp';
	$deafultPhone['icon'] = 'fas fa-phone';
	$deafultPhone['title'] = '001-234-5678';
	$deafultPhone['subtitle'] = __('Call us now', 'combar-fs');
	$deafultPhone['reverse'] = 'on';
	$deafultPhone['tel'] = '001-234-5678';

	// default WhatsApp element
	$deafultWa = array();
	$deafultWa['type'] = 'text';
	$deafultWa['id'] = 'S3mHJ';
	$deafultWa['icon'] = 'fab fa-whatsapp';
	$deafultWa['title'] = __('Click to chat', 'combar-fs');
	$deafultWa['subtitle'] = __('Contact us on WhatsApp', 'combar-fs');
	$deafultWa['reverse'] = 'on';
	$deafultWa['link'] = 'https://api.whatsapp.com/send?phone=12123456789';
	$deafultWa['icon_color'] = 'rgb(37,211,102)';
	$deafultWa['title_color'] = 'rgb(37,211,102)';

	// default Social element	
	$deafultSocial = array();
	$deafultSocial['type'] = 'social';
	$deafultSocial['id'] = '3coad';
	$deafultSocial['title'] = __('We are on social', 'combar-fs');
	$deafultSocial['soc_style'] = 'style_1';	
	$deafultSocial['shape'] = 'square';	
	$deafultSocial['soc_gap'] = 10;	
	$deafultSocial['soc_size'] = 50;	
	$deafultSocial['icon_size'] = 30;	
	$deafultSocial['title_color'] = 'rgb(119,119,119)';	

	
	$side = 'left';
	if (is_rtl()) {
		$side = 'right';		
	}

	// start deafult settings
	$defaultSetting = array();
	$defaultSetting['side'] = $side;
	$defaultSetting['disable_scroll'] = 'off';
	$defaultSetting['main_color'] = '#077fde';
	$defaultSetting['secondary_color'] = '#333333';
	$defaultSetting['overlay_color'] = 'rgba(0,0,0,0.5)';
	$defaultSetting['background_color'] = '#ffffff';
	$defaultSetting['background_color'] = '#ffffff';
	$defaultSetting['close']['position'] = 'outside';
	$defaultSetting['close']['side'] = $side;
	
	// default trigger settings
	$defaultSetting['trigger']['style'] = 'style_1';
	$defaultSetting['trigger']['shape'] = 'square';
	$defaultSetting['trigger']['icon'] = 'fas fa-comments';
	$defaultSetting['trigger']['title'] = __('Contact', 'combar-fs');
	$defaultSetting['trigger']['align'] = 'center';
	$defaultSetting['trigger']['align_mob'] = 'center';
	$defaultSetting['trigger']['size'] = 'medium';
	$defaultSetting['trigger']['size_mob'] = 'medium';
	
	// default socials
	$defaultSetting['social']['facebook'] = 'https://www.facebook.com/';
	$defaultSetting['social']['instagram'] = 'https://www.Instagram.com/';
	$defaultSetting['social']['linkedin'] = 'https://www.linkedIn.com/';
	$defaultSetting['social']['twitter'] = 'https://www.twitter.com/';
	$defaultSetting['social']['pinterest'] = 'https://www.pinterest.com/';
	$defaultSetting['social']['behance'] = '';
	$defaultSetting['social']['skype'] = '';
	$defaultSetting['social']['snapchat'] = '';
	$defaultSetting['social']['telegram'] = '';
	$defaultSetting['social']['tiktok'] = '';
	$defaultSetting['social']['vimeo'] = '';
	$defaultSetting['social']['vk'] = '';
	$defaultSetting['social']['whatsapp'] = '';
	
	// default elements
	$defaultSetting['elements']['eaR6V'] = $deafultLogo;
	$defaultSetting['elements']['q5YVM'] = $deafultWYS;
	$defaultSetting['elements']['B1JFS'] = $deafultAddress;
	$defaultSetting['elements']['Os6lp'] = $deafultPhone;
	$defaultSetting['elements']['S3mHJ'] = $deafultWa;
	$defaultSetting['elements']['3coad'] = $deafultSocial;

	// default advenced options	
	$defaultSetting['adv']['hash'] = 'on';
	$defaultSetting['adv']['esc'] = 'on';
	$defaultSetting['adv']['fontawesome'] = 'on';
	$defaultSetting['adv']['nopage_rule'] = 'except';
	$defaultSetting['adv']['minified'] = 'on';
	
	return $defaultSetting;
}

/*
 * Set default options when active the plugin
*/
function combar_fs_activate_redirect($plugin) {

	if ($plugin == plugin_basename(__FILE__)) {

		exit(wp_redirect(admin_url('admin.php?page=combar-fs')));

	}

}
add_action('activated_plugin', 'combar_fs_activate_redirect');

/*
 * Delete options from DB on uninstall
*/
function combar_fs_uninstall() {

	$settings = get_option('combar_fs');

	if ('on' == $settings['adv']['uninstall_delete']) {
		delete_option('combar_fs');
	}

}
register_uninstall_hook(__FILE__, 'combar_fs_uninstall');

/*
 * Enqueue plugin script and styles
*/
function combar_fs_scripts() {

	$combar_fs_path = COMBAR_FS_DIR;

	if (is_admin()) {
		if ( isset($_GET['page'])) {
			if ( false !== strpos($_GET['page'], 'combar-fs')) {
				// plugin admin files
				wp_enqueue_style('combar-fs-admin', $combar_fs_path . 'assets/css/combar-fs-admin.css', false, COMBAR_FS_VERSION, 'all');
				wp_enqueue_script('combar-fs-admin', $combar_fs_path . 'assets/js/combar-fs-admin.js', array(
					'jquery'
				) , COMBAR_FS_VERSION, true);

				// plugin style for preview
				wp_enqueue_style('combar-fs', $combar_fs_path . 'assets/css/combar-fs.css', false, COMBAR_FS_VERSION, 'all');

				wp_localize_script('combar-fs-admin', 'combar_fs', $atts = array(
					'saved' => __('Options saved successfully', 'combar-fs') ,
					'error' => __('An error occurred. Please try again or refresh the page.', 'combar-fs') ,
					'unvalid' => sprintf(__('Some of the entries entered do not meet the requirements.%sPlease check this and try again.', 'combar-fs') , '<br>') ,
					'caution' => __('CAUTION', 'combar-fs') ,
					'alert_reset' => __('This option will delete all the plugin data and return it to the default options.', 'combar-fs') ,
					'toContinue' => __('Do you wish to continue?', 'combar-sab') ,
					'confirmDelete' => __('Are you sure you want to delete this block?', 'combar-sab') ,
					'ajaxurl' => admin_url('admin-ajax.php') ,
				));

				// font awesome
				wp_enqueue_style('combar-fs-fa', $combar_fs_path . 'assets/fonts/FontAwesome/css/all.min.css', false, COMBAR_FS_VERSION, 'all');

				// jquery cookie
				wp_enqueue_script('jquery-cookie', $combar_fs_path . 'assets/js/jquery-cookie/jquery.cookie.js', array(
					'jquery'
				) , COMBAR_FS_VERSION, true);

				// jquery cookie
				wp_enqueue_script('jquery-validate', $combar_fs_path . 'assets/js/jquery-validate/jquery.validate.js', array(
					'jquery'
				) , COMBAR_FS_VERSION, true);

				// icon picker
				wp_enqueue_style('fontawesome-iconpicker', $combar_fs_path . 'assets/js/fontawesome-iconpicker/fontawesome-iconpicker.min.css', false, COMBAR_FS_VERSION, 'all');
				wp_enqueue_script('fontawesome-iconpicker', $combar_fs_path . 'assets/js/fontawesome-iconpicker/fontawesome-iconpicker.min.js', array(
					'jquery'
				) , COMBAR_FS_VERSION, true);

				// color picker
				wp_enqueue_script('wp-color-picker');
				wp_enqueue_style('wp-color-picker');
				wp_add_inline_script('wp-color-picker-alpha', 'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );');
				wp_enqueue_script('wp-color-picker-alpha', $combar_fs_path . 'assets/js/wp-color-picker-alpha/wp-color-picker-alpha.min.js', array(
					'jquery'
				) , COMBAR_FS_VERSION, true);

				// media
				wp_enqueue_media();
				
				// Tinymce editor
				wp_enqueue_editor();

				// sortable
				wp_enqueue_script('jquery-ui-sortable');
			}
		}
	}
	else {

		$settings = get_option('combar_fs');
		$settings['duration'] = combar_fs_setting_deafult($settings['duration'], 500);
		$settings['close']['size'] = combar_fs_setting_deafult($settings['close']['size'], 30);

		$minified = '';
		if ('on' == $settings['adv']['minified']) {
			$minified = '.min';
		}

		// plugin files
		wp_enqueue_style('combar-fs', $combar_fs_path . 'assets/css/combar-fs' . $minified . '.css', false, COMBAR_FS_VERSION, 'all');
		wp_enqueue_script('combar-fs', $combar_fs_path . 'assets/js/combar-fs' . $minified . '.js', array(
			'jquery'
		) , COMBAR_FS_VERSION, true);

		// set defaults
		$side = 'left';
		if (is_rtl()) {
			$side = 'right';		
		}
		$settings['duration'] = combar_fs_setting_deafult( $settings['duration'], 500);
		$settings['width'] = combar_fs_setting_deafult( $settings['width'], 350);
		$settings['close']['size'] = combar_fs_setting_deafult( $settings['close']['size'], 30);
		$settings['close']['gap'] = combar_fs_setting_deafult( $settings['close']['gap'], 5);
		
		// localize script
		wp_localize_script('combar-fs', 'combar_fs', $atts = array(
			'selector' => $settings['trigger']['selector'],
			'duration' => $settings['duration'],
			'scroll_dis' => $settings['disable_scroll'],
			'hash' => $settings['adv']['hash'],
			'esc' => $settings['adv']['esc'],
			'side' => $settings['side'],
			'width' => $settings['width'],
			'close_width' => $settings['close']['size'],
			'close_gap' => $settings['close']['gap']
		));

		// scrollbar
		wp_enqueue_style('jquery-scrollbar', $combar_fs_path . 'assets/js/jquery-scrollbar/jquery.scrollbar.css', false, COMBAR_FS_VERSION, 'all');
		wp_enqueue_script('jquery-scrollbar', $combar_fs_path . 'assets/js/jquery-scrollbar/jquery.scrollbar.min.js', array(
			'jquery'
		) , COMBAR_FS_VERSION, true);

		// font awesome
		if ('on' == $settings['adv']['fontawesome']) {
			wp_enqueue_style('combar-fs-fa', $combar_fs_path . 'assets/fonts/FontAwesome/css/all.min.css', false, COMBAR_FS_VERSION, 'all');
		}

	}

}
add_action('wp_enqueue_scripts', 'combar_fs_scripts');
add_action('admin_enqueue_scripts', 'combar_fs_scripts');

/*
 * load plugin text domain
*/
load_plugin_textdomain('combar-fs', false, dirname(plugin_basename(__FILE__)) . '/languages/');

/*
 * load plugin main functions
*/
require_once ('inc/functions.php');
require_once ('inc/admin-functions.php');