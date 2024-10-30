<?php
// If this file is called directly, abort.
if (!defined("ABSPATH")) {
	exit();
}

/*
 * LIST OF FUNCTION IN THIS FILE
 * 'combar_fs_admin_body_class' - add plguin body classes to backend
 * 'combar_fs_element_fields' - create elements fields for backend
 * 'combar_fs_element_fields_ajax' - return elements fields with ajax
 * 'combar_fs_preview_ajax' - return preview html
 * 'combar_fs_update_options' - update plugin options with ajax
 * 'combar_fs_restart_options' - update plugin options to default
 */
 
 function combar_fs_admin_body_class($classes) {
	
	if ( isset($_GET['page'])) {
		if ( false !== strpos($_GET['page'], 'combar-fs')) {

			$settings = get_option('combar_fs');
			$side = combar_fs_setting_deafult($settings['side'], 'left');
			$classes.= ' fs-side-' . esc_attr($side);

		}
	}
	
	return $classes;

}
add_filter('admin_body_class', 'combar_fs_admin_body_class');

function combar_fs_element_fields($elem, $type = 'text', $ajax = false) {

	$blocks = get_option('combar_fs');
	$blocks = $blocks['elements'];
	
	// defaults
	// type
	if (!$elem['type'] || '' == $elem['type']) {
		$elem['type'] = $type;
	}

	// head align default
	if (!is_rtl() && !in_array($elem['type'], array(
		'banner',
		'logo'
	))) {
		$elem['head_align'] = combar_fs_setting_deafult($elem['head_align'], 'left');
	}
	else if (is_rtl() && !in_array($elem['type'], array(
		'banner',
		'logo'
	))) {
		$elem['head_align'] = combar_fs_setting_deafult($elem['head_align'], 'right');
	}

	// content align default
	if (in_array($elem['type'], array(
		'banner',
		'logo'
	))) {
		$elem['align'] = combar_fs_setting_deafult($elem['align'], 'center');
	} elseif (!in_array($elem['type'], array(
		'text',
		'phone'
	)) && !is_rtl()) {
		$elem['align'] = combar_fs_setting_deafult($elem['align'], 'left');
	} elseif (!in_array($elem['type'], array(
		'text',
		'phone'
	)) && is_rtl()) {
		$elem['align'] = combar_fs_setting_deafult($elem['align'], 'right');
	}
	
	// other defaults
	if ('social' == $elem['type']) {
		$elem['soc_style'] = combar_fs_setting_deafult($elem['soc_style'], 'style_1');
		$elem['shape'] = combar_fs_setting_deafult($elem['shape'], 'square');
	}
	else if ('phone' == $elem['type']) {
		$elem['icon'] = combar_fs_setting_deafult($elem['icon'], 'fas fa-phone');
		$elem['title'] = combar_fs_setting_deafult($elem['title'], __('Call us', 'combar-fs'));
	}

	// generate random id if new element
	if (!$elem['id'] || '' == $elem['id']) {
		
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		
		$unique = false;
		while(false == $unique) {
			$randomString = '';
			for ($i = 0;$i < 5;$i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1) ];
			}
			
			if (!array_key_exists($randomString, $blocks)) {
				$unique = true;
			}
		}
		
		$elem['id'] = $randomString;
		
	}

	$allowd = array(
		'text',
		'logo',
		'image',
		'banner',
		'social',
		'cf7',
		'wpforms',
		'shortcode',
		'phone',
		'wysiwyg'
	);

	// title
	switch ($elem['type']) {
		case 'text':
			$title = __('Heading block', 'combar-fs');
		break;
		case 'logo':
			$title = __('Logo block', 'combar-fs');
		break;
		case 'image':
			$title = __('Image block', 'combar-fs');
		break;
		case 'banner':
			$title = __('Banner block', 'combar-fs');
		break;
		case 'social':
			$title = __('Social networks block', 'combar-fs');
		break;
		case 'cf7':
			$title = __('Contact Form 7 block', 'combar-fs');
		break;
		case 'wpforms':
			$title = __('WPForms block', 'combar-fs');
		break;
		case 'shortcode':
			$title = __('Shortcode block', 'combar-fs');
		break;
		case 'phone':
			$title = __('Phone block', 'combar-fs');
		break;
		case 'wysiwyg':
			$title = __('WYSIWYG block', 'combar-fs');
		break;
	}

	$img = '';
	if ($elem['img']) {
		$img = wp_get_attachment_image_url($elem['img'], 'thumbnail');
	}

	$close = '';
	if (false == $ajax) {
		$close = ' close';
	}

	if (in_array($type, $allowd)) {
		$html = '';
		$html .= '<div class="element-item' . $close . '">';
		$html .= '<div class="element-head">';
		$html .= '<div class="element-title">';
		$html .= '<span class="dashicons dashicons-arrow-up-alt2"></span>';
		$html .= $title;
		$html .= '</div>'; // title
		$html .= '<div class="element-actions">';
		$html .= '<span id="moveElement" class="dashicons dashicons-move"></span>';
		$html .= '<span id="deleteElement" class="dashicons dashicons-trash"></span>';
		$html .= '</div>'; // actions
		$html .= '</div>'; // head
		$html .= '<div class="element-content">';
		$html .= '<input type="hidden" name="combar_fs[elements][' . $elem['id'] . '][type]" value="' . $elem['type'] . '" readonly \>';
		$html .= '<input type="hidden" name="combar_fs[elements][' . $elem['id'] . '][id]" value="' . $elem['id'] . '" readonly \>';

		// social note
		if (in_array($elem['type'], array(
			'social'
		))) {
			$html .= '<div class="option-title note">';
			$html .= __('In order to display the buttons enter your links in the "Social" tab.', 'combar-fs');
			$html .= '</div>';
		}
		
		if (in_array($elem['type'], array(
			'banner',
			'image',
			'logo'
		))) {
			$html .= '<div class="admin-option image-option">';
			$html .= '<span class="option-title">';
			$html .= __('Image', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][img]" placeholder="." value="' . esc_attr($elem['img']) . '" readonly />';
			$html .= '<div class="image-view">';
			$html .= '<img class="thumb" style="" src="' . esc_url($img) . '">';
			$html .= '<div class="img-actions">';
			$html .= '<input id="upload_image_button" type="button" style="" class="button-primary" value="' . __('Choose image', 'combar-fs') . '" />';
			$html .= '<input id="remove_image_button" type="button" class="button-primary reded" value="' . __('Remove image', 'combar-fs') . '" />';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</label>';
			$html .= '</div>';

			if (in_array($elem['type'], array(
				'logo'
			))) {
				// padding
				$html .= '<div class="admin-option unit-option">';
				$html .= '<label>';
				$html .= '<span class="option-title">';
				$html .= __('Logo width', 'combar-fs');
				$html .= '</span>';
				$html .= '<input type="number" min="0" name="combar_fs[elements][' . $elem['id'] . '][logo_width]" value="' . esc_attr($elem['logo_width']) . '"/>';
				$html .= '<span class="unit">px</span>';
				$html .= '</label>';
				$html .= '</div>';
			}

		}

		if (in_array($elem['type'], array(
				'image'
			))) {
				// padding
				$html .= '<div class="admin-option unit-option">';
				$html .= '<label>';
				$html .= '<span class="option-title">';
				$html .= __('Image width', 'combar-fs');
				$html .= '</span>';
				$html .= '<input type="number" min="0" name="combar_fs[elements][' . $elem['id'] . '][img_width]" value="' . esc_attr($elem['img_width']) . '"/>';
				$html .= '<span class="unit">px</span>';
				$html .= '</label>';
				$html .= '</div>';
		}

		if (!in_array($elem['type'], array(
			'banner',
			'logo'
		))) {
			// icon
			$html .= '<div class="admin-option icon-picker">';
			$html .= '<span class="option-title">';
			$html .= __('Icon', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<i class="' . esc_attr($elem['icon']) . '"></i>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][icon]" value="' . $elem['icon'] . '" class="fa-picker" readonly />';
			$html .= '</label>';
			$html .= '</div>';
		}

		/// title
		$html .= '<div class="admin-option">';
		$html .= '<label>';
		$html .= '<span class="option-title">';
		$html .= __('Title', 'combar-fs');
		$html .= '</span>';
		$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][title]" value="' . esc_attr($elem['title']) . '"/>';
		$html .= '</label>';
		$html .= '</div>';

		// text
		$html .= '<div class="admin-option">';
		$html .= '<label>';
		$html .= '<span class="option-title">';
		$html .= __('Subtitle', 'combar-fs');
		$html .= '</span>';
		$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][subtitle]" value="' . esc_attr($elem['subtitle']) . '"/>';
		$html .= '</label>';
		$html .= '</div>';

		if (in_array($elem['type'], array(
			'banner'
		))) {

			//btn text
			$html .= '<div class="admin-option">';
			$html .= '<label>';
			$html .= '<span class="option-title">';
			$html .= __('Button text', 'combar-fs');
			$html .= '</span>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][btn]" value="' . esc_attr($elem['btn']) . '"/>';
			$html .= '</label>';
			$html .= '</div>';

		}

		// reverse
		$html .= '<div class="admin-option radio-option">';
		$html .= '<label>';
		$html .= '<input type="checkbox" name="combar_fs[elements][' . $elem['id'] . '][reverse]" ' . checked('on', $elem['reverse'], false) . '/>';
		$html .= '<span>' . __('Reverse title and subtitle position', 'combar-fs') . '</span>';
		$html .= '</label>';
		$html .= '<small class="def">';
		$html .= __('By default title placed above subtitle.', 'combar-fs');
		$html .= '</small>';
		$html .= '</div>';

		if ('phone' == $elem['type']) {

			// phone number
			$html .= '<div class="admin-option">';
			$html .= '<label>';
			$html .= '<span class="option-title">';
			$html .= __('Phone number', 'combar-fs');
			$html .= '</span>';
			$html .= '<input type="tel" name="combar_fs[elements][' . $elem['id'] . '][tel]" value="' . esc_attr($elem['tel']) . '"/>';
			$html .= '</label>';
			$html .= '</div>';

		}

		if (!in_array($elem['type'], array(
			'social',
			'cf7',
			'wpforms',
			'shortcode',
			'phone',
			'wysiwyg'
		))) {

			// link
			$html .= '<div class="admin-option">';
			$html .= '<label>';
			$html .= '<span class="option-title">';
			$html .= __('Link (URL)', 'combar-fs');
			$html .= '</span>';
			$html .= '<input type="url" name="combar_fs[elements][' . $elem['id'] . '][link]" value="' . esc_url($elem['link']) . '"/>';
			$html .= '</label>';
			$html .= '<small class="def">';
			$html .= __('Enter valid URL to link block.', 'combar-fs');
			$html .= '</small>';
			$html .= '</div>';

			// new window
			$html .= '<div class="admin-option radio-option">';
			$html .= '<span class="option-title">';
			$html .= __('Link target', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="checkbox" name="combar_fs[elements][' . $elem['id'] . '][blank]" ' . checked('on', $elem['blank'], false) . '/>';
			$html .= '<span>' . __('Open link in a new window', 'combar-fs') . '</span>';
			$html .= '</label>';
			$html .= '</div>';

		}

		if (in_array($elem['type'], array(
			'wysiwyg'
		))) {

			$html .= '<div class="admin-option">';
			$html .= '<label>';
			$html .= '<span class="option-title">';
			$html .= __('Content', 'combar-fs');
			$html .= '</span>';
			$html .= '<span class="option-subtitle">';
			$html .= __('You must click on "' . __('Save changes', 'combar-fs') . '" button below the editor to apply changes.', 'combar-fs');
			$html .= '</span>';
			$html .= '<textarea id="fs-wysiwyg-' . $elem['id'] . '" class="fs-editor" name="combar_fs[elements][' . $elem['id'] . '][wysiwyg]" rows="5" hidden>' . wp_kses_post($elem['wysiwyg']) . '</textarea>';
			$html .= '<input type="button" id="saveWYS" class="button button-secondary" value="' . __('Save changes', 'combar-fs') . '" data-editor="fs-wysiwyg-' . $elem['id'] . '" />';
			$html .= '</label>';
			$html .= '<small class="def">';
			$html .= __('Enter valid URL to link block.', 'combar-fs');
			$html .= '</small>';
			$html .= '</div>';


		}
		
		if (in_array($elem['type'], array(
			'social'
		))) {

			// style
			$html .= '<div class="admin-option">';
			$html .= '<span class="option-title">';
			$html .= __('Social buttons style', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<select name="combar_fs[elements][' . $elem['id'] . '][soc_style]">';
			$html .= '<option value="style_1" ' . selected('style_1', $elem['soc_style'], false) . '>' . __('Style 1', 'combar-fs') . '</option>';
			$html .= '<option value="style_2" ' . selected('style_2', $elem['soc_style'], false) . '>' . __('Style 2', 'combar-fs') . '</option>';
			$html .= '<option value="style_3" ' . selected('style_3', $elem['soc_style'], false) . '>' . __('Style 3', 'combar-fs') . '</option>';
			$html .= '</select>';
			$html .= '</label>';
			$html .= '</div>';

			// shape
			$html .= '<div class="admin-option">';
			$html .= '<span class="option-title">';
			$html .= __('Social buttons shape', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<select name="combar_fs[elements][' . $elem['id'] . '][shape]">';
			$html .= '<option value="square" ' . selected('square', $elem['shape'], false) . '>' . __('Square', 'combar-fs') . '</option>';
			$html .= '<option value="rounded" ' . selected('rounded', $elem['shape'], false) . '>' . __('Rounded', 'combar-fs') . '</option>';
			$html .= '<option value="circle" ' . selected('circle', $elem['shape'], false) . '>' . __('Circle', 'combar-fs') . '</option>';
			$html .= '</select>';
			$html .= '</label>';
			$html .= '</div>';

			// gap
			$html .= '<div class="admin-option unit-option">';
			$html .= '<label>';
			$html .= '<span class="option-title">';
			$html .= __('Gap between buttons', 'combar-fs');
			$html .= '</span>';
			$html .= '<input type="number" min="0" name="combar_fs[elements][' . $elem['id'] . '][soc_gap]" value="' . esc_attr($elem['soc_gap']) . '"/>';
			$html .= '<span class="unit">px</span>';
			$html .= '</label>';
			$html .= '</div>';

			// Main color
			$html .= '<div class="admin-option">';
			$html .= '<span class="option-title">';
			$html .= __('Main color', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][soc_bg]" class="color-picker" data-alpha-enabled="true" value="' . esc_attr($elem['soc_bg']) . '"/>';
			$html .= '</label>';
			$html .= '</div>';

			// secondary color
			$html .= '<div class="admin-option">';
			$html .= '<span class="option-title">';
			$html .= __('Secondary color', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][soc_color]" class="color-picker" data-alpha-enabled="true" value="' . esc_attr($elem['soc_color']) . '"/>';
			$html .= '</label>';
			$html .= '</div>';
			
			// buttons size
			$html .= '<div class="admin-option unit-option">';
			$html .= '<label>';
			$html .= '<span class="option-title">';
			$html .= __('Buttons size', 'combar-fs');
			$html .= '</span>';
			$html .= '<input type="number" min="0" name="combar_fs[elements][' . $elem['id'] . '][soc_size]" value="' . esc_attr($elem['soc_size']) . '"/>';
			$html .= '<span class="unit">px</span>';
			$html .= '</label>';
			$html .= '</div>';

			// icon size
			$html .= '<div class="admin-option unit-option">';
			$html .= '<label>';
			$html .= '<span class="option-title">';
			$html .= __('Icon size', 'combar-fs');
			$html .= '</span>';
			$html .= '<input type="number" min="0" name="combar_fs[elements][' . $elem['id'] . '][icon_size]" value="' . esc_attr($elem['icon_size']) . '"/>';
			$html .= '<span class="unit">px</span>';
			$html .= '</label>';
			$html .= '</div>';

			// Stretch buttons
			$html .= '<div class="admin-option radio-option">';
			$html .= '<span class="option-title">';
			$html .= __('Stretch buttons', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="checkbox" name="combar_fs[elements][' . $elem['id'] . '][stretch]" ' . checked('on', $elem['stretch'], false) . '/>';
			$html .= '<span>' . __('Stretch buttons to fill all block', 'combar-fs') . '</span>';
			$html .= '</label>';
			$html .= '</div>';

		}

		if (in_array($elem['type'], array(
			'cf7'
		))) {

			if (defined('WPCF7_VERSION')) {

				$html .= '<div class="admin-option">';
				$html .= '<span class="option-title">';
				$html .= __('Select form', 'combar-fs');
				$html .= '</span>';
				$html .= '<label>';

				$cf7args = array(
					'post_type' => 'wpcf7_contact_form',
					'posts_per_page' => - 1,
				);

				$cf7_query = new WP_Query($cf7args);
				$html .= '<select name="combar_fs[elements][' . $elem['id'] . '][cf7]">';
				$html .= '<option value="">' . __('Select', 'combar-fs') . '</option>';
				while ($cf7_query->have_posts()):
					$cf7_query->the_post();
					$cf7Id = get_the_id();
					$cf7Title = get_the_title();
					$html .= '<option value="' . $cf7Id . '" ' . selected($cf7Id, $elem['cf7'], false) . '>ID: ' . $cf7Id . ' | ' . $cf7Title . '</option>';
				endwhile;
				$html .= '</select>';
				$html .= '</label>';
				$html .= '</div>';

			}
		}

		if (in_array($elem['type'], array(
			'shortcode'
		))) {
			// shortcode
			$html .= '<div class="admin-option">';
			$html .= '<label>';
			$html .= '<span class="option-title">';
			$html .= __('Shortcode', 'combar-fs');
			$html .= '</span>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][shortcode]" value="' . esc_attr($elem['shortcode']) . '"/>';
			$html .= '</label>';
			$html .= '</div>';

		}
		
		// wpforms
		if (in_array($elem['type'], array(
			'wpforms'
		))) {

			if (defined('WPFORMS_VERSION')) {

				$html .= '<div class="admin-option">';
				$html .= '<span class="option-title">';
				$html .= __('Select form', 'combar-fs');
				$html .= '</span>';
				$html .= '<label>';

				$wpformsargs = array(
					'post_type' => 'wpforms',
					'posts_per_page' => - 1,
				);

				$wpforms_query = new WP_Query($wpformsargs);
				$html .= '<select name="combar_fs[elements][' . $elem['id'] . '][wpforms]">';
				$html .= '<option value="">' . __('Select', 'combar-fs') . '</option>';
				while ($wpforms_query->have_posts()):
					$wpforms_query->the_post();
					$wpformsId = get_the_id();
					$wpformsTitle = get_the_title();
					$html .= '<option value="' . $wpformsId . '" ' . selected($wpformsId, $elem['wpforms'], false) . '>ID: ' . $wpformsId . ' | ' . $wpformsTitle . '</option>';
				endwhile;
				$html .= '</select>';
				$html .= '</label>';
				$html .= '</div>';

			}
		}

		// design options
		$html .= '<div class="design-options-toggle">' . __('Design options', 'combar-fs') . '</div>';
		$html .= '<div class="design-options" style="display: none">';

		// background
		$html .= '<div class="admin-option">';
		$html .= '<span class="option-title">';
		$html .= __('Background color', 'combar-fs');
		$html .= '</span>';
		$html .= '<label>';
		$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][background]" class="color-picker" data-alpha-enabled="true" value="' . esc_attr($elem['background']) . '"/>';
		$html .= '</label>';
		$html .= '</div>';

		if (!in_array($elem['type'], array(
			'banner',
			'logo'
		))) {

			// icon color
			$html .= '<div class="admin-option">';
			$html .= '<span class="option-title">';
			$html .= __('Icon color', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][icon_color]" class="color-picker" data-alpha-enabled="true" value="' . esc_attr($elem['icon_color']) . '"/>';
			$html .= '</label>';
			$html .= '</div>';

		}

		// title color
		$html .= '<div class="admin-option">';
		$html .= '<span class="option-title">';
		$html .= __('Title color', 'combar-fs');
		$html .= '</span>';
		$html .= '<label>';
		$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][title_color]" class="color-picker" data-alpha-enabled="true" value="' . esc_attr($elem['title_color']) . '"/>';
		$html .= '</label>';
		$html .= '</div>';

		// text color
		$html .= '<div class="admin-option">';
		$html .= '<span class="option-title">';
		$html .= __('Subtitle color', 'combar-fs');
		$html .= '</span>';
		$html .= '<label>';
		$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][subtitle_color]" class="color-picker" data-alpha-enabled="true" value="' . esc_attr($elem['subtitle_color']) . '"/>';
		$html .= '</label>';
		$html .= '</div>';

		// banner
		if (in_array($elem['type'], array(
			'banner'
		))) {

			//btn background
			$html .= '<div class="admin-option">';
			$html .= '<span class="option-title">';
			$html .= __('Button background', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][btn_bg]" class="color-picker" data-alpha-enabled="true" value="' . esc_attr($elem['btn_bg']) . '"/>';
			$html .= '</label>';
			$html .= '<small class="def">';
			$html .= __('Default', 'combar-fs') . ': ' . __('Main color', 'combar-fs') . '.';
			$html .= '</small>';
			$html .= '</div>';

			//btn text color
			$html .= '<div class="admin-option">';
			$html .= '<span class="option-title">';
			$html .= __('Button text color', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][btn_color]" class="color-picker" data-alpha-enabled="true" value="' . esc_attr($elem['btn_color']) . '"/>';
			$html .= '</label>';
			$html .= '<small class="def">';
			$html .= __('Default', 'combar-fs') . ': #fff.';
			$html .= '</small>';
			$html .= '</div>';

			//btn text color
			$html .= '<div class="admin-option">';
			$html .= '<span class="option-title">';
			$html .= __('Border color', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][border]" class="color-picker" data-alpha-enabled="true" value="' . esc_attr($elem['border']) . '"/>';
			$html .= '</label>';
			$html .= '<small class="def">';
			$html .= __('Leave blank to avoid.', 'combar-fs');
			$html .= '</small>';
			$html .= '</div>';

			// overlay
			$html .= '<div class="admin-option">';
			$html .= '<span class="option-title">';
			$html .= __('Overlay color', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="text" name="combar_fs[elements][' . $elem['id'] . '][overlay]" class="color-picker" data-alpha-enabled="true" value="' . esc_attr($elem['overlay']) . '"/>';
			$html .= '</label>';
			$html .= '<small class="def">';
			$html .= __('Default', 'combar-fs') . ': rgba(255,255,255,0.5).';
			$html .= '</small>';
			$html .= '</div>';

		}

		// padding
		$html .= '<div class="admin-option unit-option">';
		$html .= '<label>';
		$html .= '<span class="option-title">';
		$html .= __('Verticl padding', 'combar-fs');
		$html .= '</span>';
		$html .= '<input type="number" min="0" name="combar_fs[elements][' . $elem['id'] . '][v_padding]" value="' . esc_attr($elem['v_padding']) . '"/>';
		$html .= '<span class="unit">px</span>';
		$html .= '</label>';
		$html .= '<small class="def">';
		$html .= __('Default', 'combar-fs') . ': 15px.';
		$html .= '</small>';
		$html .= '</div>';

		$html .= '<div class="admin-option unit-option">';
		$html .= '<label>';
		$html .= '<span class="option-title">';
		$html .= __('Horizontal padding', 'combar-fs');
		$html .= '</span>';
		$html .= '<input type="number" min="0" name="combar_fs[elements][' . $elem['id'] . '][h_padding]" value="' . esc_attr($elem['h_padding']) . '"/>';
		$html .= '<span class="unit">px</span>';
		$html .= '</label>';
		$html .= '<small class="def">';
		$html .= __('Default', 'combar-fs') . ': 15px.';
		$html .= '</small>';
		$html .= '</div>';

		// align
		if (!in_array($elem['type'], array(
			'banner',
			'logo'
		))) {
			$html .= '<div class="admin-option icon-radio">';
			$html .= '<span class="option-title">';
			$html .= __('Heading align', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="radio" name="combar_fs[elements][' . $elem['id'] . '][head_align]" value="left" ' . checked('left', $elem['head_align'], false) . ' />';
			$html .= '<span class="dashicons dashicons-editor-alignleft" title="' . __('Left', 'combar-fs') . '"></span>';
			$html .= '</label>';
			$html .= '<label>';
			$html .= '<input type="radio" name="combar_fs[elements][' . $elem['id'] . '][head_align]" value="center" ' . checked('center', $elem['head_align'], false) . ' />';
			$html .= '<span class="dashicons dashicons-editor-aligncenter" title="' . __('Center', 'combar-fs') . '"></span>';
			$html .= '</label>';
			$html .= '<label>';
			$html .= '<input type="radio" name="combar_fs[elements][' . $elem['id'] . '][head_align]" value="right" ' . checked('right', $elem['head_align'], false) . ' />';
			$html .= '<span class="dashicons dashicons-editor-alignright" title="' . __('Right', 'combar-fs') . '"></span>';
			$html .= '</label>';
			$html .= '</div>';
		}

		if (!in_array($elem['type'], array(
			'text',
			'phone'
		))) {
			$html .= '<div class="admin-option icon-radio">';
			$html .= '<span class="option-title">';
			$html .= __('Content align', 'combar-fs');
			$html .= '</span>';
			$html .= '<label>';
			$html .= '<input type="radio" name="combar_fs[elements][' . $elem['id'] . '][align]" value="left" ' . checked('left', $elem['align'], false) . ' />';
			$html .= '<span class="dashicons dashicons-editor-alignleft" title="' . __('Left', 'combar-fs') . '"></span>';
			$html .= '</label>';
			$html .= '<label>';
			$html .= '<input type="radio" name="combar_fs[elements][' . $elem['id'] . '][align]" value="center" ' . checked('center', $elem['align'], false) . ' />';
			$html .= '<span class="dashicons dashicons-editor-aligncenter" title="' . __('Center', 'combar-fs') . '"></span>';
			$html .= '</label>';
			$html .= '<label>';
			$html .= '<input type="radio" name="combar_fs[elements][' . $elem['id'] . '][align]" value="right" ' . checked('right', $elem['align'], false) . ' />';
			$html .= '<span class="dashicons dashicons-editor-alignright" title="' . __('Right', 'combar-fs') . '"></span>';
			$html .= '</label>';
			$html .= '</div>';
		}

		// disable separator
		$html .= '<div class="admin-option radio-option">';
		$html .= '<span class="option-title">';
		$html .= __('Block separator', 'combar-fs');
		$html .= '</span>';
		$html .= '<label>';
		$html .= '<input type="checkbox" name="combar_fs[elements][' . $elem['id'] . '][dis_separator]" ' . checked('on', $elem['dis_separator'], false) . '/>';
		$html .= '<span>' . __('Disable separator for this block', 'combar-fs') . '</span>';
		$html .= '</label>';
		$html .= '</div>';

		$html .= '</div>'; // design options
		
		// device rules
		$html .= '<div class="admin-option icon-radio">';
		$html .= '<span class="option-title">';
		$html .= __('Devices display rules', 'combar-fs');
		$html .= '</span>';
		$html .= '<span class="option-subtitle">';
		$html .= __('Check to hide block on selected devices.', 'combar-fs');
		$html .= '</span>';
		$html .= '<label>';
		$html .= '<input type="checkbox" name="combar_fs[elements][' . $elem['id'] . '][hide_desktop]" ' . checked('left', $elem['hide_desktop'], false) . ' />';
		$html .= '<span class="dashicons dashicons-desktop" title="' . __('Hide on desktop', 'combar-fs') . '"></span>';
		$html .= '</label>';
		$html .= '<label>';
		$html .= '<input type="checkbox" name="combar_fs[elements][' . $elem['id'] . '][hide_tablet]" ' . checked('left', $elem['hide_tablet'], false) . ' />';
		$html .= '<span class="dashicons dashicons-tablet" title="' . __('Hide on tablet', 'combar-fs') . '"></span>';
		$html .= '</label>';
		$html .= '<label>';
		$html .= '<input type="checkbox" name="combar_fs[elements][' . $elem['id'] . '][hide_mobile]" ' . checked('left', $elem['hide_mobile'], false) . ' />';
		$html .= '<span class="dashicons dashicons-smartphone" title="' . __('Hide on mobile', 'combar-fs') . '"></span>';
		$html .= '</label>';

		$html .= '</div>';
		

		$html .= '</div>'; // content
		$html .= '</div>'; // item
		

		return $html;

	}
	else {
		return false;
	}

}

function combar_fs_element_fields_ajax() {

	$validate = check_ajax_referer('fs-elements', 'wpnonce');
	$data = $_POST;
	$html = combar_fs_element_fields(array() , $data['type'], true);
	die($html);

}
add_action('wp_ajax_nopriv_combar_fs_element_fields_ajax', 'combar_fs_element_fields_ajax');
add_action('wp_ajax_combar_fs_element_fields_ajax', 'combar_fs_element_fields_ajax');

function combar_fs_preview_ajax() {

	$data = $_POST;
	check_ajax_referer('fs-preview', 'prev_nonce');
	$html = combar_fs_main($data, true);
	die($html);

}
add_action('wp_ajax_nopriv_combar_fs_preview_ajax', 'combar_fs_preview_ajax');
add_action('wp_ajax_combar_fs_preview_ajax', 'combar_fs_preview_ajax');

function combar_fs_update_options() {

	check_ajax_referer('_wpnonce', '_wpnonce');
	$data = $_POST;
	unset($data['option_page'], $data['fs-preview'], $data['action'], $data['_wpnonce'], $data['_wp_http_referer']);
	if (update_option('combar_fs', $data)) {
		die(true);
	}
	else {
		die(false);
	}

}
add_action('wp_ajax_nopriv_combar_fs_update_options', 'combar_fs_update_options');
add_action('wp_ajax_combar_fs_update_options', 'combar_fs_update_options');

function combar_fs_restart_options() {

	$validate = check_ajax_referer('fs-restart', 'wpnonce');
	$data = combar_fs_defaults_options();

	if (update_option('combar_fs', $data)) {
		die(true);
	}
	else {
		die(false);
	}

}
add_action('wp_ajax_nopriv_combar_fs_restart_options', 'combar_fs_restart_options');
add_action('wp_ajax_combar_fs_restart_options', 'combar_fs_restart_options');