<?php
/**
 * WC_Shipping_Aramex class.
 *
 * @extends WC_Shipping_Method
 */
class WC_Shipping_Aramex extends WC_Shipping_Method {
	
	private $rate_major = '';
	
	private $address_validation_major = '';
	
	private $services = array(
		'EUROPE_FIRST_INTERNATIONAL_PRIORITY'	=> 'Europe First International Priority',
		'FEDEX_1_DAY_FREIGHT'					=> '1 Day Freight',
		'FEDEX_2_DAY'							=> '2Day',
		'FEDEX_2_DAY_AM'						=> '2Day A.M',
		'FEDEX_2_DAY_FREIGHT'					=> '2 Day Freight',
		'FEDEX_3_DAY_FREIGHT'					=> '3 Day Freight',
		'FEDEX_EXPRESS_SAVER'					=> 'Express Saver',
		'FEDEX_FIRST_FREIGHT'					=> 'First Freight',
		'FIRST_OVERNIGHT'						=> 'First Overnight',
		'PRIORITY_OVERNIGHT'					=> 'Priority Overnight',
		'STANDARD_OVERNIGHT'					=> 'Standard Overnight',
		'GROUND_HOME_DELIVERY'					=> 'Ground Home Delivery',
		'FEDEX_GROUND'							=> 'Ground',
		'INTERNATIONAL_ECONOMY'					=> 'International Economy',
		'INTERNATIONAL_FIRST'					=> 'International First',
		'INTERNATIONAL_PRIORITY'				=> 'International Priority',
		'INTERNATIONAL_ECONOMY_FREIGHT'			=> 'Economy Freight',
		'INTERNATIONAL_PRIORITY_FREIGHT'		=> 'Priority Freight',
		'FEDEX_FREIGHT'							=> 'Freight',
		'FEDEX_NATIONAL_FREIGHT'				=> 'National Freight',
		'INTERNATIONAL_GROUND'					=> 'International Ground',
		'SMART_POST'							=> 'Smart Post',
		'FEDEX_FREIGHT_ECONOMY'					=> 'Freight Economy',
		'FEDEX_FREIGHT_PRIORITY'				=> 'Freight Priority'
	);
	
	private $request;
	private $params;
	
	private $soap_extension = false;
	
	private $crates;
	
	private $boxes;
	
	private $key;
	
	private $password;

	private $ar_country_code;
	private $ar_account_entity;
	private $ar_account_number;
	private $ar_account_pin;
	private $ar_account_username;
	private $ar_account_password;
	private $ar_api_version;
	private	$productGroup;
	private	$productType ;

