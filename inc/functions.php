<?php
// If this file is called directly, abort.
if (!defined("ABSPATH")) {
	exit();
}

/*
 * LIST OF FUNCTION IN THIS FILE
 * 'combar_fs_body_class' - add plugin body classes to frontend
 * 'combar_fs_echoed' - echo plugin html to 'wp_footer'
 * 'combar_fs_main' - main plugin function, creates sidebar html
 * 'combar_fs_create_sidebar' - creates sidebar wrapper html
 * 'combar_fs_create_trigger' - creates sidebar trigger button html
 * 'combar_fs_create_close' - creates sidebar close button html
 * 'combar_fs_create_blocks' - creates sidebar blocks html
 * 'combar_fs_create_styles' - creates plugin frontend css variables inside '<style>' tag
 * 'combar_fs_setting_deafult' - sets default value to settings if empty
 */

function combar_fs_body_class($classes) {

	$settings = get_option('combar_fs');

	$side = combar_fs_setting_deafult($settings['side'], 'left');
	$classes[] = 'fs-side-' . esc_attr($side);

	return $classes;

}
add_filter('body_class', 'combar_fs_body_class');

function combar_fs_echoed() {
	$sidebar = combar_fs_main();
	echo wp_kses_normalize_entities($sidebar);
}
add_action('wp_footer', 'combar_fs_echoed', 100);

function combar_fs_main($settings = array() , $prev = false) {

	$settings = $settings['combar_fs'];

	if (empty($settings)) {
		$settings = get_option('combar_fs');
	}

	if (false == $prev) {
		// don't load on choosen pages
		$nopage = $settings['adv']['nopage'];
		$nopage = explode(',', $nopage);
		
		if ('except' == $settings['adv']['nopage_rule']) {
			if (is_page($nopage) && !empty($nopage)) {
				return;
			}
		} else {
			if (!is_page($nopage) && !empty($nopage)) {
				return;
			}	
		}

		// development mode
		if ('on' == $settings['dev'] && !current_user_can('administrator')) {
			return;
		}

		// loggen in users only
		if ('on' == $settings['adv']['login'] && !is_user_logged_in()) {
			return;
		}
		
	}
	
	// start of html
	//$html = '';
	if ('on' != $settings['trigger']['hide']) {
		$html .= combar_fs_create_trigger($settings['trigger'], $settings['main_color'], $settings['adv']);
	}
	$html .= combar_fs_create_sidebar($settings, $prev);
	$html .= combar_fs_create_styles($settings);

	return $html;

}

function combar_fs_create_sidebar($settings, $prev = false) {

	// wrapper
	$wrapperClassArray = array();
	$wrapperClassArray[] = 'combar-fs-wrapper';
	$wrapperClass = implode(' ', $wrapperClassArray);

	// overlay
	$overlayAtts = '';
	$overlayBg = combar_fs_setting_deafult($settings['overlay_color'], 'rgba(0,0,0,0.5)');
	$overlayAtts = ' style="background-color:' . esc_attr($overlayBg) . ';"';

	// container
	$settings['background_color'] = combar_fs_setting_deafult($settings['background_color'], '#fff');
	$settings['background_size'] = combar_fs_setting_deafult($settings['background_size'], 'cover');

	$containerAtts = '';
	$containerStyles = 'style="';
	$containerStyles .= 'background-color:' . $settings['background_color'] . ';';
	if ($settings['background_img']) {
		$bg_img = wp_get_attachment_image_url($settings['background_img'], 'full');
		$containerStyles .= 'background-image:url(' . esc_url($bg_img) . ');';
		$containerStyles .= 'background-size:' . esc_attr($settings['background_size']) . ';';
		$containerStyles .= 'background-repeat: repeat;';
	}
	$containerStyles .= '"';

	// close
	$settings['close']['position'] = combar_fs_setting_deafult($settings['close']['position'], 'outside');

	$html = '';
	$html .= '<div class="' . $wrapperClass . '">';
	$html .= '<div class="combar-fs-overlay"' . $overlayAtts . '></div>';

	if ('outside' == $settings['close']['position'] && 'on' != $settings['close']['disable']) {
		$html .= '<div class="fs-close-outside">';
		$html .= combar_fs_create_close($settings['close'], $settings['main_color']);
		$html .= '</div>';
	}

	$html .= '<div class="combar-fs-container" ' . $containerStyles . '>';
	$html .= '<div class="combar-fs-scrollbox">';

	if ('inside' == $settings['close']['position'] && 'on' != $settings['close']['disable']) {
		$html .= combar_fs_create_close($settings['close'], $settings['main_color']);
	}

	$html .= combar_fs_create_blocks($settings['elements'], $settings['social'], $prev);

	$html .= '</div>'; // scrollbox
	$html .= '</div>'; // container
	$html .= '</div>'; // wrapper
	return $html;

}

