<?php
/*
Plugin Name: Related Products For Amazon
Plugin URI: http://wordpress.org/extend/plugins/related-products-for-amazon/
Description: Enables a responsive layout widget based on <a href="https://affiliate-program.amazon.com/" target="_blank">Amazon Associates</a> which uses keywords to show related products from Amazon market place and helps you to increase revenue from your content.
Version: 1.0
Author: Tadas Pocius
Author URI: http://fiction.lt/
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

register_deactivation_hook(__FILE__, 'deactive_aasrp');

add_action('admin_init', 'aasrp_admin_init');
add_action('admin_menu', 'aasrp_admin_actions');

function deactive_aasrp() {
  delete_option('aasrp_admin_init');
  delete_option('aasrp_admin_actions');
}

function aasrp_admin_init() {
  register_setting('amazonassociatesshowrelated', 'aasrp_tracking_id');
}

function aasrp_admin_actions(){
	add_options_page('Related Products For Amazon', 'Related Products for Amazon', 'manage_options', __FILE__, 'aasrp_admin');
}

function aasrp_admin(){
	
	if(isset($_POST['aasrp_tracking_id'])){
		update_option('aasrp_tracking_id', $_POST['aasrp_tracking_id']);
	}
	
	?>
	<div class="wrap">
		<h2>Related Products For Amazon Plugin</h2>
		<h4>A responsive layout widget based on <a href="https://affiliate-program.amazon.com/" target="_blank">Amazon Associates</a> which uses keywords to show related products from Amazon market place and helps you to increase revenue from your content.</h4>
		<form method="POST" action="">
		<?php wp_nonce_field('update-options'); ?>
		<?php settings_fields('amazonassociatesshowrelated'); ?>
		<table class="widefat" style="width:auto">
				<tbody>
					<tr>
						<td><label for="aasrp_tracking_id">Tracking ID:</label></td>
						<td><input id="aasrp_tracking_id" name="aasrp_tracking_id" type="text" value="<?php echo get_option('aasrp_tracking_id'); ?>" /></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="action" value="update" />
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
		<p>
			Once your Amazon Associates Tracking ID is stored, just use shortag [amazon-related-products keywords="product suggestions keywords"] wherever you want to see Amazon reccomended products widget.
		</p>
	</div>
	<?php
}

function aasrp_add_shortcode($atts){
	
	$attributes = shortcode_atts( array(
			'keywords' => ''
	), $atts);
	
	$result = '';
	
	if(isset($attributes['keywords'])){
		if($attributes['keywords'] != '' && preg_match('/^[0-9A-Za-z ]+$/', $attributes['keywords'])){
				$result =  '<script type="text/javascript">
							amzn_assoc_placement = "adunit0";
							amzn_assoc_enable_interest_ads = "true";
							amzn_assoc_tracking_id = "'.get_option("aasrp_tracking_id").'";
							amzn_assoc_ad_mode = "auto";
							amzn_assoc_ad_type = "smart";
							amzn_assoc_marketplace = "amazon";
							amzn_assoc_region = "US";
							amzn_assoc_fallback_mode = {"type":"search","value":"'.$attributes['keywords'].'"};
							amzn_assoc_default_category = "All";
							amzn_assoc_rows = "1";
							amzn_assoc_search_bar = "false"
							</script>
							<script src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US"></script>';
		}
	}
	
	return $result;
}

add_shortcode( 'amazon-related-products', 'aasrp_add_shortcode' ); 