	function __construct() {
		global $woocommerce;
		
		$this->crates = array();
		
		$this->id = 'aramex';
		$this->title = 'Aramex';
		$this->method_title = 'Aramex';

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();


		$this->enabled				= isset( $this->settings['enabled'] ) && $this->settings['enabled'] == 'yes' ? true : false;
		$this->title				= isset( $this->settings['title'] ) ? $this->settings['title'] : 'Aramex';
		$this->debug				= isset( $this->settings['debug'] ) && $this->settings['debug'] == 'yes' ? true : false;
		$this->availability			= isset( $this->settings['availability'] ) ? $this->settings['availability'] : 'all';
		$this->countries			= isset( $this->settings['countries'] ) ? $this->settings['countries'] : array();
		$this->origin_postalcode	= isset($this->settings['origin_postalcode']) ? str_replace( ' ', '', strtoupper( $this->settings['origin_postalcode'] ) ) : '';
		$this->origin_country		= $woocommerce->countries->get_base_country();
		$this->dev_mode				= isset( $this->settings['dev_mode'] ) && $this->settings['dev_mode'] == 'yes' ? true : false;
		$this->dev_key				= isset( $this->settings['dev_key'] ) ? $this->settings['dev_key'] : '';
		$this->dev_password			= isset( $this->settings['dev_password'] ) ? $this->settings['dev_password'] : '';

		// Aramex Settings
		$this->aramex_country_code			= isset( $this->settings['aramex_country_code'] ) ? $this->settings['aramex_country_code'] : '';
		$this->aramex_account_entity			= isset( $this->settings['aramex_account_entity'] ) ? $this->settings['aramex_account_entity'] : '';
		$this->aramex_account_number			= isset( $this->settings['aramex_account_number'] ) ? $this->settings['aramex_account_number'] : '';
		$this->aramex_account_pin			= isset( $this->settings['aramex_account_pin'] ) ? $this->settings['aramex_account_pin'] : '';
		$this->aramex_account_username			= isset( $this->settings['aramex_account_username'] ) ? $this->settings['aramex_account_username'] : '';
		$this->aramex_account_password			= isset( $this->settings['aramex_account_password'] ) ? $this->settings['aramex_account_password'] : '';
		$this->aramex_account_city			= isset( $this->settings['aramex_account_city'] ) ? $this->settings['aramex_account_city'] : '';
		$this->aramex_api_version			= isset( $this->settings['aramex_api_version'] ) ? $this->settings['aramex_api_version'] : '';



		$this->prod_key				= isset( $this->settings['prod_key'] ) ? $this->settings['prod_key'] : '';
		$this->prod_password		= isset( $this->settings['prod_password'] ) ? $this->settings['prod_password'] : '';
		$this->account_number		= isset( $this->settings['account_number'] ) ? $this->settings['account_number'] : '510087666';
		$this->meter_number			= isset( $this->settings['meter_number'] ) ? $this->settings['meter_number'] : '118579571';
		$this->need_signature		= isset( $this->settings['need_signature'] ) && $this->settings['need_signature'] == 'yes' ? true : false;
		$this->insure_shipping		= isset( $this->settings['insure_shipping'] ) && $this->settings['insure_shipping'] == 'yes' ? true : false;
		$this->fee					= isset( $this->settings['fee'] ) ? $this->settings['fee'] : '';
		$this->shipping_methods		= isset( $this->settings['shipping_methods'] ) ? $this->settings['shipping_methods'] : array();
		$this->custom_methods		= isset( $this->settings['custom_methods'] ) ? $this->settings['custom_methods'] : array();
		
		$this->packing_method		= isset( $this->settings['packing_method'] ) ? $this->settings['packing_method'] : 'per_item';
		$this->soap_method			= isset( $this->settings['soap_method'] ) ? $this->settings['soap_method'] : 'auto';
		
		if( empty( $this->custom_methods ) && !empty( $this->services ) ){
			
			foreach( $this->services as $method_key => $method_name ){
				
				$this->custom_methods[ $method_key ] = array(
					'name'				=> woocommerce_clean( $method_name ),
					'price_ajustment'	=> '',
					'enabled'			=> ( ( isset( $this->settings['shipping_methods'] ) && array_search( $method_key, $this->settings['shipping_methods'] ) !== false ) || !isset( $this->settings['shipping_methods'] ) ? '1' : '0' )
				);
				
			}
			
		}
		
		if(class_exists('SoapClient') && class_exists('SoapHeader') && $this->soap_method != 'nusoap'){
			$this->soap_extension = true;
			if( $this->debug && $this->enabled )
				$woocommerce->add_message('Use native SoapHeader extension.<br />');
		}else{
			require_once('nusoap/lib/nusoap.php');
			if( $this->debug && $this->enabled )
				$woocommerce->add_message('Use nusoap extension.<br />');
		}
		
		if( $this->dev_mode ){
			$this->key = $this->dev_key;
			$this->password = $this->dev_password;
			$this->wsdl_path = plugin_dir_path(__FILE__)."wsdl/development/aramex_rates_calculator_wsdl.wsdl";
			$this->address_validation_wsdl_path = plugin_dir_path(__FILE__).'wsdl/development/Location_API_WSDL.wsdl';
			$this->shipping_services_wsdl_path = plugin_dir_path(__FILE__).'wsdl/development/shipping_services_api_wsdl.wsdl';
			$this->shipments_tracking_wsdl_path = plugin_dir_path(__FILE__).'wsdl/development/shipments_tracking_api_wsdl.wsdl';

		}else{
			$this->key = $this->prod_key;
			$this->password = $this->prod_password;
			$this->wsdl_path = plugin_dir_path(__FILE__)."wsdl/production/aramex_rates_calculator_wsdl.wsdl";
			$this->address_validation_wsdl_path = plugin_dir_path(__FILE__).'wsdl/production/Location_API_WSDL.wsdl';
			$this->shipping_services_wsdl_path = plugin_dir_path(__FILE__).'wsdl/production/shipping_services_api_wsdl.wsdl';
			$this->shipments_tracking_wsdl_path = plugin_dir_path(__FILE__).'wsdl/production/shipments_tracking_api_wsdl.wsdl';

		}

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( &$this, 'process_admin_options' ) );