function combar_fs_create_trigger($trigger, $main_color, $advanced) {

	$html = '';
	$tag = 'div';
	$triggerAtts = '';

	if ('on' == $advanced['hash']) {
		$tag = 'a';
		$triggerAtts .= ' href="#fs-open"';
	}

	$side = combar_fs_setting_deafult($side, 'left');

	$triggerClass = array();
	$trigger['style'] = combar_fs_setting_deafult($trigger['style'], 'style_1');
	$trigger['align'] = combar_fs_setting_deafult($trigger['align'], 'center');
	$trigger['shape'] = combar_fs_setting_deafult($trigger['shape'], 'square');
	$trigger['align_mob'] = combar_fs_setting_deafult($trigger['align_mob'], 'center');
	$trigger['size'] = combar_fs_setting_deafult($trigger['size'], 'medium');
	$trigger['size_mob'] = combar_fs_setting_deafult($trigger['size_mob'], 'medium');

	$triggerClass[] = 'combar-fs-trigger';
	$triggerClass[] = 'trigger-' . esc_attr($trigger['style']);
	$triggerClass[] = 'shape-' . esc_attr($trigger['shape']);
	$triggerClass[] = 'trigger-align-' . esc_attr($trigger['align']);
	$triggerClass[] = 'trigger-align-mob-' . esc_attr($trigger['align_mob']);
	$triggerClass[] = 'trigger-size-' . esc_attr($trigger['size']);
	$triggerClass[] = 'size-mob-' . esc_attr($trigger['size_mob']);

	if ('' != $trigger['shadow']) {
		$triggerClass[] = 'fs-shadow';
	}

	if ('on' == $trigger['reverse']) {
		$triggerClass[] = 'trigger-reverse';
	}

	if ('on' == $trigger['hide_desk']) {
		$triggerClass[] = 'fs-hide-desk';
	}
	
	if ('on' == $trigger['hide_tab']) {
		$triggerClass[] = 'fs-hide-tab';
	}

	if ('on' == $trigger['hide_mob']) {
		$triggerClass[] = 'fs-hide-mob';
	}

	$html .= '<' . $tag . $triggerAtts . ' id="fs-trigger" class="' . implode(' ', $triggerClass) . '">';

	if ($trigger['icon']) {

		$trigger['icon_color'] = combar_fs_setting_deafult($trigger['icon_color'], '#fff');
		$main_color = combar_fs_setting_deafult($main_color, '#077fde');
		$trigger['icon_background'] = combar_fs_setting_deafult($trigger['icon_background'], $main_color);

		$html .= '<span class="fs-trigger-icon"';
		$html .= ' style="background:' . esc_attr($trigger['icon_background']) . '; color:' . esc_attr($trigger['icon_color']) . ';">';
		$html .= '<i class="' . esc_attr($trigger['icon']) . '"></i>';
		$html .= '</span>';

	}
	if ($trigger['title']) {

		$trigger['title_color'] = combar_fs_setting_deafult($trigger['title_color'], '#fff');
		$trigger['title_background'] = combar_fs_setting_deafult($trigger['title_background'], '#333');
		$trigger['weight'] = combar_fs_setting_deafult($trigger['weight'], 700);

		$html .= '<span class="fs-trigger-title"';
		$html .= ' style="';
		$html .= 'background:' . esc_attr($trigger['title_background']) . ';';
		$html .= 'color:' . esc_attr($trigger['title_color']) . ';';
		$html .= 'font-weight:' . esc_attr($trigger['weight']) . ';';
		$html .= '">';
		$html .= '<span>';
		$html .= esc_html(stripcslashes($trigger['title']));
		$html .= '</span>';
		$html .= '</span>';
	}

	$html .= '</' . $tag . '>';

	return $html;
}

