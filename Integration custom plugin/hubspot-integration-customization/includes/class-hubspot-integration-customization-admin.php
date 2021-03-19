<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       makwebbetter.com
 * @since      1.0.0
 *
 * @package    Hubspot_Integration_Customization
 * @subpackage Hubspot_Integration_Customization/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hubspot_Integration_Customization
 * @subpackage Hubspot_Integration_Customization/admin
 * @author     MakeWebBetter <support@makewebbetter.com>
 */
class Hubspot_Integration_Customization_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function hubwoo_update_ecomm_deal($order_id) {
		if ( empty( $order_id ) ) {
			return;
		}		
		wp_schedule_single_event( time() + 10, "hubwoo_sync_extra_deal_data", array( $order_id ) );
	}
 
	public function hubwoo_product_did_updated($product_id) {

		//Direct Call
		// $this->hubwoo_single_schedule_event('ticket-product', $product_id);

		// Updating Product inventory
		// wp_schedule_single_event( time() + 10, "hubwoo_single_schedule_event", array( 'inv-update', $product_id ) );
	
		// Updating the ticket product
		wp_schedule_single_event( time() + 10, "hubwoo_single_schedule_event", array( 'ticket-product', $product_id ) );
		
	}

	public function hubwoo_order_did_updated($order_id) {
		
		// Updating the ticket product
		wp_schedule_single_event( time() + 10, "hubwoo_single_schedule_event", array( 'ticket-order', $order_id ) );
	}
		
	public function hubwoo_single_schedule_event( $sync_type, $object_id ) {

		if (empty($sync_type) || empty($object_id)) { return; }			

		switch ($sync_type) {
			case 'ticket-product':
				$box_office = new Hubspot_Integration_Customization_Box_Office();
				$box_office->hubwoo_update_ticket_products($object_id);			
				break;
			case 'ticket-order':
				$box_office = new Hubspot_Integration_Customization_Box_Office();
				$box_office->hubwoo_create_ticket_contacts($object_id);			
				break;
			case 'inv-update':
				$manage_products = new Hubspot_Integration_Customization_Manage_Products();
				$manage_products->hubwoo_update_inventory($object_id);			
				break;
			default:
				return;
		}
	}	

	public function hubwoo_sync_extra_deal_data($order_id) {

		$properties = array();	

		$deal_id = get_post_meta( $order_id, "hubwoo_ecomm_deal_id", true );
		
		if ( empty( $deal_id ) ) {
			wp_schedule_single_event( time() + 30, "hubwoo_update_ecomm_deal", array( $order_id ) );
			return;
		}	

		// add custom code for deals here

	}

	public function hubwoo_abandoned_cart_html( $products_html, $cart_products ) {

		$retrive_url = get_site_url()."/cart/?hubwoo-abncart-retrieve=";

		$total_cart_products = count($cart_products);

		$width = $total_cart_products < 4 ? 100 / $total_cart_products : 25;
		
		$table_width = $total_cart_products > 4 ? "100%" : "auto";

		$products_html = '<div></div><!--[if mso]><center><table width="100%" style="width:auto"><![endif]--><table style="font-size: 14px; font-family: Arial, sans-serif; line-height: 20px; text-align: left; table-layout: fixed;" width="'.$table_width.'"><thead><tr><th colspan="4"><h2 style="width: 400px;margin: 0 auto;font-size: 30px;font-weight: 900;padding: 15px;text-align: center;line-height: 1.2">We noticed you left something in your cart.</h2></th></tr></thead><tbody><tr>';

		$rows = ceil ( $total_cart_products / 4 );

		$p_counter = 0;

		for ( $i=0; $i<$rows; $i++) {

			$products_html .= '<tr>';
		
			for ($j=0; $j < $total_cart_products; $j++, $p_counter++) { 

				if( $j > 3 || $p_counter >= $total_cart_products) {
					break;
				}

				$retrive_url .= $cart_products[$p_counter]['item_id'].':'.$cart_products[$p_counter]['qty'].',';
				
				$products_html .= '<td style="width: '.$width.'%;padding: 15px;text-align: center;" ><img height="150px" width="120px" src="'.$cart_products[$p_counter]['image'][0].'" style="display: block;width: 100%"><h4 style="margin:0;padding: 0 0 8px 0"><a style="text-decoration:none; color:#4e4e4e;" href="' . $cart_products[$p_counter]['url'] . '">' . $cart_products[$p_counter]['name'] . '</a></h4><h3 style="margin:0;font-size: 15px; color:#4e4e4e;padding: 0 0 8px 0">'. wc_price( $cart_products[$p_counter]['price'], array( 'currency' => get_option( 'woocommerce_currency' ) ) ) . '</h3></td>';					
			}
			$products_html .= '</tr>';
		}
		
		$products_html .= '<tr><td style="text-align: center;"colspan="4"><a target="_blank" href="'.$retrive_url.'" style="background-color: #000000;color:#ffffff;display: inline-block;text-decoration: none;font-size: 16px;font-weight: 700;padding: 8px 20px">Checkout Now</a></td></tr></tbody></table><!--[if mso]></table></center><![endif]--><div></div>';

		return $products_html;
	}


	// 	WooCommerce PDF Invoices
	private function hubwoo_sync_woo_pdf_invoices($order_id,$deal_id) {

		$invoice = get_post_meta( $order_id, '_bewpi_invoice_pdf_path', true );
		
		if( ! empty( $invoice ) ) {
			
			$attachment_id = get_post_meta( $order_id, "hubwoo_uploaded_invoice_id", true );

			if( empty( $attachment_id ) ) {
				$response = Hubspot_Integration_Customization_Rest::get_instance()->upload_file( $invoice, $order_id );

				if( 200 == $response['status_code'] ) {
					
					$response = json_decode($response['response'],true);
					$attachment_id = $response['objects'][0]['id'];			
					update_post_meta( $order_id, "hubwoo_uploaded_invoice_id", $attachment_id );
				}
				if( !empty( $attachment_id ) ) {
					Hubspot_Integration_Customization_Rest::get_instance()->create_attachment( $deal_id, $attachment_id );		
				}
			}
		}
	}

	// WooCommerce PDF Invoices & Packing Slips 
	public function hubwoo_sync_woo_pdf_slip_invoices($order_id,$deal_id) {

		$invoice = get_post_meta( $order_id, '_wcpdf_invoice_number', true );
		if( ! empty( $invoice ) ) {
			
			$attachment_id = get_post_meta( $order_id, "hubwoo_uploaded_invoice_id", true );

			if( empty( $attachment_id ) ) {
				$invoice = 'invoice-'.$invoice;
				$response = Hubspot_Integration_Customization_Rest::get_instance()->upload_woo_slip( $invoice, $order_id );
				if( 200 == $response['status_code'] ) {
					
					$response = json_decode($response['response'],true);
					$attachment_id = $response['objects'][0]['id'];			
					update_post_meta( $order_id, "hubwoo_uploaded_invoice_id", $attachment_id );
				}
				if( !empty( $attachment_id ) ) {
					Hubspot_Integration_Customization_Rest::get_instance()->create_attachment( $deal_id, $attachment_id );		
				}
			}
		}
	}		
	
	// Billing and shipping details.
	private function hubwoo_sync_order_details($order_id, $deal_id) {

		$order = new WC_Order( $order_id );
		
		$billing_data = $deal_details = array();
		
		$billing_data['billing_one']  	  = $order->get_billing_address_1();
		$billing_data['billing_two']  	  = $order->get_billing_address_2();
		$billing_data['billing_company']  = $order->get_billing_company();
		$billing_data['billing_city'] 	  = $order->get_billing_city();
		$billing_data['billing_state'] 	  = $order->get_billing_state();
		$billing_data['billing_postcode'] = $order->get_billing_postcode();
		$billing_data['billing_country']  = $order->get_billing_country();
		$billing_data['billing_phone'] 	  = $order->get_billing_phone();
		$billing_data['shipping_company'] = $order->get_shipping_company();
		$billing_data['shipping_one'] 	  = $order->get_shipping_address_1();
		$billing_data['shipping_two'] 	  = $order->get_shipping_address_2();
		$billing_data['shipping_city'] 	  = $order->get_shipping_city();
		$billing_data['shipping_state']   = $order->get_shipping_state();
		$billing_data['shipping_postcode']= $order->get_shipping_postcode();
		$billing_data['shipping_country'] = $order->get_shipping_country();

		foreach ( $billing_data as $key => $value ) {

			if( ! empty( $value ) ) {
				$deal_details['properties'][] = array('name' => $key, 'value' => $value);
			}
		}

		if(!empty($deal_details['properties'])) {

			$active_plugins = get_option('active_plugins');										

			if( in_array('makewebbetter-hubspot-for-woocommerce/makewebbetter-hubspot-for-woocommerce.php', $active_plugins ) ) {
				HubWooConnectionMananager::get_instance()->update_existing_deal( $deal_id, $deal_details );
			} elseif ( in_array('hubspot-deal-per-order/hubspot-deal-per-order.php', $active_plugins) ) {
				HubWooDealsCallbacks::get_instance()->hubwoo_update_deals( $deal_details, $deal_id );
			}
		}
	}

	private function hubwoo_order_properties() {

		// Deal groups    
		$group[] = array( 'name' => 'order_billing_shipping', 'displayName' => __( 'Order Billing and Shipping', 'hubwoo' ) );

		$billing_data['billing_one']      = 'Billing Line One';
		$billing_data['billing_two']      = 'Billing Line Two';
		$billing_data['billing_company']  = 'Billing Company';
		$billing_data['billing_city']     = 'Billing City';
		$billing_data['billing_state']    = 'Billing State';
		$billing_data['billing_postcode'] = 'Billing Postcode';
		$billing_data['billing_country']  = 'Billing Country';
		$billing_data['billing_phone']    = 'Billing Phone';
		$billing_data['shipping_company'] = 'Shipping Company';
		$billing_data['shipping_one']     = 'Shipping Line One';
		$billing_data['shipping_two']     = 'Shipping Line Two';
		$billing_data['shipping_city']    = 'Shipping City';
		$billing_data['shipping_state']   = 'Shipping State';
		$billing_data['shipping_postcode']= 'Shipping Postcode';
		$billing_data['shipping_country'] = 'Shipping Country';

		$propertyDetails = array();

		foreach ($billing_data as $key => $value) {
			$propertyDetails[] = array(
				"name"      => $key,
				"label"     => $value,
				"type"      => "string",
				"fieldType" => "textarea",
				"formfield" => false,
				'groupName' => 'order_billing_shipping',
			);
		}
	}

	/**
	 * Syncing of custom fields from woocommerce
	 *
	 * @param [sring] $order_id is the last order id.
	 */
	public function woocommerce_custom_field( $order_id ) {
		//echo 'hello world';
		$order_id = '24211';
		echo $order_id;
	//	die;
		if( ! empty( $order_id ) ) {
			//echo $order_id;
			//die('test');
			// code to sync payment method
			$orders = wc_get_order( $order_id );
			$payment = $orders->get_payment_method_title();
			echo $payment;
			echo '<br>';


			// code to sync total discount
			$orders = wc_get_order( $order_id );
			$discount = $orders->get_discount_total();

			// code to sync shipping total in order
			$orders = wc_get_order( $order_id );
			$shipping = $orders->get_shipping_total();

			//code for the custom delivery number on billing address
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta) {
				$meta = $meta->get_data();
				if ( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if ( $data == '_billing_tel_consegna' ) {
						$billing_delivery_number = $meta['value'];
					}
				}
			}
			echo $billing_delivery_number;
			echo '<br>';
			//code for the delivery number on shiiping address
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta) {
				$meta = $meta->get_data();
				if ( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if ( $data == '_shipping_tel_consegna' ) {
						$shipping_delivery_number = $meta['value'];
					}
				}
			}
			echo $shipping_delivery_number;
			echo '<br>';
			//code for getting billing phone number
			$orders = wc_get_order( $order_id );
			$array_data = $orders->get_data();
			$billing_detail = $array_data['billing'];
			$billing_phone =  $billing_detail['phone'];
			echo $billing_phone;
			echo '<br>';
			//code to get out of town option from order 
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( !empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'is_oot' ) {
						$out_of_town = $meta['value'];
						if( $out_of_town ) {
							$out_of_town = 'yes';
						}
						else{
							$out_of_town = 'no';
						}
					}
				}
			}
			echo $out_of_town;
			echo '<br>';

			//code to get order delivery date

			//also convert this date into time stamp
			// $orders = wc_get_order( $order_id );
			// $meta = $orders->get_meta_data();
			// foreach ( $meta as $meta ) {
			// 	$meta = $meta->get_data();
			// 	if( ! empty( $meta ) && is_array( $meta ) ) {
			// 		$data = $meta['key'];
			// 		if( $data == 'data_consegna' ) {
			// 			$delivery_date = $meta['value'];
			// 		}
			// 	}
			// }

			//code for start delivery time
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'inizio_ora_consegna' ) {
						$start_delivery_time = $meta['value'];
					}
				}
			}
			echo $start_delivery_time;
			echo '<br>';
			//code for end delivery time
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'fine_ora_consegna' ) {
						$end_delivery_time = $meta['value'];
					}
				}
			}
			echo $end_delivery_time;
			echo '<br>';
			//code for take away status
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'is_client_pickup' ) {
						$take_away = $meta['value'];
						if( $take_away ){
							$take_away = 'yes';
						}
						else{
							$take_away = 'no';
						}
					}
				}
			}
			echo $take_away;
			echo '<br>';
			//code for getting busniess status
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'is_business' ) {
						$busniness = $meta['value'];
						if( $busniness ){
							$busniness = 'yes';
						}
						else{
							$busniness = 'no';
						}
					}
				}
			}
			echo $busniness;
			echo '<br>';
			//code to get multi-order or multi-store order status
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'multinegozio_is_multiorder' ) {
						$multi_store = $meta['value'];
						if( $multi_store ){
							$multi_store = 'yes';
						}
						else{
							$multi_store = 'no';
						}
					}
				}
			}
			echo $multi_store;
			echo '<br>';

			//code for getting FCP status(is_b2b  status)
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'is_b2b' ) {
						$b2b = $meta['value'];
						if( $b2b ){
							$b2b = 'yes';
						}
						else{
							$b2b = 'no';
						}
					}
				}
			}
			echo $b2b;
			echo '<br>';


			$deal_id = get_post_meta( $order_id, 'hubwoo_ecomm_deal_id', true );

			if ( ! empty( $payment ) ) {
				$properties[] = array(
					'name'  => 'payment_method',
					'value' => $payment,
				);
			}

			if ( ! empty( $discount ) ) {
				$properties[] = array(
					'name'  => 'total_discount',
					'value' => $discount,
				);
			}

			if ( ! empty( $shipping ) ) {
				$properties[] = array(
					'name'  => 'total_shipping',
					'value' => $discount,
				);
			}


			if ( ! empty( $billing_delivery_number ) ) {
				echo 'builling delivery num not empty';
					echo '<br>';
				$properties[] = array(
					'name'  => 'billing_delivery_phone',
					'value' => $billing_delivery_number,
				);
			}

			if ( ! empty( $shipping_delivery_number ) ) {
				echo 'shipping delivery num not empty';
					echo '<br>';
				$properties[] = array(
					'name'  => 'shipping_delivery_phone',
					'value' => $shipping_delivery_number,
				);
			}

			if ( ! empty( $billing_phone ) ) {
				echo 'billing phone num not empty';
					echo '<br>';
				$properties[] = array(
					'name'  => 'billing_phone',
					'value' => $billing_phone,
				);
			}

			if ( ! empty( $out_of_town ) ) {
				echo 'out_of_town not empty';
					echo '<br>';
				$properties[] = array(
					'name'  => 'out_of_town',
					'value' => $out_of_town,
				);
			}

			if ( ! empty( $start_delivery_time ) ) {
				echo 'start_delivery_time not empty';
					echo '<br>';
				$properties[] = array(
					'name'  => 'start_delivery_time',
					'value' => $start_delivery_time,
				);
			}

			if ( ! empty( $end_delivery_time ) ) {
				echo 'end_delivery_time not empty';
					echo '<br>';
				$properties[] = array(
					'name'  => 'end_delivery_time',
					'value' => $end_delivery_time,
				);
			}

			if ( ! empty( $take_away ) ) {
				echo 'take away not empty';
					echo '<br>';
				$properties[] = array(
					'name'  => 'take_away',
					'value' => $take_away,
				);
			}

			if ( ! empty( $busniness ) ) {
				echo 'busniness not empty';
					echo '<br>';
				$properties[] = array(
					'name'  => 'business',
					'value' => $busniness,
				);
			}

			if ( ! empty( $multi_store ) ) {
					echo 'multi_store not empty';
						echo '<br>';
				$properties[] = array(
					'name'  => 'multi_store',
					'value' => $multi_store,
				);
			}

			if ( ! empty( $b2b ) ) {
				echo 'b2b not empty';
				echo '<br>';
				$properties[] = array(
					'name'  => 'is_b2b',
					'value' => $b2b,
				);
			}

			

			if ( ! empty( $properties ) ) {
				$properties = array( 'properties' => $properties );
				self::hubwoo_update_deals( $properties, $deal_id );
			}
			echo '<pre>';
			print_r($properties);
			die();
			return $properties;

		}
	}

	/**
	 * Hubwoo update deal
	 *
	 * @param [string] $deal_details is the detail of deal.
	 * @param [string] $deal_id is the id of deal.
	 */
	public static function hubwoo_update_deals( $deal_details, $deal_id ) {
		if ( ! empty( $deal_details ) ) {
			$flag = true;

			if ( Hubwoo::is_access_token_expired() ) {
				$hapikey = HUBWOO_CLIENT_ID;
				$hseckey = HUBWOO_SECRET_ID;
				$status  = HubWooConnectionMananager::get_instance()->hubwoo_refresh_token( $hapikey, $hseckey );

				if ( ! $status ) {
					$flag = false;
				}
			}
			if ( $flag ) {
				$response = HubWooConnectionMananager::get_instance()->update_existing_deal( $deal_id, $deal_details );
			}
		}
	}

	/**
	 * Function to extend properties of contact properties
	 *
	 */
	public function hubwoo_extend_contact_properties( $properties, $user_id ) {

		//code to get the preferred city of the user in contact propertiess
		$customer = new WC_Customer( $user_id );
		if( ! empty( $customer ) ) {
			$last_order = $customer->get_last_order();
			$order_id   = $last_order->get_id();
			$preferred_city = get_post_meta( $order_id, 'cp_city_slug', true );
		}
		
		//code to get birth date of the user from user meta
		if( ! empty( $user_id ) ) {
			$birth_date = get_user_meta( $user_id, 'birth_date', true );
		}
		
		if ( ! empty( $preferred_city ) ) {
			$properties[] = array(
				'property' => 'preferred_cities',
				'value'    => $preferred_city,
			);
		}

		if ( ! empty( $birth_date ) ) {
			$properties[] = array(
				'property' => 'birth_date',
				'value'    => $birth_date,
			);
		}
		//echo '<pre>';
		//print_r($properties);
		return $properties;

	}

	function woocommerce_thankyou_test() {
		echo 'WooCommerce thank you hook is working perectly';
	}
}