		add_action('admin_notices', array(&$this, 'currency_check'));
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	function init_form_fields() {
		global $woocommerce;

		$this->form_fields = array(
		    'enabled' => array(
				'title' => __('Enable/Disable', 'wc_aramex'),
				'type' => 'checkbox',
				'label' => __('Enable Aramex', 'wc_aramex'),
				'default' => ''
		    ),
		    'title' => array(
				'title' => __('Method title', 'wc_aramex'),
				'type' => 'text',
				'description' => __('Enter the title of the shipping method.', 'wc_aramex'),
				'default' => __('Aramex', 'wc_aramex')
		    ),
		    'debug' => array(
				'title' => __('Debug Mode', 'wc_aramex'),
				'label' => __('Enable Debug Mode', 'wc_aramex'),
				'type' => 'checkbox',
				'description' => __('Output the response from Aramex on the cart/checkout for debugging purposes.', 'wc_aramex'),
				'default' => ''
		    ),
		    'availability'  => array(
				'title'           => __( 'Method Availability', 'wc_aramex' ),
				'type'            => 'select',
				'default'         => 'all',
				'class'           => 'availability',
				'options'         => array(
					'all'            => __( 'All Countries', 'wc_aramex' ),
					'specific'       => __( 'Specific Countries', 'wc_aramex' ),
				),
			),
			'countries'        => array(
				'title'           => __( 'Specific Countries', 'wc_aramex' ),
				'type'            => 'multiselect',
				'class'           => 'chosen_select',
				'css'             => 'width: 450px;',
				'default'         => '',
				'options'         => $woocommerce->countries->get_allowed_countries(),
			),
		    'origin_postalcode' => array(
				'title' => __('Origin Postal code', 'wc_aramex'),
				'type' => 'text',
				'description' => __('Enter your origin postal code.', 'wc_aramex'),
				'default' => __('', 'wc_aramex')
		    ),
		    'api'           => array(
				'title'           => __( 'API Settings', 'wc_aramex' ),
				'type'            => 'title',
				'description'     => __( 'Your API access details', 'wc_aramex' ),
		    ),		    
		    'aramex_country_code' => array(
				'title' => __('Aramex Account Country Code', 'wc_aramex'),
				'type' => 'text',
				'css' => 'width: 250px;',
				'description' => __('Country Code where your Aramex Account is Created', 'wc_aramex'),
				'default' => ''
		    ),
		    'aramex_account_entity' => array(
				'title' => __('Aramex Account Entity', 'wc_aramex'),
				'type' => 'text',
				'css' => 'width: 250px;',
				'description' => __('Entity Code where your Aramex Account is Created,AMM,DXB,etc', 'wc_aramex'),
				'default' => ''
		    ),
		    'aramex_account_number' => array(
				'title' => __('Aramex Account Number', 'wc_aramex'),
				'type' => 'text',
				'css' => 'width: 250px;',
				'description' => __('Enter Your Aramex Account Number', 'wc_aramex'),
				'default' => ''
		    ),
		    'aramex_account_pin' => array(
				'title' => __('Aramex Account PIN', 'wc_aramex'),
				'type' => 'text',
				'css' => 'width: 250px;',
				'description' => __('Enter Your Aramex Account PIN', 'wc_aramex'),
				'default' => ''
		    ),
		    'aramex_account_username' => array(
				'title' => __('Aramex Account Username', 'wc_aramex'),
				'type' => 'text',
				'css' => 'width: 250px;',
				'description' => __('Enter Your Aramex Account Username', 'wc_aramex'),
				'default' => ''
		    ),
		    'aramex_account_password' => array(
				'title' => __('Aramex Account Password', 'wc_aramex'),
				'type' => 'text',
				'css' => 'width: 250px;',
				'description' => __('Enter Your Aramex Account Password', 'wc_aramex'),
				'default' => ''
		    ),
			'aramex_account_city' => array(
				'title' => __('Aramex Origin City', 'wc_aramex'),
				'type' => 'text',
				'css' => 'width: 250px;',
				'description' => __('Enter Your Aramex Account Password', 'wc_aramex'),
				'default' => ''
		    ),
		    'aramex_api_version' => array(
				'title' => __('Aramex API Version', 'wc_aramex'),
				'type' => 'text',
				'css' => 'width: 250px;',
				'description' => __('Enter Aramex API Version, Default is v1.0', 'wc_aramex'),
				'default' => ''
		    )
		);
	}

	/**
	 * First step is to make sure the configuration of woocommerce is correct.
	 * Raise warning if is not correct.
	 */
	function currency_check() {
	}
	
	function create_soap_client( $wsdl ){
		if($this->soap_extension){
			$client = new SoapClient( $wsdl, 
				array(
					'trace' =>	true
				)
			);
		}else{
			$client = new nusoap_client($wsdl, true, array(
				'trace' =>	true
			));
			$client->soap_defencoding = 'UTF-8';
			$client->setCredentials($this->key, $this->pass);
		}
		return($client);
	}

	/**
	 * Get shipping quotes based on change of shipping address
	 * 
	 * @method calculate_shipping
	 * @abstract setup shipping rate for each selected shipping option
	 */
	function calculate_shipping( $package ){
		global $woocommerce;
		
		if($this->debug)
			$woocommerce->add_message('Enter calculate shipping function.<br />');
		
		$update_rates = false;
		$cart_items = $woocommerce->cart->get_cart();
		foreach ($cart_items as $id => $cart_item) {
			$cart_temp[] = $id . $cart_item['quantity'];
		}
		$cart_hash = hash('MD5', serialize($cart_temp));

		if (!$this->debug)
			$cache_data = get_transient(get_class($this));
		else
			$cache_data = '';
		

		if ($cache_data) {
			if ($cache_data['cart_hash'] == $cart_hash && $cache_data['shipping_data']['postalcode'] == $package['destination']['postcode'] && $cache_data['shipping_data']['State'] == $package['destination']['state'] && $cache_data['shipping_data']['Country'] == $package['destination']['country']) {
				$this->rates = $cache_data['rates'];
				if($this->debug)
					$woocommerce->add_message('Shipping update not required.<br />');
			} else {
				if($this->debug)
					$woocommerce->add_message('Shipping update required.<br />');
				$update_rates = true;
			}
		} else {
			if($this->debug)
				$woocommerce->add_message('Shipping update required.<br />');
			$update_rates = true;
		}

		//only update rates when needed
		if ($update_rates) {
			if($this->debug)
				$woocommerce->add_message('Enter update shipping.<br />');
			if ($this->get_shipping_request($package)) {
				$cache_data['shipping_data'] = array(
				    'postalcode' => $package['destination']['postcode'],
				    'State' => $package['destination']['state'],
				    'Country' => $package['destination']['country']
				);
				$cache_data['cart_hash'] = $cart_hash;
				$cache_data['rates'] = $this->rates;

				set_transient(get_class($this), $cache_data);
			}
		}
	}
	
