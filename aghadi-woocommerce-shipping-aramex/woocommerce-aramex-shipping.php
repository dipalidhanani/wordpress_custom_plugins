<?php
/*
  Plugin Name: Aramex Shipping for WooCommerce
  Plugin URI: http://www.aghadiinfotech.com
  Description: Automatic Shipping Calculation using the Aramex Shipping API for WooCommerce
  Version: 1.0.0
  Author: Aghadi Infotech
  Author URI: http://www.aghadiinfotech.com
  Requires at least: 3.1
  Tested up to: 3.3.1

  Copyright: © 2012-2014 Aghadi Infotech.
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
 
/*if ( ! class_exists( 'Aghadi_Auto_Update' ) )
	require_once( 'aghadi-updates/aghadi_auto_update.php' );

function aghadi_aramex_update_init(){
	$aghadi_update = new Aghadi_Auto_Update( get_plugin_data(__FILE__), plugin_basename( __FILE__ ), '4548833', 'QXKYTd5u3oMPhJPPeHNccNvvO' );
}
add_action('admin_init', 'aghadi_aramex_update_init', 11);*/

/**
 * Localisation
 */
load_plugin_textdomain( 'wc_aramex', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

require_once('functions.php');

/**
 * Check if WooCommerce is active
 */
if ( is_woo_enabled() ) {

	require_once('functions.php');

	/**
	 * woocommerce_init_shipping_table_rate function.
	 *
	 * @access public
	 * @return void
	 */
	function wc_aramex_init() {
		if ( ! class_exists( 'WC_Shipping_Aramex' ) )
			include_once( 'classes/class-wc-shipping-aramex.php' );
	}

	add_action( 'woocommerce_shipping_init', 'wc_aramex_init' );

	/**
	 * wc_aramex_add_method function.
	 *
	 * @access public
	 * @param mixed $methods
	 * @return void
	 */
	function wc_aramex_add_method( $methods ) {
		$methods[] = 'WC_Shipping_Aramex';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'wc_aramex_add_method' );
	
	/**
	 * Display a notice if we don't have a origin city or province selected.
	 * @return void
	 */
	function wc_aramex_notices() {
	
		if ( ! class_exists( 'WC_Shipping_Aramex' ) )
			include_once( 'classes/class-wc-shipping-aramex.php' );
	
		$aramex = new WC_Shipping_Aramex();
		
		if( empty($aramex->origin_postalcode) ){
			
			$aramex_url = self_admin_url( 'admin.php?page=wc_settings&tab=shipping&section=wc_shipping_aramex' );

			$message = __( 'You must enter your post code before you can use Aramex shipping.' , 'wc_aramex' );
	
			echo '<div class="updated fade"><p><a href="' . $aramex_url . '">' . $message . '</p></div>' . "\n";
			
		}
	
	}

	add_action( 'admin_notices', 'wc_aramex_notices' );


	 $pluginroot = plugin_dir_path( __FILE__ ); 
	 $themeroot = get_template_directory();
	 
	 if (!file_exists($themeroot.'/woocommerce/order')) {
	    mkdir($themeroot.'/woocommerce/order', 0777, true);
	}

	copy($pluginroot.'woocommerce/order/order-details.php', $themeroot.'/woocommerce/order/order-details.php');
	
	add_action('woocommerce_admin_order_data_after_shipping_address','custom_plugin_content_fn',$order);

	function custom_plugin_content_fn($order){
		/*echo "<pre>";
		print_r($order);
		echo "=======================";
		echo "</pre>";*/
		?>
          <!-- Shipment code -->   
                <?php $post_id = $_GET['post'];
				 global $wpdb;
		 $shipment_number = $wpdb->get_var( $wpdb->prepare( "SELECT shipment_no FROM {$wpdb->prefix}woocommerce_order_items where order_id = %d limit 1;", $post_id ) ); 
		
		   ?>
          <div class="create_Shipment_number"><input type="submit" name="create_shipment" value="Create Shipment Number" style="cursor:pointer;" 
          <?php if($shipment_number != ''){ echo "disabled"; } ?> /></div>
          
          <div id="create_shipment_id"><?php  echo "AWB Number: ".$shipment_number; ?></div>
          
          <!-- Shipment code -->
          
    <?php	
	}
	
	add_action('save_post','function_save_order');
	function function_save_order($postid){
		
		if(get_post_type($postid) == 'shop_order'){
			
			//// My code start
				
				
				
				// List order items
				
if ( ( $_POST['create_shipment'] == 'Create Shipment Number' ) ){						
			$order = new WC_Order( $postid );
			 global $wpdb;
				
			//	$order = new WC_Order( $post_id );
				$shipping_city=$order->shipping_city;
				$shipping_country=$order->shipping_country;
				$shipping_address_1=$order->shipping_address_1;
				$shipping_address_2=$order->shipping_address_2;
				$shipping_postcode=$order->shipping_postcode;
			
				$order_items = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', array( 'line_item', 'fee' ) ) );
				
				$totalWeight 	= 0;
				$totalItems 	= 0;
				$aramex_items_counter = 0;
				
					
					foreach ( $order_items as $item_id => $item ) {
$aramex_items_counter++;
			
			$abc = $item['item_meta'];
			$def = $abc['_product_id'];
			$prid = $def[0];		
			$product = new WC_Product( $prid );
		//echo "<pre>";print_r($product);echo "</pre>";
		$aramex_items[]	= array(
									'PackageType'	=> 'Box',
									'Quantity'		=> $item['qty'],
									'Weight'		=> array(
										'Value'	=> get_post_meta( $prid, '_weight', true),
										'Unit'	=> 'Kg'
									),
									'Comments'		=> 'testcomment', //'',
									'Reference'		=> ''
								);

$totalWeight += get_post_meta( $prid, '_weight', true);
$totalItems 	+= $item['qty'];	
$totalLength 	+= get_post_meta( $prid, '_length', true);	
$totalWidth 	+= get_post_meta( $prid, '_width', true);	
$totalHeight 	+= get_post_meta( $prid, '_height', true);				

					}
					
					
				//echo "<pre>";print_r($order_items);echo "</pre>";
				
				
		//foreach( $packages as $key => $pack ){
//			$aramex_items_counter++;
//			// collect items for aramex
//								$aramex_items[]	= array(
//									'PackageType'	=> 'Box',
//									'Quantity'		=> $pack['Qty'],
//									'Weight'		=> array(
//										'Value'	=> $pack['Weight'],
//										'Unit'	=> 'Kg'
//									),
//									'Comments'		=> 'testcomment', //'',
//									'Reference'		=> ''
//								);
//			
//			
//			
//			$totalWeight 	+= $pack['Weight'];
//			$totalItems 	+= $pack['Qty'];		
//			
//			
//		}
		
			 $shipping_services_wsdl_path = get_site_url().'/wp-content/plugins/aghadi-woocommerce-shipping-aramex/classes/wsdl/production/shipping_services_api_wsdl.wsdl';
			
			$clientAramex_shipment = new SoapClient($shipping_services_wsdl_path);
			
		//$clientAramex_shipment = $this->create_soap_client( $shipping_services_wsdl_path );
		
		$sqlop=mysql_query("SELECT option_value FROM wp_options where option_name='woocommerce_aramex_settings'");
	
	$rowop=mysql_fetch_array($sqlop);
	$aramexsettings = unserialize($rowop['option_value']);

		$aramex_country_code			= $aramexsettings['aramex_country_code'];
		$aramex_account_entity = $aramexsettings['aramex_account_entity'];
        $aramex_account_number = $aramexsettings['aramex_account_number'];
        $aramex_account_pin = $aramexsettings['aramex_account_pin'];
        $aramex_account_username = $aramexsettings['aramex_account_username'];
        $aramex_account_password = $aramexsettings['aramex_account_password'];
        $aramex_api_version = $aramexsettings['aramex_api_version'];
			
		$params = array(
			'Shipments' => array(
				'Shipment' => array(
						'Shipper'	=> array(
										'Reference1' 	=> '001',
										'Reference2' 	=> '002',
										'AccountNumber' => '47615',
										'PartyAddress'	=> array(
											'Line1'					=> $shipping_address_1,
											'Line2' 				=> $shipping_address_2,
											'Line3' 				=> '',
											'City'					=> $shipping_city,
											'StateOrProvinceCode'	=> '',
											'PostCode'				=> $shipping_postcode,
											'CountryCode'			=> $shipping_country
										),
										'Contact'		=> array(
											'Department'			=> '',
											'PersonName'			=> 'Michael',
											'Title'					=> '',
											'CompanyName'			=> 'Aramex',
											'PhoneNumber1'			=> '5555555',
											'PhoneNumber1Ext'		=> '125',
											'PhoneNumber2'			=> '',
											'PhoneNumber2Ext'		=> '',
											'FaxNumber'				=> '',
											'CellPhone'				=> '9099133550',
											'EmailAddress'			=> $order->billing_email,
											'Type'					=> ''
										),
						),
					
						'Consignee'	=> array(
										'Reference1'	=> 'Ref 333333',
										'Reference2'	=> 'Ref 444444',
										'AccountNumber' => '',
										'PartyAddress'	=> array(
											'Line1'					=> $shipping_address_1,
											'Line2'					=> $shipping_address_2,
											'Line3'					=> '',
											'City'					=> $shipping_city,
											'StateOrProvinceCode'	=> '',
											'PostCode'				=> $shipping_postcode,
											'CountryCode'			=>  $shipping_country
										),
										
										'Contact'		=> array(
											'Department'			=> '',
											'PersonName'			=> $order->shipping_first_name,
											'Title'					=> '',
											'CompanyName'			=> 'Aramex',
											'PhoneNumber1'			=> '6666666',
											'PhoneNumber1Ext'		=> '155',
											'PhoneNumber2'			=> '',
											'PhoneNumber2Ext'		=> '',
											'FaxNumber'				=> '',
											'CellPhone'				=> $order->billing_phone,
											'EmailAddress'			=> $order->billing_email,
											'Type'					=> ''
										),
						),
						
						'Reference1' 				=> 'Shpt 0001',
						'Reference2' 				=> '',
						'Reference3' 				=> '',						
						'TransportType'				=> 0,
						'ShippingDateTime' 			=> time(),
						'DueDate'					=> time(),
						'PickupLocation'			=> 'Reception',
						'PickupGUID'				=> '',
						'Comments'					=> 'Shpt 0001',
						'AccountingInstrcutions' 	=> '',
						'OperationsInstructions'	=> '',
						
						'Details' => array(
										'Dimensions' => array(
											'Length'	=> $totalLength,
			    							'Width'		=> $totalWidth,
			    							'Height'	=> $totalHeight,
											'Unit'					=> 'cm'											
										),
										
										'ActualWeight' => array(
											'Value'					=> $totalWeight,
											'Unit'					=> 'Kg'
										),
										
										'ProductGroup' 			=> 'EXP',
										'ProductType'			=> 'PDX',
										'PaymentType'			=> 'P',
										'PaymentOptions' 		=> '',
										'Services'				=> '',
										'NumberOfPieces'		=> $totalItems,
										'DescriptionOfGoods' 	=> 'Docs',
										'GoodsOriginCountry' 	=> 'AE',
										
										'CashOnDeliveryAmount' 	=> array(
											'Value'					=> 0,
											'CurrencyCode'			=> get_woocommerce_currency()
										),
										
										'InsuranceAmount'		=> array(
											'Value'					=> 2,
											'CurrencyCode'			=> get_woocommerce_currency()
										),
										
										'CollectAmount'			=> array(
											'Value'					=> 0,
											'CurrencyCode'			=> get_woocommerce_currency()
										),
										
										'CashAdditionalAmount'	=> array(
											'Value'					=> 0,
											'CurrencyCode'			=> get_woocommerce_currency()							
										),
										
										'CashAdditionalAmountDescription' => '',
										
										'CustomsValueAmount' => array(
											'Value'					=> 0,
											'CurrencyCode'			=> get_woocommerce_currency()								
										),
										
										'Items' 				=> $aramex_items
						),
				),
		),
		
			'ClientInfo'  			=> array(
									'AccountCountryCode'        => $aramex_country_code,
                                    'AccountEntity'             => $aramex_account_entity,
                                    'AccountNumber'            => $aramex_account_number,
                                    'AccountPin'                => $aramex_account_pin,
                                    'UserName'                 => $aramex_account_username,
                                    'Password'                 => $aramex_account_password,
                                    'Version'                 => $aramex_api_version
									),

			'Transaction' 			=> array(
										'Reference1'			=> '001',
										'Reference2'			=> '', 
										'Reference3'			=> '', 
										'Reference4'			=> '', 
										'Reference5'			=> '',									
									),
			'LabelInfo'				=> array(
										'ReportID' 				=> 9201,
										'ReportType'			=> 'URL',
			),
	);
	

	try {
		$auth_call = $clientAramex_shipment->CreateShipments($params);
			
		//// Track shipment api start
		//$shipments_tracking_wsdl_path = get_site_url().'/wp-content/plugins/aghadi-woocommerce-shipping-aramex/classes/wsdl/production/shipments_tracking_api_wsdl.wsdl';
//			//$clientAramex = $this->create_soap_client( $shipments_tracking_wsdl_path );
//		
//		$clientAramex = new SoapClient($shipments_tracking_wsdl_path);
//		
		$trackingvalue = $auth_call->Shipments->ProcessedShipment->ID;
		
		$wpdb->query( $wpdb->prepare( "UPDATE wp_woocommerce_order_items SET shipment_no = %s WHERE order_id = %d", $trackingvalue,$postid) );
		
		$to = $order->billing_email;
		$subject = 'AWB Number';
		$message = 'You can track your order using shipment number(AWB Number) on your order page.<br>
		AWB Number: '.$trackingvalue;
		$headers = 'From: webmaster@example.com' . "\r\n" .
			'Reply-To: webmaster@example.com';
		
		mail($to, $subject, $message, $headers);
	
		
//$aramexParams['ClientInfo'] 	=  array(
//									 'AccountCountryCode'        => 'AE',
//                                    'AccountEntity'             => 'DXB',
//                                    'AccountNumber'            => '47615',
//                                    'AccountPin'                => '554654',
//                                    'UserName'                 => 'nick@oceanactive.com',
//                                    'Password'                 => 'oceanActive123@',
//                                    'Version'                 => 'v1.0'
//								);
//		$aramexParams['Transaction'] 	= array('Reference1' => '001' );
//		$aramexParams['Shipments'] 		= array($trackingvalue);
//
//        $_resAramex = $clientAramex->TrackShipments($aramexParams);
//	
//		if(is_object($_resAramex) && !$_resAramex->HasErrors){
//				if(!empty($_resAramex->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult)){
//					echo $this->getTrackingInfoTable($_resAramex->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult);
//				} 
//
//		}
		//// Track shipment end
	} catch (SoapFault $fault) {
		die('Error : ' . $fault->faultstring);
	}
}
	
//// My code end --- Get AWB number
			
			}
		
		
	}
	$sql=mysql_query("SELECT shipment_no FROM wp_woocommerce_order_items");

		if (!$sql){
		mysql_query("ALTER TABLE wp_woocommerce_order_items ADD shipment_no VARCHAR(255) NOT NULL AFTER order_id");
		
		}
}


?>