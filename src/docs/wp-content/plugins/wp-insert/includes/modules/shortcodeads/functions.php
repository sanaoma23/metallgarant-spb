<?php
/* Begin Add New Shortcode Content */
add_action('wp_ajax_wp_insert_shortcodeads_new_form_get_content', 'wp_insert_shortcodeads_new_form_get_content');
function wp_insert_shortcodeads_new_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	wp_insert_shortcodeads_form_get_content(uniqid());
	die();
}
/* End Add New Shortcode Content */

/* Begin Edit Shortcode Content */
add_action('wp_ajax_wp_insert_shortcodeads_existing_form_get_content', 'wp_insert_shortcodeads_existing_form_get_content');
function wp_insert_shortcodeads_existing_form_get_content() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	if(isset($_POST['wp_insert_shortcodeads_identifier'])) {
		wp_insert_shortcodeads_form_get_content($_POST['wp_insert_shortcodeads_identifier']);
	}
	die();
}

add_action('wp_ajax_wp_insert_shortcodeads_existing_form_save_action', 'wp_insert_shortcodeads_existing_form_save_action');
function wp_insert_shortcodeads_existing_form_save_action() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');	
	if(isset($_POST['wp_insert_shortcodeads_identifier'])) {
		wp_insert_shortcodeads_form_save_action($_POST['wp_insert_shortcodeads_identifier']);
	}
	die();
}
/* End Edit Shortcode Content */

/* Begin Delete Shortcode Content */
add_action('wp_ajax_wp_insert_shortcodeads_remove', 'wp_insert_shortcodeads_remove');
function wp_insert_shortcodeads_remove() {
	check_ajax_referer('wp-insert', 'wp_insert_nonce');
	if(isset($_POST['wp_insert_shortcodeads_identifier'])) {
		$shortcodeads = get_option('wp_insert_shortcodeads');
		unset($shortcodeads[$_POST['wp_insert_shortcodeads_identifier']]);
		update_option('wp_insert_shortcodeads', $shortcodeads);
	}
	die();
}
/* End Delete Shortcode Content */

/* Begin Shared UI Functions */
function wp_insert_shortcodeads_form_get_content($identifier) {
	$shortcodeads = get_option('wp_insert_shortcodeads');
	echo '<div class="wp_insert_popup_content_wrapper">';
		$control = new smartlogixControls(array('optionIdentifier' => 'wp_insert_shortcodeads['.$identifier.']', 'values' => $shortcodeads[$identifier]));
		$control->add_control(array('type' => 'ipCheckbox', 'className' => 'wp_insert_shortcodeads_status', 'optionName' => 'status'));
		$control->add_control(array('type' => 'hidden', 'className' => 'wp_insert_shortcodeads_identifier', 'optionName' => 'identifier', 'value' => $identifier));
		echo $control->HTML;
		$control->clear_controls();
		echo '<div id="wp_insert_shortcodeads_'.$identifier.'_accordion">';
			echo '<h3>Shortcode</h3>';
			echo '<div>';
				$control->set_HTML('<p class="codeSnippet"><code>[wpshortcodead id="'.$identifier.'"]</code></p>');
				$control->create_section('Code to add to your post/page content');
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
			echo '<h3>Ad Code</h3>';
			echo '<div>';
				$abtestingMode = get_option('wp_insert_abtesting_mode');
				$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'primary_ad_code'));
				$control->create_section('Ad Code (Primary Network)');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'secondary_ad_code'));
				$control->create_section('Ad Code (Secondary Network)');
				if($abtestingMode != '2' && $abtestingMode != '3') {	
					$control->set_HTML('<div style="display: none;">'.$control->HTML.'</div>');
				}
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'tertiary_ad_code'));
				$control->create_section('Ad Code (Tertiary Network)');
				if($abtestingMode != '3') {	
					$control->set_HTML('<div style="display: none;">'.$control->HTML.'</div>');
				}
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
			echo '<h3>Rules</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_loggedin'));
				$control->create_section('Logged in Users');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_mobile_devices'));
				$control->create_section('Mobile Devices');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_404'));
				$control->create_section('404 Pages');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_home'));
				$control->create_section('Home');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_archives'));
				$control->create_section('Archives');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_search'));
				$control->create_section('Search Results');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_page'));
				$control->create_section('Single Pages');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_post'));
				$control->create_section('Single Posts');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_categories'));
				$control->add_control(array('type' => 'categories-chosen-multiselect', 'label' => 'Exceptions', 'optionName' => 'rules_categories_exceptions'));
				$control->create_section('Category Archives');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				
			echo '</div>';
			echo '<h3>Geo Targeting</h3>';
			echo '<div>';
				echo '<p>';
					echo 'A Geo Targeted Ads have a higher priority than Ads configured via Multiple Ad Networks / A-B Testing.<br />';
					echo 'If a Geo Targeting match is found all other Ads (Primary, Secondary and Tertiary Networks) will be ignored.';					
				echo '</p>';
				$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Countries', 'optionName' => 'geo_group1_countries', 'options' => wp_insert_get_countries()));
				$control->add_control(array('type' => 'textarea', 'label' => 'Ad Code', 'style' => 'height: 220px;', 'optionName' => 'geo_group1_adcode'));
				$control->create_section('Group 1');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Countries', 'optionName' => 'geo_group2_countries', 'options' => wp_insert_get_countries()));
				$control->add_control(array('type' => 'textarea', 'label' => 'Ad Code', 'style' => 'height: 220px;', 'optionName' => 'geo_group2_adcode'));
				$control->create_section('Group 2');
				$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
				echo $control->HTML;
				$control->clear_controls();
				echo '<p>';
					echo 'This feature uses the Free Geo ip service from <a href="http://freegeoip.net/">freegeoip.net</a>, if you find this feature useful please consider donating to the project at <a href="http://freegeoip.net/">freegeoip.net</a>';
				echo '</p>';
			echo '</div>';
			echo '<h3>Styles</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'styles'));
				$control->create_section('Styles');
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
			echo '<h3>Notes</h3>';
			echo '<div>';
				$control->add_control(array('type' => 'text', 'optionName' => 'title', 'helpText' => 'The title is used to identify your Ad Widget easily in future.  A Random Title will be assigned to your Ad widget by default.', 'value' => ((isset($shortcodeads[$identifier]['title']))?$shortcodeads[$identifier]['title']:$identifier)));
				$control->create_section('Title');
				echo $control->HTML;
				$control->clear_controls();
				
				$control->add_control(array('type' => 'textarea', 'style' => 'height: 220px;', 'optionName' => 'notes'));
				$control->create_section('Notes');
				echo $control->HTML;
				$control->clear_controls();
			echo '</div>';
		echo '</div>';
		echo '<script type="text/javascript">';
			echo $control->JS;
			echo 'jQuery("#wp_insert_shortcodeads_'.$identifier.'_accordion").accordion({ icons: { header: "ui-icon-circle-arrow-e", activeHeader: "ui-icon-circle-arrow-s" }, heightStyle: "auto" });';
		echo '</script>';
	echo '</div>';
}