	public function has_enabled_methods(){
		
		$enabled = false;
		
		if( !empty( $this->custom_methods ) ){
		
			foreach( $this->custom_methods as $method_key => $service ){
				
				if( $service['enabled'] ){

					$enabled = true;
					break;
					
				}
				
			}
			
		}
		
		return $enabled;
		
	}
	
	/**
	 * validate_address function. Used Aramex address verification
	 *
	 * @access public
	 * @param mixed $address
	 * @return $address
	 */
	public function validate_address( $address ){
		global $woocommerce;
		
		$address['postalcode'] = str_replace( ' ', '', strtoupper( $address['postalcode'] ) );
		
		$client = $this->create_soap_client( $this->address_validation_wsdl_path );
		
		if($this->soap_extension){
		
		
				//$woocommerce->add_message( 'Aramex request address validation: <pre style="height:200px">' . print_r( $request, true ) . '</pre>' );
			
			//$response = $client->addressValidation( $request );

///////////////////////////

	$params = array(
		'ClientInfo'             => array(
                                    'AccountCountryCode'        => $this->aramex_country_code,
                                    'AccountEntity'             => $this->aramex_account_entity,
                                    'AccountNumber'            => $this->aramex_account_number,
                                    'AccountPin'                => $this->aramex_account_pin,
                                    'UserName'                 => $this->aramex_account_username,
                                    'Password'                 => $this->aramex_account_password,
                                    'Version'                 => $this->aramex_api_version
                                ),

		'Transaction' 			=> array(
									'Reference1'			=> '001',
									'Reference2'			=> '002',
									'Reference3'			=> '003',
									'Reference4'			=> '004',
									'Reference5'			=> '005'
							 
								),
		'Address' 			=> array(
									'Line1'			=> 'Building 28, Apartment 24 Jebel Ali Gardens',
									'Line2'			=> 'Dubai, United Arab Emirates',
									'Line3'			=> '',
									'City'			=> 'Dubai',
									'StateOrProvinceCode'			=> '',
									'PostCode'			=> '',
									'CountryCode'			=> 'AE'							 
								)

		);


			// calling the method and printing results
			try {
				$response = $client->ValidateAddress($params);

				//echo '<pre>repose from Address Validate';

				//echo '<pre>';
				//print_r($response);
				//die();

				} catch (SoapFault $fault) {
				die('Error : ' . $fault->faultstring);
				}
			
			if($this->debug)
				$woocommerce->add_message( 'Aramex response address validation: <pre style="height:200px">' . print_r( $response, true ) . '</pre>' );
			
			if( $response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' ){
				if(count($response->AddressResults) > 0){
				foreach( $response->AddressResults as $validated_address ){
					
					if( $validated_address->ProposedAddressDetails->ResidentialStatus == 'BUSINESS' )
						$address['residential'] = false;
					
				}
				}
				
			}
		
		}else{
/*
			$client->custom_payload = '<?xml version="1.0" encoding="UTF-8"?><SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ns1="'.$this->uri.'"><SOAP-ENV:Header><ns1:RequestContext><ns1:Version>1.2</ns1:Version><ns1:Language>fr</ns1:Language><ns1:GroupID>xxx</ns1:GroupID><ns1:RequestReference>Address validation</ns1:RequestReference></ns1:RequestContext></SOAP-ENV:Header><SOAP-ENV:Body><ns1:ValidateCityPostalCodeZipRequest><ns1:Addresses><ns1:ShortAddress><ns1:City>'.$address['city'].'</ns1:City><ns1:Province>'.$address['province'].'</ns1:Province><ns1:Country>'.$address['country'].'</ns1:Country><ns1:PostalCode>'.$address['postalcode'].'</ns1:PostalCode></ns1:ShortAddress></ns1:Addresses></ns1:ValidateCityPostalCodeZipRequest></SOAP-ENV:Body></SOAP-ENV:Envelope>';

			$response = $client->call('ValidateCityPostalCodeZip', '', $this->uri);
*/
		}

		$client = $request = null;
		
		return($address);
	}