function combar_fs_create_close($settings, $main_color) {

	// close button style
	$main_color = combar_fs_setting_deafult($main_color, '#077fde');
	$closeSettings = $settings;
	$closeSettings['background'] = combar_fs_setting_deafult($closeSettings['background'], $main_color);
	$closeSettings['size'] = combar_fs_setting_deafult($closeSettings['size'], 30);
	$closeSettings['side'] = combar_fs_setting_deafult($closeSettings['side'], 'left');
	$closeSettings['gap'] = combar_fs_setting_deafult($closeSettings['gap'], 5);

	$closeAtts = 'style="';
	$closeAtts .= 'background:' . esc_attr($closeSettings['background']) . ';';
	$closeAtts .= 'width:' . esc_attr($closeSettings['size']) . 'px;';
	$closeAtts .= 'height:' . esc_attr($closeSettings['size']) . 'px;';
	$closeAtts .= 'top: ' . esc_attr($closeSettings['gap']) . 'px;';
	$closeAtts .= esc_attr($closeSettings['side']) . ': ' . esc_attr($closeSettings['gap']) . 'px;';
	$closeAtts .= '"';

	// close icon style
	$closeColor = combar_fs_setting_deafult($closeSettings['color'], '#fff');
	$iconAtts = ' style="fill:' . esc_attr($closeColor) . '; stroke: ' . esc_attr($closeColor) . ';"';

	$html = '';
	$html .= '<div class="fs-close" ' . $closeAtts . '>';
	$html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 371.23 371.23" xmlns:v="https://vecta.io/nano"' . $iconAtts . '><path d="M371.23 21.213L350.018 0 185.615 164.402 21.213 0 0 21.213l164.402 164.402L0 350.018l21.213 21.212 164.402-164.402L350.018 371.23l21.212-21.212-164.402-164.403z"/></svg>';
	$html .= '</div>';

	return $html;

}