function wp_insert_shortcodeads_form_save_action($identifier) {
	$shortcodeads = get_option('wp_insert_shortcodeads');
	$shortcodeads[$identifier]['identifier'] = ((isset($_POST['wp_insert_shortcodeads_identifier']))?$_POST['wp_insert_shortcodeads_identifier']:'');
	$shortcodeads[$identifier]['status'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_status']) && ($_POST['wp_insert_shortcodeads_'.$identifier.'_status'] == 'true'))?'1':'');
	
	$shortcodeads[$identifier]['primary_ad_code'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_primary_ad_code']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_primary_ad_code']:'');
	$shortcodeads[$identifier]['secondary_ad_code'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_secondary_ad_code']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_secondary_ad_code']:'');
	$shortcodeads[$identifier]['tertiary_ad_code'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_tertiary_ad_code']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_tertiary_ad_code']:'');
	
	$shortcodeads[$identifier]['rules_exclude_loggedin'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_loggedin']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_loggedin']:'');
	$shortcodeads[$identifier]['rules_exclude_mobile_devices'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_mobile_devices']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_mobile_devices']:'');
	$shortcodeads[$identifier]['rules_exclude_404'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_404']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_404']:'');
	$shortcodeads[$identifier]['rules_exclude_home'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_home']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_home']:'');
	$shortcodeads[$identifier]['rules_exclude_archives'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_archives']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_archives']:'');
	$shortcodeads[$identifier]['rules_exclude_search'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_search']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_search']:'');
	$shortcodeads[$identifier]['rules_exclude_page'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_page']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_page']:'');
	$shortcodeads[$identifier]['rules_page_exceptions'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_page_exceptions']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_page_exceptions']:'');
	$shortcodeads[$identifier]['rules_exclude_post'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_post']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_post']:'');
	$shortcodeads[$identifier]['rules_post_exceptions'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_post_exceptions']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_post_exceptions']:'');
	$shortcodeads[$identifier]['rules_post_categories_exceptions'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_post_categories_exceptions']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_post_categories_exceptions']:'');
	$shortcodeads[$identifier]['rules_exclude_categories'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_categories']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_exclude_categories']:'');
	$shortcodeads[$identifier]['rules_categories_exceptions'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_rules_categories_exceptions']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_rules_categories_exceptions']:'');
	
	$shortcodeads[$identifier]['geo_group1_countries'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_geo_group1_countries']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_geo_group1_countries']:'');
	$shortcodeads[$identifier]['geo_group1_adcode'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_geo_group1_adcode']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_geo_group1_adcode']:'');
	$shortcodeads[$identifier]['geo_group2_countries'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_geo_group2_countries']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_geo_group2_countries']:'');
	$shortcodeads[$identifier]['geo_group2_adcode'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_geo_group2_adcode']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_geo_group2_adcode']:'');
	
	$shortcodeads[$identifier]['styles'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_styles']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_styles']:'');
	
	$shortcodeads[$identifier]['title'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_title']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_title']:'');
	$shortcodeads[$identifier]['notes'] = ((isset($_POST['wp_insert_shortcodeads_'.$identifier.'_notes']))?$_POST['wp_insert_shortcodeads_'.$identifier.'_notes']:'');

	update_option('wp_insert_shortcodeads', $shortcodeads);
}
/* End Shared UI Functions */

/* Begin Shortcode Ad Insertion */
add_shortcode('wpshortcodead', 'wp_insert_shortcodeads_shortcode');
function wp_insert_shortcodeads_shortcode($atts) {
	$atts = shortcode_atts(array('id' => ''), $atts, 'wpshortcodead');
	if(isset($atts['id']) && ($atts['id'] != '')) {
		$shortcodeads = get_option('wp_insert_shortcodeads');
		if(isset($shortcodeads[$atts['id']]) && is_array($shortcodeads[$atts['id']]) && wp_insert_get_ad_status($shortcodeads[$atts['id']])) {
			return '<div class="wpInsert wpInsertShortcodeAd"'.(($shortcodeads[$atts['id']]['styles'] != '')?' style="'.$shortcodeads[$atts['id']]['styles'].'"':'').'>'.wp_insert_get_geotargeted_adcode($shortcodeads[$atts['id']]).'</div>';
		}
	}
}
/* End Shortcode Ad Insertion */
?>