	/**
	 * Send request and retrieve the result.
	 */
	function get_shipping_request( $package ) {
		global $woocommerce;
		
		if($this->debug)
			$woocommerce->add_message('Enter shipping request function.<br />');
		
		$client = $this->create_soap_client( $this->wsdl_path );
		
		$address = $this->validate_address( array(
			'address'		=> $package['destination']['address'],
			'address_2'		=> $package['destination']['address_2'],
			'city'			=> $package['destination']['city'],
			'province'		=> $package['destination']['state'],
			'country'		=> $package['destination']['country'],
			'postalcode'	=> $package['destination']['postcode'],
			'residential'	=> true
		));
		
		/* Declare new object first */
	/*	$this->request = array(
			'WebAuthenticationDetail' => array(
				'UserCredential' => array(
					'Key'		=> $this->key,
					'Password'	=> $this->password
				)
			),
			'ClientDetail' => array(
				'AccountNumber'	=> $this->account_number,
				'MeterNumber'	=> $this->meter_number
			),
			'TransactionDetail' => array(
				'CustomerTransactionId' => '*** Aghadi WooCommerce Aramex Method ***'
			),
			'Version' => array(
				'ServiceId'		=> 'crs',
				'Major'			=> $this->rate_major,
				'Intermediate'	=> '0',
				'Minor'			=> '0'
			),
			'ReturnTransitAndCommit' => true,
			'RequestedShipment' => array(
				'DropoffType' => 'REGULAR_PICKUP',
				'Shipper' => array(
					'Address' => array(
						'PostalCode'	=> $this->origin_postalcode,
						'CountryCode'	=> $woocommerce->countries->get_base_country()
					)
				),
				'Recipient' => array(
					'Address' => array(
						'Residential'			=> $address['residential'],
						'StateOrProvinceCode'	=> $address['province'],
						'PostalCode'			=> $address['postalcode'],
						'CountryCode'			=> $address['country']
					)
				),
				'RequestedPackageLineItems' => array(),
				'PackageCount' => 0
			)
		);*/
		

		// prepare request Param to be sent to Aramex SOAP services

		if ($package['destination']['country'] == $this->aramex_country_code){
				$this->productGroup = 'DOM';
				$this->productType = 'OND';
		}
		else {
			$this->productGroup = 'EXP';
			$this->productType = 'PPX';

		}
		
			$packages = $this->set_package_requests( $package );
		
		if( count($packages) <= 0 )
			return false;
		
		foreach( $packages as $key => $pack ){
			
			$item = array(
				'Dimensions' => array(
					'Length'	=> $pack['Length'],
			    	'Width'		=> $pack['Width'],
			    	'Height'	=> $pack['Height'],
			    	'Units'		=> 'cm'
				),
				'Weight' => array(
					'Value' => $pack['Weight'],
					'Units' => 'KG'
				),
				'GroupPackageCount' => $pack['Qty'],
				'SequenceNumber' => ( $key + 1 )
			);
			
			if( $this->insure_shipping ){
				
				$item['InsuredValue'] = array(
					'Amount'	=> ( $pack['Value'] * $pack['Qty'] ),
					'Currency'	=> get_woocommerce_currency()
				);
				
			}
				
			if( $this->need_signature ){
			
				$item['SpecialServicesRequested'] = array(
					'SpecialServiceTypes' => 'SIGNATURE_OPTION',
					'SignatureOptionDetail' => array(
						'OptionType' => 'ADULT'
					)
				);
				
			}
			
			$cartlength += $pack['Length'];
			$cartwidth += $pack['Width'];
			$cartheight += $pack['Height'];
			
		//	$this->request['RequestedShipment']['PackageCount'] += $pack['Qty'];
				
			$this->request['RequestedShipment']['RequestedPackageLineItems'][] = $item;
			
		}
		//echo "<pre>"; print_r($package); echo "</pre>";
		//$total_weight = $woocommerce->cart->cart_contents_weight;
		$totaldimension = ($cartlength*$cartwidth*$cartheight)/5000;
		$totalweight = $woocommerce->cart->cart_contents_weight;
		 $total_pr_qty = $woocommerce->cart->cart_contents_count;		
		
		if($totaldimension > $totalweight){$total_weight = $totaldimension;}else {$total_weight = $totalweight;}
		
		$this->params = array(
		'ClientInfo'  			=> array(
									'AccountCountryCode'	=> $this->aramex_country_code,
									'AccountEntity'		 	=> $this->aramex_account_entity,

									'UserName'			 	=> $this->aramex_account_username,
									'Password'			 	=> $this->aramex_account_password,
									'Version'			 	=> 'v1.0'
								),
								
		'Transaction' 			=> array(
									'Reference1'			=> '001' 
								),
								
		'OriginAddress' 	 	=> array(
									'City'					=> $this->aramex_account_city,
									'CountryCode'				=> $this->aramex_country_code//$this->origin_country
									
								),
								
		'DestinationAddress' 	=> array(
									'City'					=> $package['destination']['city'],
									'CountryCode'			=> $package['destination']['country'],
									'PostCode'			   => $package['destination']['postcode']
								),
		'ShipmentDetails'		=> array(
									'PaymentType'			 => 'P',
									'ProductGroup'			 => $this->productGroup,
									'ProductType'			 => $this->productType,
									'ActualWeight' 			 => array('Value' => $total_weight, 'Unit' => 'KG'),
									'ChargeableWeight' 	     => array('Value' => $total_weight, 'Unit' => 'KG'),
									'NumberOfPieces'		 => $total_pr_qty
								)
	);
	
		if( !in_array( $address['country'], array( 'US', 'CA',  ) ) )
			unset( $this->request['RequestedShipment']['Recipient']['Address']['StateOrProvinceCode'] );
		
		
		
		if($this->debug)
			$woocommerce->add_message( 'Aramex request: <pre style="height:200px">' . print_r( $this->request, true ) . '</pre>' );
		
		try {
			//$woocommerce->add_message( 'Calling CalculateRate Aramex method' );
			$response = $client->CalculateRate( $this->params );
	
		} catch (SoapFault $fault) {
				die('Error : ' . $fault->faultstring);
		}
		if($this->debug)
			$woocommerce->add_message( 'Aramex CalculateRate response: <pre style="height:200px">' . print_r( $response, true ) . '</pre>' );

		if ($response){
			
			//$woocommerce->add_message( 'Adding Aramex rate' );
			$Value = $response->TotalAmount->Value;
			//echo "CurrencyCode:".$CurrencyCode = $response->TotalAmount->CurrencyCode;
			//echo "---------".get_woocommerce_currency();
		//	echo "<pre>";print_r($response);	echo "</pre>";
			$rate = array(
						'id' => $this->id,
						'label' => $this->title,
						'cost' => $this->get_currency($response->TotalAmount->CurrencyCode, get_woocommerce_currency(), $Value)
						
					);
			// Register the rate
			$this->add_rate( $rate );

		}
		//// My code start
			
	
//// My code end
		if( $response ){ 
		
			if( $response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' && isset( $response->RateReplyDetails ) ){
			
				$rate_reply_details = $response->RateReplyDetails;
			
				if( !is_array( $rate_reply_details ) )
					$rate_reply_details = array( $rate_reply_details );
					
				foreach( $rate_reply_details as $rate ){
				
					$method_id = $rate->ServiceType;
				
					if( !isset( $this->custom_methods[ $method_id ] ) || ( isset( $this->custom_methods[ $method_id ] ) && !$this->custom_methods[ $method_id ][ 'enabled' ] ) )
						continue;
					
					$rate_id = $this->id.':'.$method_id;
					$rate_name = $this->title.' ' . ( !empty( $this->custom_methods[ $method_id ]['name'] ) ? $this->custom_methods[ $method_id ]['name'] : $this->services[ $method_id ] );
					$rate_cost = is_array( $rate->RatedShipmentDetails ) ? $rate->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount : $rate->RatedShipmentDetails->ShipmentRateDetail->TotalNetCharge->Amount;
					
					$this->combine_estimate(array(
						'code'	=> $method_id,
						'id' 	=> $rate_id,
						'label' => $rate_name,
						'cost' 	=> $rate_cost
					));
					
				}
				
				if( !empty($this->crates) ){
				
					foreach( $this->crates as $rate ){
						$this->add_this_estimate($rate);
					}
					
				}
					
			}
			
		}
		
		if( empty( $this->rates ) ){
			
			if($this->debug)
				$woocommerce->add_message('Aramex returned no rates - ensure you have defined product dimensions and weights.');
			
		}else{			
			
			if($this->debug)
				$woocommerce->add_message('All was good!');
			
		}
		
		return false;
	}	
	
