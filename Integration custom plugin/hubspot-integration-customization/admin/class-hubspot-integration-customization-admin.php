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

			//code to get payment mnethod
			$orders = wc_get_order( $order_id );
			$payment = $orders->get_payment_method_title();

			// code to sync total discount
			$orders = wc_get_order( $order_id );
			$discount = $orders->get_discount_total();

			// code to sync shipping total in order
			$orders = wc_get_order( $order_id );
			$shipping = $orders->get_shipping_total();

			//code to get currency of order
			$orders = wc_get_order( $order_id );
			$currency = $orders->get_currency();

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

			//code for getting billing phone number
			$orders = wc_get_order( $order_id );
			$array_data = $orders->get_data();
			$billing_detail = $array_data['billing'];
			$billing_phone =  $billing_detail['phone'];

			//code to get out of town option from order 
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( !empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'is_oot' ) {
						$out_of_town = 'true';						
					} else {
						$out_of_town = 'false';
					}
				}
			}
		
			//code to get order delivery date
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'data_consegna' ) {
						$date = $meta['value'];
						$unix_timestamp = strtotime($date);
						$delivery_date = HubwooGuestOrdersManager::hubwoo_set_utc_midnight( $unix_timestamp );
					}
				}
			}

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

			//code for take away status
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'is_client_pickup' ) {
						$take_away = $meta['value'];
						if( $take_away ) {
							$take_away = 'true';
						}
						else {
							$take_away = 'false';
						}
					}
				}
			}

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
							$busniness = 'true';
						}
						else{
							$busniness = 'false';
						}
					} else{
							$busniness = 'false';
						}
				}
			}

			//code to get multi-order or multi-store order status
			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			$multi_store = 'false';
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'multinegozio_is_multiorder' ) {
						if( $meta['value'] ) { 
							$multi_store = 'true';
						}
						break;
					}

				}
			}

			//code for getting parent order status
			//$orders = wc_get_order( $order_id );
			$is_master = get_post_meta( $order_id, 'multinegozio_is_master', true );
			if( $is_master ) {
				$is_master = 'true';
			}
			else{
				$is_master = 'false';
			}

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
							$b2b = 'true';
						}
						else{
							$b2b = 'false';
						}
					}
				}
			}

			$orders = wc_get_order( $order_id );	
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();

			 	if( ! empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					
					if( ! empty( $data ) && $data == 'gift_message' ) {
						$gift_message = $meta['value'];
					}
					if( ! empty( $data ) && $data == 'ship_to_friend' ) {
						$ship_to_friend = $meta['value'];
					}
					if( ! empty( $gift_message ) || ! empty( $ship_to_friend ) ) {
						$gift = 'true';
					} else{
						$gift = 'false';
					}	
				}
			}

			$orders = wc_get_order( $order_id );
			$meta = $orders->get_meta_data();
			foreach ( $meta as $meta ) {
				$meta = $meta->get_data();
				if( !empty( $meta ) && is_array( $meta ) ) {
					$data = $meta['key'];
					if( $data == 'cp_city_slug' ) {
						$city = $meta['value'];
					}	
				}	
			}

			//$orders = wc_get_order( $order_id );
			$orders = get_post_meta( $order_id );
			if( array_key_exists( 'exp_multinegozio_parent', $orders ) ) {
				$parent_order_id = $orders['exp_multinegozio_parent'][0];
			}

			$orders = wc_get_order( $order_id );
			$shipping_address_1 = $orders->get_shipping_address_1();
			$shipping_address_2 = $orders->get_shipping_address_2();
			$shipping_city      = $orders->get_shipping_city();
			$shipping_state     = $orders->get_shipping_state();
			$shipping_postcode  = $orders->get_shipping_postcode();
			$shipping_country   = $orders->get_shipping_country();

			$billing_address_1 = $orders->get_billing_address_1();
			$billing_address_2 = $orders->get_billing_address_2();
			$billing_city      = $orders->get_billing_city();
			$billing_state 	   = $orders->get_billing_state();
			$billing_postcode  = $orders->get_billing_postcode();
			$billing_country   = $orders->get_billing_country();

			$multi = get_post_meta( $order_id, 'multinegozio_is_multiorder', true );
			$master = get_post_meta( $order_id, 'multinegozio_is_master', true );
			if( $multi && $master != '1'){
				$parent_order_id = get_post_meta( $order_id, 'exp_multinegozio_parent', true );
			}


			if( ! empty ( $shipping_address_1 ) ) {
				$properties[] = array(
					'name'  => 'shipping_address_1',
					'value' => $shipping_address_1,
				);
			}

			if( ! empty ( $shipping_address_2 ) ) {
				$properties[] = array(
					'name'  => 'shipping_address_2',
					'value' => $shipping_address_2,
				);
			}

			if( ! empty ( $shipping_city ) ) {
				$properties[] = array(
					'name'  => 'shipping_city',
					'value' => $shipping_city,
				);
			}

			if( ! empty ( $shipping_state ) ) {
				$properties[] = array(
					'name'  => 'shipping_state',
					'value' => $shipping_state,
				);
			}

			if( ! empty ( $shipping_postcode ) ) {
				$properties[] = array(
					'name'  => 'shipping_postcode',
					'value' => $shipping_postcode,
				);
			}

			if( ! empty ( $shipping_country ) ) {
				$properties[] = array(
					'name'  => 'shipping_country',
					'value' => $shipping_country,
				);
			}


			if( ! empty ( $billing_address_1 ) ) {
				$properties[] = array(
					'name'  => 'billing_address_1',
					'value' => $billing_address_1,
				);
			}

			if( ! empty ( $billing_address_2 ) ) {
				$properties[] = array(
					'name'  => 'billing_address_2',
					'value' => $billing_address_2,
				);
			}

			if( ! empty ( $billing_city ) ) {
				$properties[] = array(
					'name'  => 'billing_city',
					'value' => $billing_city,
				);
			}

			if( ! empty ( $billing_state ) ) {
				$properties[] = array(
					'name'  => 'billing_state',
					'value' => $billing_state,
				);
			}

			if( ! empty ( $billing_postcode ) ) {
				$properties[] = array(
					'name'  => 'billing_postcode',
					'value' => $billing_postcode,
				);
			}

			if( ! empty ( $billing_country ) ) {
				$properties[] = array(
					'name'  => 'billing_country',
					'value' => $billing_country,
				);
			}

			if ( ! empty( $parent_order_id ) ) {
				$properties[] = array(
					'name'  => 'parent_order',
					'value' => $parent_order_id,
				);
			}


			if ( ! empty( $payment ) ) {
				$properties[] = array(
					'name'  => 'payment_method',
					'value' => $payment,
				);
			}
			
			if ( ! empty( $payment ) ) {
				$properties[] = array(
					'name'  => 'payment_method',
					'value' => $payment,
				);
			}

			if ( ! empty( $currency ) ) {
				$properties[] = array(
					'name'  => 'deal_currency_code',
					'value' => $currency,
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
					'value' => $shipping,
				);
			}


			if ( ! empty( $billing_delivery_number ) ) {
				$properties[] = array(
					'name'  => 'billing_delivery_phone',
					'value' => $billing_delivery_number,
				);
			}

			if ( ! empty( $shipping_delivery_number ) ) {
				$properties[] = array(
					'name'  => 'shipping_delivery_phone',
					'value' => $shipping_delivery_number,
				);
			}

			if ( ! empty( $billing_phone ) ) {
				$properties[] = array(
					'name'  => 'billing_phone',
					'value' => $billing_phone,
				);
			}

			if ( ! empty( $out_of_town ) ) {
				$properties[] = array(
					'name'  => 'out_of_town',
					'value' => $out_of_town,
				);
			}

			if ( ! empty( $delivery_date ) ) {
				$properties[] = array(
					'name'  => 'delivery_date',
					'value' => $delivery_date,
				);
			}

			if ( ! empty( $start_delivery_time ) ) {
				$properties[] = array(
					'name'  => 'start_delivery_time',
					'value' => $start_delivery_time,
				);
			}

			if ( ! empty( $end_delivery_time ) ) {
				$properties[] = array(
					'name'  => 'end_delivery_time',
					'value' => $end_delivery_time,
				);
			}

			if ( ! empty( $take_away ) ) {
				$properties[] = array(
					'name'  => 'take_away',
					'value' => $take_away,
				);
			}

			if ( ! empty( $busniness ) ) {
				$properties[] = array(
					'name'  => 'business',
					'value' => $busniness,
				);
			}

			if ( ! empty( $multi_store ) ) {
				$properties[] = array(
					'name'  => 'multi_store',
					'value' => $multi_store,
				);
			}

			if ( ! empty( $is_master ) ) {
				$properties[] = array(
					'name'  => 'master',
					'value' => $is_master,
				);
			}


			if ( ! empty( $city ) ) {
				$properties[] = array(
					'name'  => 'city',
					'value' => $city,
				);
			}

			if ( ! empty( $b2b ) ) {
				$properties[] = array(
					'name'  => 'is_b2b',
					'value' => $b2b,
				);
			}

			if ( ! empty( $gift ) ) {
				$properties[] = array(
					'name'  => 'gift',
					'value' => $gift,
				);
			}

			if ( ! empty( $parent_order_id ) ) {
				$properties[] = array(
					'name'  => 'parent_order',
					'value' => $parent_order_id,
				);
			}


			//$orders = wc_get_order( $order_id );
			$deal_id = get_post_meta( $order_id, 'hubwoo_ecomm_deal_id', true );
			
			if ( ! empty( $properties ) ) {
				$properties = array( 'properties' => $properties );
				self::hubwoo_update_deals( $properties, $deal_id );
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

		//code to get the preferred city of the user from user meta in contact propertiess
		if( ! empty( $user_id ) ) {
			$pref_city = get_user_meta( $user_id, 'pref_city', true );
			$pref_city = HubwooGuestOrdersManager::hubwoo_format_array( $pref_city );
		}

		if( ! empty($user_id) ){
			$pref_language = get_user_meta( $user_id, 'cp__notification_language', true );
			if( ! $pref_language ) {
				$pref_language = 'it';
			}
		}
		
		//code to get birth date of the user from user meta
		if( ! empty( $user_id ) ) {
			$birth_date = get_user_meta( $user_id, 'birth_date', true );
			$birth_date = str_replace( '/', '-', $birth_date );
			$birth_date = strtotime( $birth_date );	
			$birth_date = HubwooObjectProperties::hubwoo_set_utc_midnight( $birth_date );
		}
		
		if ( ! empty( $pref_city ) ) {
			$properties[] = array(
				'property' => 'preferred_cities',
				'value'    => HubwooGuestOrdersManager::hubwoo_format_array( $pref_city ),
			);
		}

		if ( ! empty( $pref_language ) ) {
			$properties[] = array(
				'property' => 'hs_language',
				'value'    => $pref_language ,
			);
		}

		if ( ! empty( $birth_date ) ) {
			$properties[] = array(
				'property' => 'birth_date',
				'value'    => $birth_date ,
			);
		}
		return $properties;
	}

	/**
	 * Function to extend properties of product properties
	 *
	 */
	public function hubwoo_custom_product_properties( $product_id ) {
		//custom code for fetching info_prodotto_multigusto of product
		$multi_flavor = get_post_meta( $product_id, 'info_prodotto_multigusto', true );
		if( $multi_flavor ) {
			$multi_flavor = 'true';
		}
		else{
			$multi_flavor = 'false';
		}

		$multi_flavor_type = get_post_meta( $product_id, 'info_prodotto_multigusto_type', true );
		

		if( ! empty( $multi_flavor ) ) {
			$properties[] = array(
				'name' => 'multi_flavor',
				'value'    =>  $multi_flavor,
			);
		}

		if( ! empty( $multi_flavor_type ) ) {
			$properties[] = array(
				'name' => 'multi_flavor_type',
				'value'    =>  $multi_flavor_type,
			);
		}

		$hubwoo_ecomm_pro_id = get_post_meta( $product_id, 'hubwoo_ecomm_pro_id', true );
		if ( ! empty( $properties ) ) {	
			//$properties = array( 'properties' => $properties );
			self::hubwoo_update_products( $properties, $hubwoo_ecomm_pro_id  );
		}
	}

	/**
	 * Hubwoo update product
	 *
	 * @param [string] $deal_details is the detail of deal.
	 * @param [string] $deal_id is the id of deal.
	 */
	public static function hubwoo_update_products( $properties, $hubwoo_ecomm_pro_id ) {
		if ( ! empty( $properties ) ) {
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
				$response = HubWooConnectionMananager::get_instance()->update_existing_products( $hubwoo_ecomm_pro_id, $properties );
			}
		}
	}

	/**
	 * Function to extend properties of company
	 *
	 */
	public function hubwoo_custom_company_properties(){

		$cmp_properties = array();

		$args = array( 
			'post_type' => 'negozi',
			'post_status' => array( 'publish' ),
			'posts_per_page' => 5,
			'meta_query' => array(
				array(
					'key' => 'hubwoo_created_companies',
					'compare' => 'NOT EXISTS',
				),
			),
										
		);

		$posts = get_posts( $args );

		if( ! empty( $posts ) ) {
			foreach ($posts as $post) {
				$shop_id = $post->ID;
				$shop_name = $post->post_title;
				$shop_address = get_post_meta( $shop_id , 'indirizzo_via', true );
				$shop_zip_code = get_post_meta( $shop_id , 'indirizzo_cap', true );
				$shop_zone = get_post_meta( $shop_id , 'indirizzo_zona', true );
				$is_ZTL = get_post_meta( $shop_id , 'indirizzo_is_ztl_pickup', true );
				if( $is_ZTL ){
					$is_ZTL = 'true';
				} else{
					$is_ZTL = 'false';
				}

				$secondary_shop_address = get_post_meta( $shop_id , 'indirizzo_alternativo_via', true );
				$secondary_shop_zip_code = get_post_meta( $shop_id , 'indirizzo_alternativo_cap', true );
				$secondary_zone = get_post_meta( $shop_id , 'indirizzo_zona', true );
				$secondary_is_ZTL = get_post_meta( $shop_id , 'indirizzo_alternativo_is_ztl_pickup', true );
				if( $secondary_is_ZTL ){
					$secondary_is_ZTL = 'true';
				} else{
					$secondary_is_ZTL = 'false';
				}

				$shop = get_post_meta( $shop_id , 'dati_attivita_cp_city', true );
				$term_data = get_term( $shop );
				$shop_city = $term_data->name;

				$terms = get_terms( 
					array(
					    'taxonomy' => 'partner',
					    'hide_empty' => false,
					  	'object_ids' => $shop_id,
					) 
				);

				$term_id = $terms[0]->term_id;
				$term_title = $terms[0]->name;
				$partner_term_id_and_name = $term_id . ',' . $term_title;
				$partner_business_name = get_term_meta( $term_id, 'amministrazione_ragione_sociale', true );
				//$partner_business_name = $partner_business[0];
				$partner_address = get_term_meta( $term_id, 'amministrazione_cp_address', true );
				$partner_billing_firstname = get_term_meta( $term_id, 'contatto_commerciale_nome', true );
				$partner_billing_lastname = get_term_meta( $term_id, 'contatto_commerciale_cognome', true );
				$partner_billing_phone = get_term_meta( $term_id, 'contatto_commerciale_telefono', true );
				$partner_email = get_term_meta( $term_id, 'contatto_commerciale_email', true );
				//$amministrazione_ragione_sociale = $term_meta['amministrazione_ragione_sociale'][0];

				if( ! empty( $partner_term_id_and_name ) ) {
					$properties[] = array(
						'name' => 'partner',
						'value' =>  $partner_term_id_and_name,
					);
				}

				if( ! empty( $partner_business_name ) ) {
					$properties[] = array(
						'name' => 'partner_business_name',
						'value' =>  $partner_business_name,
					);
				}


				if( ! empty( $partner_address ) ) {
					$properties[] = array(
						'name' => 'partner_address',
						'value' =>  $partner_address,
					);
				}

				if( ! empty( $partner_billing_firstname ) ) {
					$properties[] = array(
						'name' => 'partner_billing_first_name',
						'value' =>  $partner_billing_firstname,
					);
				}

				if( ! empty( $partner_billing_lastname ) ) {
					$properties[] = array(
						'name' => 'partner_billing_last_name',
						'value' =>  $partner_billing_lastname,
					);
				}

				if( ! empty( $partner_billing_phone ) ) {
					$properties[] = array(
						'name' => 'partner_billing_phone_number',
						'value' =>  $partner_billing_phone,
					);
				}

				if( ! empty( $partner_email ) ) {
					$properties[] = array(
						'name' => 'partner_email',
						'value' =>  $partner_email,
					);
				}

				if( ! empty( $shop_city ) ) {
					$properties[] = array(
						'name' => 'City',
						'value' =>  $shop_city,
					);
				}

				$properties[] = array(
					'name' => 'shop',
					'value' => 'true',
				);

				if( ! empty( $shop_id ) ) {
					$properties[] = array(
						'name' => 'shop_id',
						'value' =>  $shop_id,
					);
				}

				if( ! empty( $shop_name ) ) {
					$properties[] = array(
						'name' => 'name',
						'value' =>  $shop_name,
					);
				}

				if( ! empty( $shop_address ) ) {
					$properties[] = array(
						'name' => 'address',
						'value'    =>  $shop_address,
					);
				}

				if( ! empty( $shop_zip_code ) ) {
					$properties[] = array(
						'name' => 'zip',
						'value'    =>  $shop_zip_code,
					);
				}

				if( ! empty( $shop_zone ) ) {
					$properties[] = array(
						'name' => 'zone',
						'value'    =>  $shop_zone,
					);
				}

				if( ! empty( $is_ZTL ) ) {
					$properties[] = array(
						'name' => 'ztl',
						'value'    =>  $is_ZTL,
					);
				}

				if( ! empty( $secondary_shop_address ) ) {
					$properties[] = array(
						'name' => 'secondary_address',
						'value'    =>  $secondary_shop_address,
					);
				}

				if( ! empty( $secondary_shop_zip_code ) ) {
					$properties[] = array(
						'name' => 'secondary_zip',
						'value'    =>  $secondary_shop_zip_code,
					);
				}

				if( ! empty( $secondary_zone ) ) {
					$properties[] = array(
						'name' => 'secondary_zone',
						'value'    =>  $secondary_zone,
					);
				}

				if( ! empty( $secondary_is_ZTL ) ) {
					$properties[] = array(
						'name' => 'secondary_ztl',
						'value'    =>  $secondary_is_ZTL,
					);
				}

				if ( ! empty( $properties ) ) {	
					$properties = array( 'properties' => $properties );

					$response = HubWooConnectionMananager::get_instance()->create_company_data( $properties );

					if( $response['status_code'] == 200 ) {
						
						$response = json_decode($response['body'], true);
						$company_id = $response['companyId'];

						if( ! empty( $company_id) ) {
							update_post_meta( $shop_id, 'hubwoo_created_companies', $company_id );
						}
		 			}
		 			//return $company_id;
				}
				
			}
		}	
	}

	/**
	 * Function to associate deal and company 
	 *
	 */
	public static function hubwoo_associate_company ( $order_id ) {

		$deal_id = get_post_meta( $order_id, 'hubwoo_ecomm_deal_id', true );
		$shop_id = get_post_meta( $order_id, 'shop_id', true );
		$post_title = get_post_meta( $shop_id, 'hubwoo_created_companies' );
		$company_id = $post_title[0];
		if( ! empty( $company_id ) ) {
			$response = HubWooConnectionMananager::get_instance()->create_company_associations( $deal_id, $company_id );
		}
	}
// end of file	
}
