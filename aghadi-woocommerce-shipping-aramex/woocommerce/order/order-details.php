<?php
/**
 * Order details
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

$order = new WC_Order( $order_id );
?>
<h2><?php _e( 'Order Details', 'woocommerce' ); ?></h2>
<table class="shop_table order_details">
	<thead>
		<tr>
			<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total"><?php _e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tfoot>
	<?php
		if ( $totals = $order->get_order_item_totals() ) foreach ( $totals as $total ) :
			?>
			<tr>
				<th scope="row"><?php echo $total['label']; ?></th>
				<td><?php echo $total['value']; ?></td>
			</tr>
			<?php
		endforeach;
	?>
	</tfoot>
	<tbody>
		<?php
		if ( sizeof( $order->get_items() ) > 0 ) {

			foreach( $order->get_items() as $item ) {
				$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
				$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );

				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
					<td class="product-name">
						<?php
							if ( $_product && ! $_product->is_visible() )
								echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
							else
								echo apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item );

							echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item );

							$item_meta->display();

							if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

								$download_files = $order->get_item_downloads( $item );
								$i              = 0;
								$links          = array();

								foreach ( $download_files as $download_id => $file ) {
									$i++;

									$links[] = '<small><a href="' . esc_url( $file['download_url'] ) . '">' . sprintf( __( 'Download file%s', 'woocommerce' ), ( count( $download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html( $file['name'] ) . '</a></small>';
								}

								echo '<br/>' . implode( '<br/>', $links );
							}
						?>
					</td>
					<td class="product-total">
						<?php echo $order->get_formatted_line_subtotal( $item ); ?>
					</td>
				</tr>
				<?php

				if ( in_array( $order->status, array( 'processing', 'completed' ) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) {
					?>
					<tr class="product-purchase-note">
						<td colspan="3"><?php echo apply_filters( 'the_content', $purchase_note ); ?></td>
					</tr>
					<?php
				}
			}
		}

		do_action( 'woocommerce_order_items_table', $order );
		?>
	</tbody>
</table>

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

<header>
	<h2><?php _e( 'Customer details', 'woocommerce' ); ?></h2>
</header>
<dl class="customer_details">
<?php
	if ( $order->billing_email ) echo '<dt>' . __( 'Email:', 'woocommerce' ) . '</dt><dd>' . $order->billing_email . '</dd>';
	if ( $order->billing_phone ) echo '<dt>' . __( 'Telephone:', 'woocommerce' ) . '</dt><dd>' . $order->billing_phone . '</dd>';

	// Additional customer details hook
	do_action( 'woocommerce_order_details_after_customer_details', $order );
?>
</dl>

<?php if ( get_option( 'woocommerce_ship_to_billing_address_only' ) === 'no' && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

<div class="col2-set addresses">

	<div class="col-1">

<?php endif; ?>

		<header class="title">
			<h3><?php _e( 'Billing Address', 'woocommerce' ); ?></h3>
		</header>
		<address><p>
			<?php
				if ( ! $order->get_formatted_billing_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_billing_address();
			?>
		</p></address>

<?php if ( get_option( 'woocommerce_ship_to_billing_address_only' ) === 'no' && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

	</div><!-- /.col-1 -->

	<div class="col-2">

		<header class="title">
			<h3><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>
		</header>
		<address><p>
			<?php
				if ( ! $order->get_formatted_shipping_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_shipping_address();
			?>
		</p></address>

	</div><!-- /.col-2 -->

</div><!-- /.col2-set -->

<?php endif; ?>

<div class="col2-set addresses">
<header class="title">
			<h3><?php _e( 'Tracking information', 'woocommerce' ); ?></h3>
</header>
<form method="post" name="frmtrack" id="frmtrack">
AWB Number: <input type="text" name="awb_number" id="awb_number"  /> 
<input type="hidden" name="submit_track_detail_hdn" id="submit_track_detail_hdn" value="1" />
<input type="submit" name="submit_track_detail" id="submit_track_detail" value="Track detail"  />
</form>
<?php


	

if($_REQUEST['submit_track_detail_hdn'] == '1'){
 if($_REQUEST['awb_number'] != ''){	
$shipment_number = $_REQUEST['awb_number'];
		  //// Track shipment api start
		 $siteurl = get_bloginfo('siteurl');
		$shipments_tracking_wsdl_path = $siteurl.'/wp-content/plugins/aghadi-woocommerce-shipping-aramex/classes/wsdl/production/shipments_tracking_api_wsdl.wsdl';
//			//$clientAramex = $this->create_soap_client( $shipments_tracking_wsdl_path );
		
		$clientAramex = new SoapClient($shipments_tracking_wsdl_path);
		
		
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
		
$aramexParams['ClientInfo'] 	=  array(
									 'AccountCountryCode'        => $aramex_country_code,
                                    'AccountEntity'             => $aramex_account_entity,
                                    'AccountNumber'            => $aramex_account_number,
                                    'AccountPin'                => $aramex_account_pin,
                                    'UserName'                 => $aramex_account_username,
                                    'Password'                 => $aramex_account_password,
                                    'Version'                 => $aramex_api_version
								);
		$aramexParams['Transaction'] 	= array('Reference1' => '001' );
		$aramexParams['Shipments'] 		= array($shipment_number);

        $_resAramex = $clientAramex->TrackShipments($aramexParams);
	
		if(is_object($_resAramex) && !$_resAramex->HasErrors){
				if(!empty($_resAramex->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult)){
					echo getTrackingInfoTable($_resAramex->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult);
				} 

		}
		//// Track shipment end
		   
	
	} else { echo "<div>Please enter AWB Number to track order!</div>"; } }
	
/// My Tracking function start 
 function getTrackingInfoTable($HAWBHistory) {
		$_resultTable = '<header class="title"><h3>Tracking information</h3></header>';
        $_resultTable = '<table summary="Item Tracking" style="width:100%;">';
        $_resultTable .= '<col width="1">
                          <col width="1">
                          <col width="1">
                          <col width="1">
                          <thead>
                          <tr class="first last">
                          <th>Location</th>
                          <th>Action Date/Time</th>
                          <th class="a-right">Tracking Description</th>
                          <th class="a-center">Comments</th>
                          </tr>
                          </thead><tbody>';

        foreach ($HAWBHistory as $HAWBUpdate) {

            $_resultTable .= '<tr>
                <td align="center">' . $HAWBUpdate->UpdateLocation . '</td>
                <td align="center">' . $HAWBUpdate->UpdateDateTime . '</td>
                <td align="center">' . $HAWBUpdate->UpdateDescription . '</td>
                <td align="center">' . $HAWBUpdate->Comments . '</td>
                </tr>';
        }
        $_resultTable .= '</tbody></table>';

        return $_resultTable;
    }
 ?>
</div>

<div class="clear"></div>