	function combine_estimate( $estimate ){
	
		if( !isset($this->crates[ $estimate['id'] ]) ){
			$this->crates[ $estimate['id'] ] = $estimate;
		}else{
			$this->crates[ $estimate['id'] ]['cost'] += $estimate['cost'];
		}
		
	}
	
	function add_this_estimate($estimate){
		global $woocommerce;
		
		if( !empty( $this->custom_methods[ $estimate['code'] ][ 'price_ajustment' ] ) ){
			$estimate['cost'] = $estimate['cost'] + $this->get_fee( $this->custom_methods[ $estimate['code'] ][ 'price_ajustment' ], $estimate['cost'] );
		}
		unset($estimate['code']);

		if( !empty( $this->fee ) ) {
			$estimate['cost'] = $estimate['cost'] + $this->get_fee($this->fee, $estimate['cost']);
		}
		
		$this->add_rate( $estimate );
	}


	/**
	 * Shipping method available condition:
	 * 1. Set to yes
	 * 2. Origin country is CA
	 * 3. Dest country is in the list
	 * 
	 * @global type $woocommerce
	 * @return type 
	 */
	function is_available() {
		global $woocommerce;
		
		if ( !$this->enabled )
			return false;
			
		if($this->debug)
			$woocommerce->add_message('Aramex is enabled<br />');

		if (isset($woocommerce->cart->cart_contents_total) && isset($this->min_amount) && $this->min_amount && $this->min_amount > $woocommerce->cart->cart_contents_total)
			return false;

		if (!$this->origin_postalcode):
			return false;
		endif;

		$ship_to_countries = '';

		if ($this->availability == 'specific') :
			$ship_to_countries = $this->countries;
		else :
			if (get_option('woocommerce_allowed_countries') == 'specific') :
				$ship_to_countries = get_option('woocommerce_specific_allowed_countries');
			endif;
		endif;

		if (is_array($ship_to_countries)) :
			if (!in_array($woocommerce->customer->get_shipping_country(), $ship_to_countries))
				return false;
		endif;

		return true;
	}