function combar_fs_create_blocks($elements, $social, $prev = false) {

	$html = '';

	foreach ($elements as $block) {

		$blockAtts = '';
		if ($block['background']) {
			$blockAtts .= ' style="';
			$blockAtts .= 'background-color:' . esc_attr($block['background']) . ';';
			$blockAtts .= '"';
		}

		// container
		$block['v_padding'] = combar_fs_setting_deafult($block['v_padding'], 15);
		$block['h_padding'] = combar_fs_setting_deafult($block['h_padding'], 15);
		$containerTag = 'div';
		$containerAtts = '';

		if ($block['link'] || $block['tel']) {
			$containerTag = 'a';
			$link = esc_url($block['link']);
			if ($block['tel']) {
				$link = 'tel:' . esc_attr($block['tel']);
			}
			$taraget = '_self';
			if ('on' == $block['blank']) {
				$taraget = '_blank';
			}
			if (true == $prev) {
				$taraget = '_self';
				$link = '#';
			}
			$containerAtts .= ' href="' . $link . '" target="' . $taraget . '"';
		}

		$containerAtts .= 'style="';
		$containerAtts .= 'padding:' . esc_attr($block['v_padding']) . 'px ' . esc_attr($block['h_padding']) . 'px;';
		$containerAtts .= '"';

		$iconAtts = '';
		if ($block['icon_color']) {
			$iconAtts .= ' style="';
			$iconAtts .= 'color:' . esc_attr($block['icon_color']) . ';';
			$iconAtts .= '"';
		}

		$titleAtts = '';
		if ($block['title_color']) {
			$titleAtts .= ' style="';
			$titleAtts .= 'color:' . esc_attr($block['title_color']) . ';';
			$titleAtts .= '"';
		}

		$subtitleAtts = '';
		if ($block['subtitle_color']) {
			$subtitleAtts .= ' style="';
			$subtitleAtts .= 'color:' . esc_attr($block['subtitle_color']) . ';';
			$subtitleAtts .= '"';
		}

		$heading = '';
		if ('on' == $block['reverse']) {
			if ($block['subtitle']) {
				$heading .= '<div class="fs-block-subtitle"' . $subtitleAtts . '>';
				$heading .= esc_html(stripcslashes($block['subtitle']));
				$heading .= '</div>';
			}
			if ($block['title']) {
				$heading .= '<div class="fs-block-title"' . $titleAtts . '>';
				$heading .= esc_html(stripcslashes($block['title']));
				$heading .= '</div>';
			}
		}
		else {
			if ($block['title']) {
				$heading .= '<div class="fs-block-title"' . $titleAtts . '>';
				$heading .= esc_html(stripcslashes($block['title']));
				$heading .= '</div>';
			}
			if ($block['subtitle']) {
				$heading .= '<div class="fs-block-subtitle"' . $subtitleAtts . '>';
				$heading .= esc_html(stripcslashes($block['subtitle']));
				$heading .= '</div>';
			}
		}

		$html .= '<div class="fs-block fs-type-' . esc_attr($block['type']);
		
		if ($block['align']) {
			$html .= ' fs-block-align-' . esc_attr($block['align']);
		}
		if ($block['head_align']) {
			$html .= ' fs-block-head-align-' . esc_attr($block['head_align']);
		}
		$html .= ' fs-block-' . esc_attr($block['id']);
		if ('on' == $block['dis_separator']) {
			$html .= ' fs-no-sep';
		}
		if ('on' == $block['hide_desktop']) {
			$html .= ' fs-hide-desk';
		}
		if ('on' == $block['hide_tablet']) {
			$html .= ' fs-hide-tab';
		}
		if ('on' == $block['hide_mobile']) {
			$html .= ' fs-hide-mob';
		}
		$html .= '"' . $blockAtts . '>';

		$html .= '<' . $containerTag . ' class="fs-block-container"' . $containerAtts . '>';

		// header
		if (!in_array($block['type'], array(
			'banner',
			'logo'
		))) {

			if ( ('none' != $block['icon'] && '' != $block['icon']) || $block['title'] || $block['subtitle']) {
				$html .= '<div class="fs-block-head">';
				if ('none' != $block['icon'] && '' != $block['icon']) {
					$html .= '<div class="fs-block-icon"' . $iconAtts . '>';
					$html .= '<i class="' . esc_attr($block['icon']) . '"></i>';
					$html .= '</div>';
				}
				if ($block['title'] || $block['subtitle']) {
					$html .= '<div class="fs-block-heading">';
					$html .= $heading;
					$html .= '</div>'; // heading
					
				}
				$html .= '</div>'; // head
			}
			
		}
		if (!in_array($block['type'], array(
			'text',
			'phone'
		))) {

			$html .= '<div class="fs-block-content">';
			
			$img_width = 'auto';
			if ('image' == $block['type']) {
				$img = $block['img'];
				if ($block['img_width']) {
					$img_width = 'width:' . $block['img_width'] . 'px;';
				}
				$html .= wp_get_attachment_image($img, 'full', false, array('style'=>$img_width));
			}
			else if ('logo' == $block['type']) {
				$img = $block['img'];
				if ($block['logo_width']) {
					$img_width = 'width:' . $block['logo_width'] . 'px;';
				}
				
				$html .= wp_get_attachment_image($img, 'full', false, array('style'=>$img_width));
				if ($block['title'] || $block['subtitle']) {
					$html .= '<div class="fs-block-heading">';
					$html .= $heading;
					$html .= '</div>';
				}
			}
			else if ('wysiwyg' == $block['type']) {
				if ($block['wysiwyg']) {
					$html .= wp_kses_post(stripcslashes(wpautop($block['wysiwyg'])));
				}
			}
			else if ('banner' == $block['type']) {

				// banner
				$img = $block['img'];
				$img = wp_get_attachment_image_url($img, 'full');

				$bannerAtts = '';
				if ($block['border'] || $img) {
					$bannerAtts .= ' style="';
					if ($block['border']) {
						$bannerAtts .= 'border: 3px solid ' . $block['border'] . ';';
					}
					if ($img) {
						$bannerAtts .= 'background-image: url(' . $img . ');';
					}
					$bannerAtts .= '"';
				}

				// button
				$btn = '';
				$btnAtts = '';
				if ($block['btn_bg'] || $block['btn_color']) {
					$btnAtts .= ' style="';
					if ($block['btn_bg']) {
						$btnAtts .= 'background:' . $block['btn_bg'] . ';';
					}
					if ($block['btn_color']) {
						$btnAtts .= 'color:' . $block['btn_color'] . ';';
					}
					$btnAtts .= '"';
				}

				if ($block['btn']) {
					$btn .= '<div class="fs-block-btn"' . $btnAtts . '>';
					$btn .= esc_html(stripcslashes($block['btn']));
					$btn .= '</div>';
				}

				$html .= '<div class="fs-block-banner"' . $bannerAtts . '>';
				$html .= '<div class="fs-block-banner-over"';
				if ($block['overlay']) {
					$html .= ' style="background: ' . esc_attr($block['overlay']) . '";';
				}
				$html .= '></div>
				';

				$html .= '<div class="fs-block-banner-content">';
				$html .= $heading;
				$html .= $btn;
				$html .= '</div>';

				$html .= '</div>'; // banner
				
			}
			else if ('shortcode' == $block['type']) {
				if ($block['shortcode']) {
					if (false == $prev) {
						$html .= do_shortcode($block['shortcode']);
					} else {
						$html .= '<div class="fs-fe-note">';
						$html .= __('Note: due to security concerns preview cannot display shortcodes.', 'combar-fs');
						$html .= ' ';
						$html .= __('To view the shortcode display, please check your website.', 'combar-fs');
						$html .= '</br>';
						$html .= __('You can use "Development Mode" in the General tab so that your visitors do not see the sidebar until you have finished working on it.', 'combar-fs');
						$html .= '</div>';
					}
				}
			}
			else if ('cf7' == $block['type']) {
				if ('' != $block['cf7']) {
					if (false == $prev) {
						$html .= do_shortcode('[contact-form-7 id="' . $block['cf7'] . '"]');
					} else {
						$html .= '<div class="fs-fe-note">';
						$html .= __('Note: due to security concerns preview cannot display forms.', 'combar-fs');
						$html .= ' ';
						$html .= __('To view the form display, please check your website.', 'combar-fs');
						$html .= '</br>';
						$html .= __('You can use "Development Mode" in the General tab so that your visitors do not see the sidebar until you have finished working on it.', 'combar-fs');
						$html .= '</div>';
					}
				}
			}
			else if ('wpforms' == $block['type']) {
				if ('' != $block['wpforms']) {
						if (false == $prev) {
					$html .= do_shortcode('[wpforms id="' . $block['wpforms'] . '"]');
					} else {
						$html .= '<div class="fs-fe-note">';
						$html .= __('Note: due to security concerns preview cannot display forms.', 'combar-fs');
						$html .= ' ';
						$html .= __('To view the form display, please check your website.', 'combar-fs');
						$html .= '</br>';
						$html .= __('You can use "Development Mode" in the General tab so that your visitors do not see the sidebar until you have finished working on it.', 'combar-fs');
						$html .= '</div>';
					}
				}				
			}
			else if ('social' == $block['type']) {
				$socialArray = $social;
				$socialJson = file_get_contents(plugin_dir_path(__DIR__) . 'assets/json/social.json');
				$socialJson = json_decode($socialJson);
				if (!empty($socialArray)) {

					$block['soc_style'] = combar_fs_setting_deafult($block['soc_style'], 'style_1');
					$block['shape'] = combar_fs_setting_deafult($block['shape'], 'square');
					$block['soc_gap'] = combar_fs_setting_deafult($block['soc_gap'], 0);
					$stretch = '';
					if ('on' == $block['stretch']) {
						$stretch = ' fs-stretch';
					}
					$html .= '<div class="fs-socials fs-socials-' . $block['soc_style'] . ' fs-socials-' . $block['shape'] . $stretch . '" style="gap:' . $block['soc_gap'] . 'px">';
					foreach ($socialArray as $key => $val) {
						if ('' != $val && $val) {
							$socialId = $key;
							$socialObject = $socialJson->$socialId;

							$socAtss = '';
							if ($block['soc_bg'] || $block['soc_color'] || $block['soc_size'] || $block['icon_size']) {
								$socAtss .= ' style="';


								if ($block['soc_size']) {
									$socAtss .= 'width:' . $block['soc_size'] . 'px;';
									$socAtss .= 'height:' . $block['soc_size'] . 'px;';
									if ('style_3' == $block['soc_style']) {
										$socAtss .= 'line-height: calc(' . $block['soc_size']  . 'px - 6px);';
									} else {
										$socAtss .= 'line-height:' . $block['soc_size'] . 'px;';
	
									}
									
								}

								if ($block['icon_size']) {
									$socAtss .= 'font-size:' . $block['icon_size'] . 'px;';
								}
								
								if ('style_2' == $block['soc_style']) {
									if ($block['soc_bg']) {
										$socAtss .= 'background:' . $block['soc_bg'] . ';';
									}
									if ($block['soc_color']) {
										$socAtss .= 'color:' . $block['soc_color'] . ';';
									}

								}
								else if ('style_3' == $block['soc_style']) {
									if ($block['soc_color']) {
										$socAtss .= 'background:' . $block['soc_color'] . ';';
									}
									if ($block['soc_bg']) {
										$socAtss .= 'color:' . $block['soc_bg'] . ';';
									}
								}
								$socAtss .= '"';
							}

							$target = '_blank';
							if (true == $prev) {
								$val = '#';
								$target = '_self';
							}

							$html .= '<a class="fs-' . esc_attr($socialId) . '" href="' . esc_attr($val) . '" target="' . $target . '" rel="nofollow" ' . $socAtss . '>';
							$html .= '<i class="' . esc_attr($socialObject->icon) . '"></i>';
							$html .= '</a>';
						}
					}
					$html .= '</div>';
				}
			}

			$html .= '</div>'; // content
			
		}
		$html .= '</' . $containerTag . '>'; // container
		$html .= '</div>'; // block
		

		
	}

	return $html;

}

