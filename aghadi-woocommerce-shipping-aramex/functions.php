<?php
	
	if( !function_exists('is_woo_enabled') ){
	
		function is_woo_enabled(){
			
			/**
			 * Check if WooCommerce is active
			 **/
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			    return true;
			}else{
				return false;
			}
			
		}
		
	}
	
?>