	function admin_options() {
		global $woocommerce;
		?>
		<h3><?php _e('Aramex', 'wc_aramex'); ?></h3>
		<p><?php echo(sprintf(__('You must have a Key and Password to calculate Aramex Shipping, <a href="%s" target="_blank">click here</a> to register an account with Aramex.', 'wc_aramex'), 'http://www.aramex.com/developers/')); ?></p>
		<table class="form-table">
			<?php
			// Generate the HTML For the settings form.
			$this->generate_settings_html();
			?>
		</table><!--/.form-table-->
		<?php
	}
	
	/**
     * get_request function.
     *
     * @access private
     * @return void
     */
    private function set_package_requests( $package ) {

	    // Choose selected packing
    	switch ( $this->packing_method ) { 
	    	case 'per_item' :
	    	default :
	    		$packages = $this->per_item_shipping( $package );
	    	break;
    	}
    	
    	return $packages;
    }
    
    /**
     * per_item_shipping function.
     *
     * @access private
     * @param mixed $package
     * @return void
     */
    private function per_item_shipping( $package ) {
    
	    global $woocommerce;
	    
	    $packages = array();

    	// Get weight of order
    	foreach ( $package['contents'] as $item_id => $values ) {

    		if ( ! $values['data']->needs_shipping() ) {
    			if ( $this->debug )
    				$woocommerce->add_message( sprintf( __( 'Product # is virtual. Skipping.', 'wc_aramex' ), $item_id ) );
    			continue;
    		}

    		if ( ! $values['data']->get_weight() ) {
	    		if ( $this->debug )
	    			$woocommerce->add_error( sprintf( __( 'Product # is missing weight. Aborting.', 'wc_aramex' ), $item_id ) );
	    		return;
    		}

			if ( $values['data']->length && $values['data']->height && $values['data']->width ) {

				$dimensions = array( $values['data']->length, $values['data']->height, $values['data']->width );

				rsort( $dimensions );
				
				$pack = array(
					'Length'	=> max( 1, round( woocommerce_get_dimension( $dimensions[0], 'cm' ), 2 ) ),
					'Width'		=> max( 1, round( woocommerce_get_dimension( $dimensions[1], 'cm' ), 2 ) ),
					'Height'	=> max( 1, round( woocommerce_get_dimension( $dimensions[2], 'cm' ), 2 ) ),
					'Weight'	=> max( '1', round( woocommerce_get_weight( $values['data']->get_weight(), 'kg' ), 2 ) ),
					'Qty'		=> $values['quantity'],
					'Value'		=> $values['data']->get_price()
				);
				
				$packages[] = $pack;
				
			}
			
    	}
    	
    	return $packages;
    	
    }
    	
	/**
     * Generate Multiselect HTML.
     *
     * @access public
     * @param mixed $key
     * @param mixed $data
     * @since 1.0.0
     * @return string
     */
    function generate_multiselect_html ( $key, $data ) {
    	$html = '';

    	if ( isset( $data['title'] ) && $data['title'] != '' ) $title = $data['title']; else $title = '';
    	$data['options'] = (isset( $data['options'] )) ? (array) $data['options'] : array();
    	$data['class'] = (isset( $data['class'] )) ? $data['class'] : '';
    	$data['css'] = (isset( $data['css'] )) ? $data['css'] : '';

		$html .= '<tr valign="top">' . "\n";
			$html .= '<th scope="row" class="titledesc">';
			$html .= '<label for="' . $this->plugin_id . $this->id . '_' . $key . '">' . $title . '</label>';
			$html .= '</th>' . "\n";
			$html .= '<td class="forminp">' . "\n";
				$html .= '<fieldset><legend class="screen-reader-text"><span>' . $title . '</span></legend>' . "\n";
				$html .= '<select multiple="multiple" style="'.$data['css'].'" class="multiselect '.$data['class'].'" name="' . $this->plugin_id . $this->id . '_' . $key . '[]" id="' . $this->plugin_id . $this->id . '_' . $key . '">';

				foreach ($data['options'] as $option_key => $option_value) :
					$html .= '<option value="'.$option_key.'" ';
					if (isset($this->settings[$key]) && in_array($option_key, (array) $this->settings[$key])) $html .= 'selected="selected"';
					$html .= '>'.$option_value.'</option>';
				endforeach;

				$html .= '</select>';
				if ( isset( $data['description'] ) && $data['description'] != '' ) { $html .= '<span class="description">' . $data['description'] . '</span>' . "\n"; }
				if(isset($data['allbuttons']) && $data['allbuttons']){ $html .= '<br ><input type="button" class="button select_all_aramex_shipping" value="'.__('Select All', 'wc_aramex').'"><button class="button select_none_aramex_shipping">'.__('Select None', 'wc_aramex').'</button>'."<script type=\"text/javascript\">jQuery(function(){jQuery('.select_all_aramex_shipping').live('click', function(){jQuery('#woocommerce_aramex_shipping_methods option').attr('selected','selected');jQuery('#woocommerce_aramex_shipping_methods').trigger('liszt:updated');return false;});jQuery('.select_none_aramex_shipping').live('click', function(){jQuery('#woocommerce_aramex_shipping_methods option').removeAttr('selected');jQuery('#woocommerce_aramex_shipping_methods').trigger('liszt:updated');return false;})});</script>"; }
			$html .= '</fieldset>';
			$html .= '</td>' . "\n";
		$html .= '</tr>' . "\n";

    	return $html;
    }
    
