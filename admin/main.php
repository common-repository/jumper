<div class="wrap">

	<div class="inner">
		
		<form method="post" action="options.php" id="sf-form">
			<?php
			settings_errors();
				
			settings_fields('combar_fs');
			do_settings_sections( 'combar_fs' );
			$settings = get_option('combar_fs');
			
			$plugin_dir = COMBAR_FS_DIR;
			
			
			// defaults
			$settings['side'] = combar_fs_setting_deafult($settings['side'], 'left');
			$settings['disable_scroll'] = combar_fs_setting_deafult($settings['disable_scroll'], 'off');
			$settings['close']['position'] = combar_fs_setting_deafult($settings['close']['position'], 'outside');
			$settings['close']['side'] = combar_fs_setting_deafult($settings['close']['side'], 'left');
			$settings['trigger']['style'] = combar_fs_setting_deafult($settings['trigger']['style'], 'style_1');
			$settings['trigger']['shape'] = combar_fs_setting_deafult($settings['trigger']['shape'], 'square');
			$settings['trigger']['icon'] = combar_fs_setting_deafult($settings['trigger']['icon'], 'fas fa-comments');
			$settings['trigger']['align'] = combar_fs_setting_deafult($settings['trigger']['align'], 'center');
			$settings['trigger']['align_mob'] = combar_fs_setting_deafult($settings['trigger']['align_mob'], 'center');
			$settings['trigger']['size'] = combar_fs_setting_deafult($settings['trigger']['size'], 'medium');
			$settings['trigger']['size_mob'] = combar_fs_setting_deafult($settings['trigger']['size_mob'], 'medium');


			?>
			
			<div class="options-container">
			
				<div class="admin-tabs">
					<a class="admin-tab general-tab" href="#general" data-preview="sidebar"><?php _e('General', 'combar-fs');?></a>
					<a class="admin-tab trigger-tab" href="#trigger" data-preview="trigger"><?php _e('Button', 'combar-fs');?></a>
					<a class="admin-tab social-tab" href="#social" data-preview="sidebar"><?php _e('Social', 'combar-fs');?></a>
					<a class="admin-tab blocks-tab" href="#blocks" data-preview="sidebar"><?php _e('Blocks', 'combar-fs');?></a>
					<a class="admin-tab advanced-tab" href="#advanced" data-preview="sidebar"><?php _e('Advanced', 'combar-fs');?></a>
					<button class="submit-btn" type="submit">
						<span class="btn-txt"><?php _e('Save', 'combar-fs');?></span>
						<span class="btn-load"><img src="<?php echo esc_url($plugin_dir . '/assets/images/loader.svg');?>"></span>
					</button>
				</div>

				<div class="dev-mode-notice
					<?php
						if ('on' == $settings['dev']) {
							echo ' dev-on';
						}
					?>
					">
					<div class="dev-mode-notice-container">
						<span class="dashicons dashicons-warning"></span>
						<b>
						<?php
							_e('Warning: Development mode is active so only visitors logged in as administrator will see the sidebar on the site.', 'combar-fs');
							echo ' ';
							_e('To change this option go to the General tab.', 'combar-fs');
						?>
						</b>
					</div>
				</div>
				
				<div class="admin-box general hide">
					<h4><?php _e('Development mode', 'combar-fs'); ?></h4>
					<div class="admin-option radio-option">
						<span class="option-subtitle">
							<?php _e('Displays the sidebar on your website only to users who are logged in as administrator.', 'combar-fs'); ?>
							</br>
							<?php _e('Used to test the design before showing it to visitors.', 'combar-fs'); ?>
						</span>
						<label>
							<input type="checkbox" id="devModeInput" name="combar_fs[dev]" <?php checked( 'on', $settings['dev'] ); ?> />
							<span><?php _e('Enable development mode', 'combar-fs'); ?></span>
						</label>
					</div>
					
					<h4><?php _e('General settings', 'combar-fs'); ?></h4>
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Sidebar side', 'combar-fs');?>
						</span>
						<label>
							<input type="radio" class="sideSwitcher" name="combar_fs[side]" value="left" <?php checked( 'left', $settings['side'] ); ?> />
							<span><?php _e('Left', 'combar-fs');?></span>
						</label>
						<label>
							<input type="radio" class="sideSwitcher" name="combar_fs[side]" value="right" <?php checked( 'right', $settings['side'] ); ?> />
							<span><?php _e('Right', 'combar-fs');?></span>
						</label>
					</div>

					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Sidebar width', 'combar-fs');?>
							</span>
							<input type="number" name="combar_fs[width]" min="300" value="<?php echo esc_attr($settings['width']);?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Minimum', 'combar-fs') . ': 300px.'; ?>
							</br>
							<?php echo __('Default', 'combar-fs') . ': 350px.'; ?>
						</small>			
					</div>
					
					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Enterence duration', 'combar-fs');?>
							</span>
							<input type="number" name="combar_fs[duration]" min="0" value="<?php echo esc_attr($settings['duration']);?>"/>
							<span class="unit">ms</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 500ms.'; ?>
						</small>
					</div>

					<h4><?php _e('Theme', 'combar-fs');?></h4>
					
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Main color', 'combar-fs');?>
						</span>
						<label>
							<input type="text" name="combar_fs[main_color]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['main_color']);?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': <span style="color: #077fde;">#077fde</span>.'; ?>
						</small>
						
					</div>	

					<div class="admin-option">
						<span class="option-title">
							<?php _e('Secondary color', 'combar-fs');?>
						</span>
						<label>
							<input type="text" name="combar_fs[secondary_color]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['secondary_color']);?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': <span style="color: #333;">#333333</span>.'; ?>
						</small>
					</div>

					<div class="admin-option">
						<span class="option-title">
							<?php _e('Sidebar background color', 'combar-fs');?>
						</span>
						<label>
							<input type="text" name="combar_fs[background_color]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['background_color']);?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': #ffffff.'; ?>
						</small>
					</div>	

					<div class="admin-option image-option">
						<span class="option-title">
							<?php _e('Background image', 'combar-fs'); ?>
						</span>
						
						<label>
						<?php
							$bg_img = '';
							if ($settings['background_img']) {
								$bg_img = wp_get_attachment_image_url($settings['background_img'], 'thumbnail');
							}
						?>
						
						<input type="text" name="combar_fs[background_img]" placeholder="." value="<?php echo esc_attr($settings['background_img']); ?>" readonly />
						<div class="image-view">
							<img class="thumb" style="" src="<?php echo esc_url($bg_img); ?>">
							<div class="img-actions">
								<input id="upload_image_button" type="button" style="" class="button-primary" value="<?php _e('Choose image', 'combar-fs'); ?>" />
								<input id="remove_image_button" type="button" class="button-primary reded" value="<?php _e('Remove image', 'combar-fs'); ?>" />						
							</div>
						</div>
						
						
						<div class="admin-option inner-option">
							<span class="option-title">
								<?php _e('Background image size', 'combar-fs'); ?>
							</span>
							<select name="combar_fs[background_size]">
								<option value="cover" <?php selected( 'cover', $settings['background_size'] ); ?>><?php _e('Cover', 'combar-fs'); ?></option>
								<option value="contain" <?php selected( 'contain', $settings['background_size'] ); ?>><?php _e('Contain', 'combar-fs'); ?></option>
								<option value="auto" <?php selected( 'auto', $settings['background_size'] ); ?>><?php _e('Auto', 'combar-fs'); ?></option>
							</select>
						</div>
						
						</label>
						
					</div>
					
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Overlay color', 'combar-fs'); ?>
						</span>
						<label>
							<input type="text" name="combar_fs[overlay_color]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['overlay_color']); ?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': rgba(0, 0, 0, 0.5).'; ?>
						</small>
					</div>
					
					<h4><?php _e('Typography', 'combar-fs'); ?></h4>
					
					
					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Titles size', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[title_size]" min="1" value="<?php echo esc_attr($settings['title_size']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 25px.'; ?>
						</small>
					</div>
					
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Titles weight', 'combar-fs'); ?>
						</span>
						<label>
							<select name="combar_fs[title_weight]">
								<option value=""><?php _e('Choose', 'combar-fs'); ?></option>
								<option value="100" <?php selected( '100', $settings['title_weight'] ); ?>>100</option>
								<option value="200" <?php selected( '200', $settings['title_weight'] ); ?>>200</option>
								<option value="300" <?php selected( '300', $settings['title_weight'] ); ?>>300</option>
								<option value="400" <?php selected( '400', $settings['title_weight'] ); ?>>400</option>
								<option value="500" <?php selected( '500', $settings['title_weight'] ); ?>>500</option>
								<option value="600" <?php selected( '600', $settings['title_weight'] ); ?>>600</option>
								<option value="700" <?php selected( '700', $settings['title_weight'] ); ?>>700</option>
								<option value="800" <?php selected( '800', $settings['title_weight'] ); ?>>800</option>
								<option value="900" <?php selected( '900', $settings['title_weight'] ); ?>>900</option>
							</select>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 700.'; ?>
						</small>
					</div>

					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Subtitles size', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[subtitle_size]" min="1" value="<?php echo esc_attr($settings['subtitle_size']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 18px.'; ?>
						</small>
					</div>	
					
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Subtitles weight', 'combar-fs'); ?>
						</span>
						<label>
							<select name="combar_fs[subtitle_weight]">
								<option value=""><?php _e('Choose', 'combar-fs'); ?></option>
								<option value="100" <?php selected( '100', $settings['subtitle_weight'] ); ?>>100</option>
								<option value="200" <?php selected( '200', $settings['subtitle_weight'] ); ?>>200</option>
								<option value="300" <?php selected( '300', $settings['subtitle_weight'] ); ?>>300</option>
								<option value="400" <?php selected( '400', $settings['subtitle_weight'] ); ?>>400</option>
								<option value="500" <?php selected( '500', $settings['subtitle_weight'] ); ?>>500</option>
								<option value="600" <?php selected( '600', $settings['subtitle_weight'] ); ?>>600</option>
								<option value="700" <?php selected( '700', $settings['subtitle_weight'] ); ?>>700</option>
								<option value="800" <?php selected( '800', $settings['subtitle_weight'] ); ?>>800</option>
								<option value="900" <?php selected( '900', $settings['subtitle_weight'] ); ?>>900</option>
							</select>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 400.'; ?>
						</small>
					</div>

					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Icons size', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[icon_size]" min="1" value="<?php echo esc_attr($settings['icon_size']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 50px.'; ?>
						</small>
					</div>

					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Gap between icon and headline', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[icon_gap]" value="<?php echo esc_attr($settings['icon_gap']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 15px.'; ?>
						</small>
					</div>

					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Gap between heading and content', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[content_gap]" value="<?php echo esc_attr($settings['content_gap']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 15px.'; ?>
						</small>
					</div>
					
					<h4><?php _e('Separators', 'combar-fs'); ?></h4>


					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Separator height', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[sep_height]" min="0" value="<?php echo esc_attr($settings['sep_height']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 2px.'; ?>
						</small>
					</div>
					
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Separator color', 'combar-fs'); ?>
						</span>
						<label>
							<input type="text" name="combar_fs[sep_color]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['sep_color']); ?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': #ccc.'; ?>
						</small>
					</div>
					
					<h4><?php _e('Close button', 'combar-fs'); ?></h4>					
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Position', 'combar-fs'); ?>
						</span>
						<label>
							<input type="radio" name="combar_fs[close][position]" value="outside" <?php checked( 'outside', $settings['close']['position'] ); ?> />
							<span><?php _e('Outside sidebar', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[close][position]" value="inside" <?php checked( 'inside', $settings['close']['position'] ); ?> />
							<span><?php _e('Inside sidebar', 'combar-fs'); ?></span>
						</label>
					</div>

					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Side', 'combar-fs'); ?>
						</span>
						<label>
							<input type="radio" name="combar_fs[close][side]" value="left" <?php checked( 'left', $settings['close']['side'] ); ?> />
							<span><?php _e('Left', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[close][side]" value="right" <?php checked( 'right', $settings['close']['side'] ); ?> />
							<span><?php _e('Right', 'combar-fs'); ?></span>
						</label>
					</div>
					
					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Size', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[close][size]" min="30" value="<?php echo esc_attr($settings['close']['size']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Minimum', 'combar-fs') . ': 30px.'; ?>
							</br>
							<?php echo __('Default', 'combar-fs') . ': 30px.'; ?>
						</small>
					</div>

					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Gap from edges', 'combar-fs'); ?>
							</span>
							<span class="option-subtitle">
								<?php _e('Gap from top and side edges of the selected position.', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[close][gap]"  min="0" value="<?php echo esc_attr($settings['close']['gap']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 5px.'; ?>
						</small>
					</div>
					
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Background color', 'combar-fs'); ?>
						</span>
						<label>
							<input type="text" name="combar_fs[close][background]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['close']['background']); ?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': ' . __('Main Color', 'combar-fs'); ?>
						</small>
					</div>

					<div class="admin-option">
						<span class="option-title">
							<?php _e('Icon color', 'combar-fs'); ?>
						</span>
						<label>
							<input type="text" name="combar_fs[close][color]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['close']['color']); ?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': #ffffff.'; ?>
						</small>
					</div>
					
					<div class="admin-option radio-option">
						<label>
							<input type="checkbox" name="combar_fs[close][disable]" <?php checked( 'on', $settings['close']['disable'] ); ?> />
							<span><?php _e('Hide close button', 'combar-fs'); ?></span>
						</label>
						<small class="def">
							<?php _e('User still can close the sidebar by clicking the overlay layer.', 'combar-fs'); ?>
						</small>
					</div>
					
					<h4><?php _e('Scroll behavior', 'combar-fs'); ?></h4>
	
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Disable page scroll when sidebar is open.', 'combar-fs'); ?>
						</span>
						<label>
							<input type="radio" name="combar_fs[disable_scroll]" value="off" <?php checked( 'off', $settings['disable_scroll'] ); ?> />
							<span><?php _e('Off', 'combar-fs'); ?></span>
						</label>
						<label >
							<input type="radio" name="combar_fs[disable_scroll]" value="on" <?php checked( 'on', $settings['disable_scroll'] ); ?> />
							<span><?php _e('On', 'combar-fs'); ?></span>
						</label>
					</div>	
							
				</div>	

				<div class="admin-box trigger hide">
					
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Button style', 'combar-fs'); ?>
						</span>
						<label>
							<input type="radio" name="combar_fs[trigger][style]" value="style_1" <?php checked( 'style_1', $settings['trigger']['style'] ); ?> />
							<span><?php _e('Vertical button', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][style]" value="style_2" <?php checked( 'style_2', $settings['trigger']['style'] ); ?> />
							<span><?php _e('Horizontal button', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][style]" value="style_3" <?php checked( 'style_3', $settings['trigger']['style'] ); ?> />
							<span><?php _e('Icon button with hover tooltip', 'combar-fs'); ?></span>
						</label>
					</div>
					
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Button size', 'combar-fs'); ?>
						</span>
						<label>
							<input type="radio" name="combar_fs[trigger][size]" value="small" <?php checked( 'small', $settings['trigger']['size'] ); ?> />
							<span><?php _e('Small', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][size]" value="medium" <?php checked( 'medium', $settings['trigger']['size'] ); ?> />
							<span><?php _e('Medium', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][size]" value="big" <?php checked( 'big', $settings['trigger']['size'] ); ?> />
							<span><?php _e('Big', 'combar-fs'); ?></span>
						</label>
					</div>
					
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Button corners', 'combar-fs'); ?>
						</span>
						<label>
							<input type="radio" name="combar_fs[trigger][shape]" value="square" <?php checked( 'square', $settings['trigger']['shape'] ); ?> />
							<span><?php _e('Square', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][shape]" value="rounded" <?php checked( 'rounded', $settings['trigger']['shape'] ); ?> />
							<span><?php _e('Rounded', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][shape]" value="circle" <?php checked( 'circle', $settings['trigger']['shape'] ); ?> />
							<span><?php _e('Circle', 'combar-fs'); ?></span>
						</label>
					</div>
					
					<div class="admin-option">
						<label>
							<span class="option-title">
								<?php _e('Title', 'combar-fs'); ?>
							</span>
							<span class="option-subtitle">
								<?php _e('Leave blank to hide.', 'combar-fs'); ?>
							</span>
							<input type="text" name="combar_fs[trigger][title]" value="<?php echo esc_attr($settings['trigger']['title']); ?>"/>
						</label>
					</div>
								
					<div class="admin-option icon-picker no-blank">
	
						<span class="option-title">
							<?php _e('Icon', 'combar-fs'); ?>
						</span>
						<label>
						<i class="<?php echo esc_attr($settings['trigger']['icon']); ?>"></i>
						<input type="text" name="combar_fs[trigger][icon]" value="<?php echo esc_attr($settings['trigger']['icon']); ?>"  placeholder="<?php _e('Click to choose...', 'combar-sab'); ?>" class="fa-picker" readonly />
						</label>
					</div>
					
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Title weight', 'combar-fs'); ?>
						</span>
						<label>
							<select name="combar_fs[trigger][weight]">
								<option value=""><?php _e('Choose', 'combar-fs'); ?></option>
								<option value="100" <?php selected( '100', $settings['trigger']['weight'] ); ?>>100</option>
								<option value="200" <?php selected( '200', $settings['trigger']['weight'] ); ?>>200</option>
								<option value="300" <?php selected( '300', $settings['trigger']['weight'] ); ?>>300</option>
								<option value="400" <?php selected( '400', $settings['trigger']['weight'] ); ?>>400</option>
								<option value="500" <?php selected( '500', $settings['trigger']['weight'] ); ?>>500</option>
								<option value="600" <?php selected( '600', $settings['trigger']['weight'] ); ?>>600</option>
								<option value="700" <?php selected( '700', $settings['trigger']['weight'] ); ?>>700</option>
								<option value="800" <?php selected( '800', $settings['trigger']['weight'] ); ?>>800</option>
								<option value="900" <?php selected( '900', $settings['trigger']['weight'] ); ?>>900</option>
							</select>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 700.'; ?>
						</small>
					</div>
					
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Button reversal', 'combar-fs'); ?>
						</span>
						<label>
							<input type="checkbox" name="combar_fs[trigger][reverse]" <?php checked( 'on', $settings['trigger']['reverse'] ); ?> />
							<span><?php _e('Reverse icon and title position', 'combar-fs'); ?></span>
						</label>
						<small class="def">
							<?php echo __('By default icon placed before title.', 'combar-fs'); ?>
							</br>
							<?php echo __('This option will replace their position.', 'combar-fs'); ?>
						</small>
					</div>
					
					<h4><?php _e('Colors', 'combar-fs'); ?></h4>
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Icon color', 'combar-fs'); ?>
						</span>
						<label>
							<input type="text" name="combar_fs[trigger][icon_color]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['trigger']['icon_color']); ?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': #ffffff.'; ?>
						</small>
					</div>

					<div class="admin-option">
						<span class="option-title">
							<?php _e('Icon background', 'combar-fs'); ?>
						</span>
						<label>
							<input type="text" name="combar_fs[trigger][icon_background]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['trigger']['icon_background']); ?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': ' . __('Main Color', 'combar-fs'); ?>
						</small>
					</div>
					
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Title color', 'combar-fs'); ?>
						</span>
						<label>
							<input type="text" name="combar_fs[trigger][title_color]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['trigger']['title_color']); ?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': #ffffff.'; ?>
						</small>
					</div>

					<div class="admin-option">
						<span class="option-title">
							<?php _e('Title background', 'combar-fs'); ?>
						</span>
						<label>
							<input type="text" name="combar_fs[trigger][title_background]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['trigger']['title_background']); ?>"/>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': <span style="color: #333;">#333333</span>.'; ?>
						</small>
					</div>

					<div class="admin-option">
						<span class="option-title">
							<?php _e('Shadow color', 'combar-fs'); ?>
						</span>
						<label>
							<input type="text" name="combar_fs[trigger][shadow]" class="color-picker" data-alpha-enabled="true" value="<?php echo esc_attr($settings['trigger']['shadow']); ?>"/>
						</label>
						<small class="def">
							<?php _e('Leave blank to avoid.', 'combar-fs'); ?>
						</small>
					</div>
					
					<h4><?php _e('Alignment', 'combar-fs'); ?></h4>
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Button align', 'combar-fs'); ?>
						</span>
						<label>
							<input type="radio" name="combar_fs[trigger][align]" value="top" <?php checked( 'top', $settings['trigger']['align'] ); ?> />
							<span><?php _e('Top', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][align]" value="center" <?php checked( 'center', $settings['trigger']['align'] ); ?> />
							<span><?php _e('Center', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][align]" value="bottom" <?php checked( 'bottom', $settings['trigger']['align'] ); ?> />
							<span><?php _e('Bottom', 'combar-fs'); ?></span>
						</label>
					</div>
					
					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Gap from top/bottom edges', 'combar-fs'); ?>
							</span>
							<span class="option-subtitle">
								<?php _e('Does not affect when alignment is "center".', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[trigger][v_gap]" min="0" value="<?php echo esc_attr($settings['trigger']['v_gap']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 0px.'; ?>
						</small>
					</div>

					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Gap from left/right edges', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[trigger][h_gap]" min="0" value="<?php echo esc_attr($settings['trigger']['h_gap']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 0px.'; ?>
						</small>
					</div>
					
					<h4><?php _e('Mobile devices', 'combar-fs'); ?></h4>
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Button size', 'combar-fs'); ?>
							<?php _e('on mobile devices', 'combar-fs'); ?>
						</span>
						<label>
							<input type="radio" name="combar_fs[trigger][size_mob]" value="small" <?php checked( 'small', $settings['trigger']['size_mob'] ); ?> />
							<span><?php _e('Small', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][size_mob]" value="medium" <?php checked( 'medium', $settings['trigger']['size_mob'] ); ?> />
							<span><?php _e('Medium', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][size_mob]" value="big" <?php checked( 'big', $settings['trigger']['size_mob'] ); ?> />
							<span><?php _e('Big', 'combar-fs'); ?></span>
						</label>
					</div>
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Button align', 'combar-fs'); ?>
							<?php _e('on mobile devices', 'combar-fs'); ?>
						</span>
						<label>
							<input type="radio" name="combar_fs[trigger][align_mob]" value="top" <?php checked( 'top', $settings['trigger']['align_mob'] ); ?> />
							<span><?php _e('Top', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][align_mob]" value="center" <?php checked( 'center', $settings['trigger']['align_mob'] ); ?> />
							<span><?php _e('Center', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[trigger][align_mob]" value="bottom" <?php checked( 'bottom', $settings['trigger']['align_mob'] ); ?> />
							<span><?php _e('Bottom', 'combar-fs'); ?></span>
						</label>
					</div>
					
					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
								<?php _e('Gap from top/bottom edges', 'combar-fs'); ?>
								<?php _e('on mobile devices', 'combar-fs'); ?>
							</span>
							<span class="option-subtitle">
								<?php _e('Does not affect when alignment is "center".', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[trigger][v_gap_mob]" min="0" value="<?php echo esc_attr($settings['trigger']['v_gap_mob']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 0px.'; ?>
						</small>
					</div>

					<div class="admin-option unit-option">
						<label>
							<span class="option-title">
							<?php _e('Gap from left/right edges', 'combar-fs'); ?>
							<?php _e('on mobile devices', 'combar-fs'); ?>
							</span>
							<input type="number" name="combar_fs[trigger][h_gap_mob]" min="0" value="<?php echo esc_attr($settings['trigger']['h_gap_mob']); ?>"/>
							<span class="unit">px</span>
						</label>
						<small class="def">
							<?php echo __('Default', 'combar-fs') . ': 0px.'; ?>
						</small>
					</div>
					
					<h4><?php _e('Display rules', 'combar-fs'); ?></h4>
					<div class="admin-option radio-option">
						<span class="option-subtitle">
							<?php _e('Does not affect preview.', 'combar-fs'); ?>
						</span>
						<label>
							<input type="checkbox" name="combar_fs[trigger][hide_desk]" <?php checked( 'on', $settings['trigger']['hide_desk'] ); ?> />
							<span><?php _e('Hide trigger button on Desktop', 'combar-fs'); ?></span>
						</label>
					</div>
					<div class="admin-option radio-option">
						<label>
							<input type="checkbox" name="combar_fs[trigger][hide_tab]" <?php checked( 'on', $settings['trigger']['hide_tab'] ); ?> />
							<span><?php _e('Hide trigger button on Tablet', 'combar-fs'); ?></span>
						</label>
					</div>	
					<div class="admin-option radio-option">
						<label>
							<input type="checkbox" name="combar_fs[trigger][hide_mob]" <?php checked( 'on', $settings['trigger']['hide_mob'] ); ?> />
							<span><?php _e('Hide trigger button on Mobile', 'combar-fs'); ?></span>
						</label>
					</div>
					
					<h4><?php _e('Custom trigger', 'combar-fs'); ?></h4>
					<div class="admin-option radio-option">
						<span class="option-subtitle">
							<?php _e('In case you want to create your own trigger button use "Custom trigger selector" field to set your own trigger element.', 'combar-fs'); ?>
						</span>
						<label>
							<input type="checkbox" name="combar_fs[trigger][hide]" <?php checked( 'on', $settings['trigger']['hide'] ); ?> />
							<span><?php _e('Remove trigger button', 'combar-fs'); ?></span>
						</label>
					</div>
	
					<div class="admin-option">
						<label>
							<span class="option-title">
								<?php _e('Custom trigger selector', 'combar-fs'); ?>
							</span>
							<span class="option-subtitle">
								<?php _e('Valid jQuery selector: #id or .class', 'combar-fs'); ?>
							</span>
							<input type="text" name="combar_fs[trigger][selector]" value="<?php echo esc_attr($settings['trigger']['selector']); ?>"/>
						</label>
					</div>
					
				</div>

				<div class="admin-box social hide">
				
					<div class="options-desc">
						<?php
							_e('You can drag the fields to change the order in which they are displayed.', 'combar-fs');
							echo '</br>';
							_e('Empty fields will not be displayed.', 'combar-fs');
						?>
					</div>
					
					<div class="social-options" id="sortable">
						<?php 					
						$socialArray = $settings['social'];
						$socialJson = file_get_contents( plugin_dir_path(__DIR__ ) . 'assets/json/social.json');
						$socialJson = json_decode ($socialJson);
					//	var_dump($socialJson);
						if (!empty($socialArray)) {
							foreach ($socialArray as $key => $val) {
								$socialId = $key;
								$socialObject = $socialJson->$socialId;
								?>	
							
								<div class="admin-option social-option">
									<div class="drag-handle">
										<span class="dashicons dashicons-menu"></span>
									</div>
									<label>
										<span class="option-title">
											<?php echo esc_html($socialObject->name); ?>
										</span>
										<i class="<?php echo esc_attr($socialObject->icon); ?>"></i>
										<input type="url" name="combar_fs[social][<?php echo esc_attr($socialId); ?>]" value="<?php echo esc_attr($val); ?>" placeholder="<?php echo __('Example:', 'combar-fs') . ' ' . esc_url($socialObject->example); ?>"/>
										<?php
											if ('whatsapp' == $socialId) {
										?>
										<span class="help-tt"><i>?</i>
										<small class="tooltip">
											<?php printf ( __('Phone number in %s parameter must include country code.', 'combar-fs'), '<span>phone=</span>' ); ?>
											</br>
											<?php
												printf( __('For whatsapp link generator %s click here %s', 'combar-fs'),
														 '<a href="https://www.onetools.me/whatsapp-link-generator/" target="_blank">', '</a>'
												);
											?>
										</small>
										</span>

										<?php
											}
										?>
									</label>
								</div>
								
								<?php
								
							}	
						}
						
						?>
						
					</div>
				</div>

				<div class="admin-box blocks hide">
				
					<div class="options-desc">
						<?php
							_e('You can drag the blocks to change the order in which they are displayed.', 'combar-fs');
						?>
					</div>
					
					<div class="elements-bar">
					<button type="button" class="add-element button button-primary" data-nonce="<?php echo wp_create_nonce( 'fs-elements' ); ?>">
						<?php _e('Add element', 'combar-fs'); ?>
					</button>
						<div class="element-select">
							<div class="elements-title">
								<?php _e('Choose element', 'combar-fs'); ?>	
							</div>
							<div class="elements-list">
								
								<div class="element-btn" data-type="text">
									<span class="dashicons dashicons-heading"></span>
									<div>
									<b><?php _e('Heading block', 'combar-fs'); ?></b>
									<?php _e('Simple element with icon, title and text.', 'combar-fs'); ?>
									</br>
									<?php _e('Can be linked to a URL.', 'combar-fs'); ?>
									</div>
								</div>

								<div class="element-btn" data-type="wysiwyg">
									<span class="dashicons dashicons-edit"></span>
									<div>
									<b><?php _e('WYSIWYG block', 'combar-fs'); ?></b>
									<?php _e('Simple element with WYSIWYG editor.', 'combar-fs'); ?>
									</div>
								</div>

								<div class="element-btn" data-type="phone">
									<span class="dashicons dashicons-phone"></span>
									<div>
									<b><?php _e('Phone block', 'combar-fs'); ?></b>
									<?php _e('Show phone link with icon, title and text.', 'combar-fs'); ?>
									</div>
								</div>	

								<div class="element-btn" data-type="image">
									<span class="dashicons dashicons-format-image"></span>
									<div>
									<b><?php _e('Image block', 'combar-fs'); ?></b>
									<?php _e('Icon title and text above image.', 'combar-fs'); ?>
									</br>						
									<?php _e('Can be linked to a URL.', 'combar-fs'); ?>
									</div>
								</div>	

								<div class="element-btn" data-type="logo">
									<span class="dashicons dashicons-star-filled"></span>
									<div>
									<b><?php _e('Logo block', 'combar-fs'); ?></b>
									<?php _e('Logo above title and text.', 'combar-fs'); ?>
									</br>
									<?php _e('Can be linked to a URL.', 'combar-fs'); ?>
									</div>
								</div>

								<div class="element-btn" data-type="banner">
									<span class="dashicons dashicons-superhero-alt"></span>
									<div>
									<b><?php _e('Banner block', 'combar-fs'); ?></b>
									<?php _e('Background image with title, text and button.', 'combar-fs'); ?>
									<?php _e('Can be linked to a URL.', 'combar-fs'); ?>
									</div>
								</div>

								<div class="element-btn" data-type="social">
									<span class="dashicons dashicons-share"></span>
									<div>
									<b><?php _e('Social networks block', 'combar-fs'); ?></b>
									<?php _e('Show your social networks link in stylish buttons.', 'combar-fs'); ?>
									</div>
								</div>	

								<div class="element-btn" data-type="shortcode">
									<span class="dashicons dashicons-shortcode"></span>								<div>
									<b><?php _e('Shortcode block', 'combar-fs'); ?></b>
									<?php _e('Display custom shortcode.', 'combar-fs'); ?>
									</div>
								</div>
								
								<?php	
								if (defined('WPCF7_VERSION')) {
								?>
									<div class="element-btn" data-type="cf7">
										<span class="dashicons dashicons-email-alt"></span>
										<div>
										<b><?php _e('Contact Form 7 block', 'combar-fs'); ?></b>
										<?php _e('Show Contact Form 7 form.', 'combar-fs'); ?>
										</div>
									</div>
								<?php
								}
								?>

								<?php	
								if (defined('WPFORMS_VERSION')) {
								?>
									<div class="element-btn" data-type="wpforms">
										<span class="dashicons dashicons-email-alt"></span>
										<div>
										<b><?php _e('WPForms block', 'combar-fs'); ?></b>
										<?php _e('Show WPForms form.', 'combar-fs'); ?>
										</div>
									</div>
								<?php
								}
								?>		
						
							</div>
						
						</div>
					</div>
					<div class="elements-container" id="sortable">					
						<?php
							if (!empty($settings['elements'])) {
								foreach ($settings['elements'] as $elem) {
									echo combar_fs_element_fields($elem, $type = $elem['type'], false);
								}
							}
						?>
					</div>
				</div>
				
				<div class="admin-box advanced hide">
					
					<h4><?php _e('Display rules', 'combar-fs'); ?></h4>
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Hide on pages', 'combar-fs'); ?>
						</span>
					</div>
					<div class="admin-option radio-option">
					<span class="option-subtitle">
							<?php _e('Hide on pages rule', 'combar-fs'); ?>
						</span>
						<label>
							<input type="radio" name="combar_fs[adv][nopage_rule]" value="except" <?php checked( 'except', $settings['adv']['nopage_rule'] ); ?> />
							<span><?php _e('All except...', 'combar-fs'); ?></span>
						</label>
						<label>
							<input type="radio" name="combar_fs[adv][nopage_rule]" value="only" <?php checked( 'only', $settings['adv']['nopage_rule'] ); ?> />
							<span><?php _e('Only...', 'combar-fs'); ?></span>
						</label>
					</div>
					<div class="admin-option">
						<span class="option-subtitle">
							<?php _e('Select pages', 'combar-fs'); ?>
						</span>
						<label>
							<input type="hidden" name="combar_fs[adv][nopage]" value="<?php esc_attr($settings['adv']['nopage']); ?>" />
							<div class="nopages">
							<?php
							$pages = get_pages();
							$nopage = $settings['adv']['nopage'];
							$nopage = explode(',', $nopage);
							foreach ($pages as $page) {
								echo '<label>';
								echo '<input type="checkbox" value="' . esc_attr($page->ID) . '"'; 
								if (in_array($page->ID, $nopage)) {
									echo ' checked';
								}
								echo ' />';
								echo 'ID: ' . esc_attr($page->ID) . ' - ' . esc_attr($page->post_title);
								echo '</label>';
							}
							?>
							</div>
						</label>
					</div>
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('User rules', 'combar-fs'); ?>
						</span>
						<label>
							<input type="checkbox" name="combar_fs[adv][login]" <?php checked( 'on', $settings['adv']['login'] ); ?> />
							<span><?php _e('Show sidebar only to logged in users', 'combar-fs'); ?></span>
						</label>
					</div>
					
					<h4><?php _e('Browser features', 'combar-fs'); ?></h4>
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Hash link support', 'combar-fs'); ?>
						</span>
						<label>
							<input type="checkbox" name="combar_fs[adv][hash]" <?php checked( 'on', $settings['adv']['hash'] ); ?> />
							<span><?php _e('Support Hash link', 'combar-fs'); ?></span>
						</label>
						<small class="def">
							<?php _e('Recommended.', 'combar-fs'); ?> 
							<?php printf ( __('Adds %s to the trigger button.', 'combar-fs'), '<code>href="#sf-open"</code>' ); ?>
							</br>
							<?php _e('Allows browsers to close the sidebar by using the "back" button and prevents mobile users from leaving the site by mistake.', 'combar-fs'); ?>
						</small>
					</div>

					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Esc click support', 'combar-fs'); ?>
						</span>
						<label>
							<input type="checkbox" name="combar_fs[adv][esc]" <?php checked( 'on', $settings['adv']['esc'] ); ?> />
							<span><?php _e('Support Esc click', 'combar-fs'); ?></span>
						</label>
						<small class="def">
						<?php _e('Recommended.', 'combar-fs'); ?> 
						<?php _e('Allows browsers to close the sidebar by using the Esc button.', 'combar-fs'); ?>
						</small>
					</div>	

					<h4><?php _e('Plugin files', 'combar-fs'); ?></h4>
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('FontAwesome 5', 'combar-fs'); ?>
						</span>
						<label>
							<input type="checkbox" name="combar_fs[adv][fontawesome]" <?php checked( 'on', $settings['adv']['fontawesome'] ); ?> />
							<span><?php _e('Load FontAwesome 5', 'combar-fs'); ?></span>
						</label>
						<small class="def">
							<?php _e('If your website theme or plugins already loads FontAwesome 5 you can uncheck this and prevent plugin from loading it. This will help reduce the page size. ', 'combar-fs'); ?>
						</small>
					</div>

					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Minified files', 'combar-fs'); ?>
						</span>
						<label>
							<input type="checkbox" name="combar_fs[adv][minified]" <?php checked( 'on', $settings['adv']['minified'] ); ?> />
							<span><?php _e('Use minified CSS and JS files', 'combar-fs'); ?></span>
						</label>
						<small class="def">
						<?php _e('Recommended.', 'combar-fs'); ?> 
						<?php _e('This will help reduce the page size.', 'combar-fs'); ?>
						</br>
						<?php _e('Uncheck only if you experienced any compatibility issues.', 'combar-fs'); ?>
						</small>
					</div>
					
					<h4><?php _e('Additional settings', 'combar-fs'); ?></h4>
					<div class="admin-option">
						<span class="option-title">
							<?php _e('Restart plugin options', 'combar-fs'); ?>
						</span>
						<span class="option-subtitle" style="color: red;">
							<?php _e('Use with caution!', 'combar-fs'); ?>
							</br>
							<?php _e('This option will delete all saved data and return it to the default options.', 'combar-fs'); ?>
						</span>
						<input type="button" id="restartOptions" class="button button-primary reded" value="<?php _e('Restart to default', 'combar-fs'); ?>" data-nonce="<?php echo wp_create_nonce( 'fs-restart' ); ?>"/>
					</div>
					
					<div class="admin-option radio-option">
						<span class="option-title">
							<?php _e('Uninstall actions', 'combar-fs'); ?>
						</span>
						<label>
							<input type="checkbox" name="combar_fs[adv][uninstall_delete]" <?php checked( 'on', $settings['adv']['uninstall_delete'] ); ?> />
							<span><?php _e('Delete plugin data on uninstall', 'combar-fs'); ?></span>
						</label>
					</div>
					
				</div>

			</div>
			
			<div class="fs-preview">
			<div class="fs-preview-box">
			</div>
			<div class="previewBar">
			<div class="previewBarHead">
				<div class="previewBarNote">
					<span class="dashicons dashicons-warning"></span>
					<?php _e('Important notes to know about preview mode.', 'combar-fs'); ?>
					<u class="openPBH">
						<?php _e('Read more', 'combar-fs'); ?>
					</u>
					<u class="closePBH">
						<?php _e('Close', 'combar-fs'); ?>
					</u>
				</div>		
				<div class="previewBarActions">
					<div class="darkmode-switcher">
						<span class="dashicons dashicons-lightbulb"  title="<?php  _e('Turn preview dark mode', 'combar-fs'); ?>"></span>
					</div>
					<div class="device-switcher">
						<span class="dashicons dashicons-desktop" data-device="desk" title="<?php  _e('Turn on preview desktop view', 'combar-fs'); ?>"></span>
						<span class="dashicons dashicons-smartphone" data-device="mob" title="<?php  _e('Turn on preview mobile view', 'combar-fs'); ?>"></span>
					</div>
				</div>
			</div>
			<ol>
			<li>
				<?php _e('Preview mode does not load the style files of your website so the display will not be 100% accurate to the final result.', 'combar-fs'); ?>
			</li>
			<li>
				<?php _e('Sidebar width in preview is limited.', 'combar-fs'); ?>
			</li>
			<li>
				<?php _e('Links not work on preview.', 'combar-fs'); ?>
			</li>
			</ol>
			</div>
			</div>
			
			<div id="previewnonce" data-nonce="<?php echo wp_create_nonce( 'fs-preview' ); ?>" hidden />

		</form>

	</div> <!-- inner -->
</div> <!-- wrap -->