function combar_fs_create_styles($settings) {

	// Size
	$settings['width'] = combar_fs_setting_deafult($settings['width'], 350);
	$settings['duration'] = combar_fs_setting_deafult($settings['duration'], 500);

	// Theme
	$settings['main_color'] = combar_fs_setting_deafult($settings['main_color'], '#077fde');
	$settings['secondary_color'] = combar_fs_setting_deafult($settings['secondary_color'], '#333333');

	// Typography
	$settings['title_size'] = combar_fs_setting_deafult($settings['title_size'], 25);
	$settings['title_weight'] = combar_fs_setting_deafult($settings['title_weight'], 700);
	$settings['subtitle_size'] = combar_fs_setting_deafult($settings['subtitle_size'], 18);
	$settings['subtitle_weight'] = combar_fs_setting_deafult($settings['subtitle_weight'], 400);
	$settings['icon_size'] = combar_fs_setting_deafult($settings['icon_size'], 50);
	$settings['icon_gap'] = combar_fs_setting_deafult($settings['icon_gap'], 15);
	$settings['content_gap'] = combar_fs_setting_deafult($settings['content_gap'], 15);

	// close
	$settings['close']['size'] = combar_fs_setting_deafult($settings['close']['size'], 30);
	$settings['close']['gap'] = combar_fs_setting_deafult($settings['close']['gap'], 5);

	// Separators
	$settings['sep_height'] = combar_fs_setting_deafult($settings['sep_height'], 2);
	$settings['sep_color'] = combar_fs_setting_deafult($settings['sep_color'], '#ccc');

	//trigger
	$settings['trigger']['v_gap'] = combar_fs_setting_deafult($settings['trigger']['v_gap'], 0);
	$settings['trigger']['h_gap'] = combar_fs_setting_deafult($settings['trigger']['h_gap'], 0);
	$settings['trigger']['v_gap_mob'] = combar_fs_setting_deafult($settings['trigger']['v_gap_mob'], 0);
	$settings['trigger']['h_gap_mob'] = combar_fs_setting_deafult($settings['trigger']['h_gap_mob'], 0);
	$settings['trigger']['title_background'] = combar_fs_setting_deafult($settings['trigger']['title_background'], '#333');

	$css = '';
	$css .= '<style>';

	$css .= ':root {';
	$css .= '--fs-width: ' . esc_attr($settings['width']) . 'px;';
	$css .= '--fs-transition: ' . esc_attr($settings['duration']) . 'ms;';
	$css .= '--fs-main: ' . esc_attr($settings['main_color']) . ';';
	$css .= '--fs-secondary: ' . esc_attr($settings['secondary_color']) . ';';
	$css .= '--fs-title: ' . esc_attr($settings['title_size']) . 'px;';
	$css .= '--fs-title-weight: ' . esc_attr($settings['title_weight']) . ';';
	$css .= '--fs-subtitle: ' . esc_attr($settings['subtitle_size']) . 'px;';
	$css .= '--fs-subtitle-weight: ' . esc_attr($settings['subtitle_weight']) . ';';
	$css .= '--fs-icon: ' . esc_attr($settings['icon_size']) . 'px;';
	$css .= '--fs-icon-gap: ' . esc_attr($settings['icon_gap']) . 'px;';
	$css .= '--fs-content-gap: ' . esc_attr($settings['content_gap']) . 'px;';
	$css .= '--fs-close-size: ' . esc_attr($settings['close']['size']) . 'px;';
	$css .= '--fs-close-gap: ' . esc_attr($settings['close']['gap']) . 'px;';
	$css .= '--fs-sep-height: ' . esc_attr($settings['sep_height']) . 'px;';
	$css .= '--fs-sep-color: ' . esc_attr($settings['sep_color']) . ';';
	$css .= '--fs-trigger-v: ' . esc_attr($settings['trigger']['v_gap']) . 'px;';
	$css .= '--fs-trigger-h: ' . esc_attr($settings['trigger']['h_gap']) . 'px;';
	$css .= '--fs-trigger-mob-v: ' . esc_attr($settings['trigger']['v_gap_mob']) . 'px;';
	$css .= '--fs-trigger-mob-h: ' . esc_attr($settings['trigger']['h_gap_mob']) . 'px;';
	$css .= '--fs-trigger-title: ' . esc_attr($settings['trigger']['title_background']) . ';';
	if ('' != $settings['trigger']['shadow']) {
		$css .= '--fs-trigger-shadow: ' . esc_attr($settings['trigger']['shadow']) . ';';
	}

	$css .= '}';

	$css .= '</style>';

	return $css;

}

function combar_fs_setting_deafult($setting, $default) {
	if ('' == $setting || !isset($setting)) {
		return $default;
	}
	else {
		return $setting;
	}
}