    function generate_custom_methods_html ( $key, $data ) {
    	global $woocommerce;
    	
    	ob_start();
		?>
		<tr valign="top" id="method_options">
			<th scope="row" class="titledesc"><?php _e( 'Services', 'wc_aramex' ); ?></th>
			<td class="forminp">
				<table class="wc_tax_rates widefat">
					<thead>
						<tr>
							<th class="sort">&nbsp;</th>
			
							<th><?php _e( 'Method name', 'wc_aramex' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('The method name that will be show on the cart and checkout.', 'wc_aramex'); ?>">[?]</span></th>
			
							<th><?php _e( 'Price ajustment', 'wc_aramex' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('Surcharge for this method, enter either a fixed amount (Ex.: 3.34) or a % amount (Ex.: 3.34%)', 'wc_aramex'); ?>">[?]</span></th>
			
							<th style="width:8%;"><?php _e( 'Enabled', 'wc_aramex' ); ?>&nbsp;<span class="tips" data-tip="<?php _e('Enable this shipping method or not', 'wc_aramex'); ?>">[?]</span></th>
			
						</tr>
					</thead>
					<tbody id="methods">
					
					<?php if( !empty( $this->custom_methods ) ){ ?>
					
					<?php foreach( $this->custom_methods as $method_key => $service ){ ?>
					
						<tr>
							<td class="sort"><input type="hidden" class="order_shipping_method" name="order_shipping_method[]" value="<?php echo( $method_key ); ?>" /></td>
	
							<td class="method_name">
								<input type="text" name="method_name[]" value="<?php echo esc_attr( $service['name'] ) ?>" />
							</td>
	
							<td class="price_ajustment">
								<input type="text" name="method_price_ajustment[]" value="<?php echo esc_attr( $service['price_ajustment'] ) ?>" placeholder="<?php _e( 'Ex.: 3.34 or 3.34%', 'wc_aramex' ); ?>" />
							</td>
	
							<td class="method_enabled" style="width:8%;" align="center">
								<input type="checkbox" class="checkbox" name="method_enabled_<?php echo($method_key); ?>" value="1" style="width:auto;"<?php echo( $service['enabled'] ? ' checked="checked"' : '' ); ?> />
							</td>
	
						</tr>
					
					<?php } ?>
					
					<?php } ?>

					</tbody>
				</table>
				<script type="text/javascript">
					jQuery( function() {
						jQuery('.wc_tax_rates tbody').sortable({
							items:'tr',
							cursor:'move',
							axis:'y',
							scrollSensitivity:40,
							forcePlaceholderSize: true,
							helper: 'clone',
							opacity: 0.65,
							placeholder: 'wc-metabox-sortable-placeholder',
							start:function(event,ui){
								ui.item.css('background-color','#f6f6f6');
							},
							stop:function(event,ui){
								ui.item.removeAttr('style');
							}
						});
					});
				</script>
			</td>
		</tr>
		<?php
		
		return ob_get_clean();
		
    }
    
    public function validate_custom_methods_field( $key ) {
    
		$custom_methods = array();
		
		if( !empty( $_POST['order_shipping_method'] ) ){
			
			foreach( $_POST['order_shipping_method'] as $key => $method_key ){
				
				$custom_methods[ $method_key ] = array(
					'name'				=> woocommerce_clean( $_POST['method_name'][$key] ),
					'price_ajustment'	=> woocommerce_clean( $_POST['method_price_ajustment'][$key] ),
					'enabled'			=> isset( $_POST['method_enabled_'.$method_key] ) ? 1 : 0
				);
				
			}
			
		}

		return $custom_methods;
		
	}
	
	function get_currency($from_Currency, $to_Currency, $amount) {
 
	$amount = urlencode($amount);
	$from_Currency = urlencode($from_Currency);
	$to_Currency = urlencode($to_Currency);
	 
	$url = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";
	 
	$ch = curl_init();
	$timeout = 0;
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	 
	curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$rawdata = curl_exec($ch);
	curl_close($ch);
	$data = explode('bld>', $rawdata);
	$data = explode($to_Currency, $data[1]);
		 if($from_Currency == $to_Currency){ 
 return $amount;
 }
 else{
	return round($data[0], 2);
 }
	}

}
	
?>