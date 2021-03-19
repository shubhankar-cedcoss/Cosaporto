<?php
/**
 * Abandoned Cart settings template.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-for-woocommerce
 * @subpackage hubspot-for-woocommerce/admin/templates/
 */
?>
<div class="hubwoo-form-wizard-wrapper">
	<div class="hubwoo-form-wizard-content-wrapper">
		<div class="hubwoo-form-wizard-content show" data-tab-content="abandon-cart-setup">
			<div class="hubwoo-settings-container">
				<div class="hubwoo-general-settings">
					<div class="hubwoo-group-wrap__abandon_cart_setup">
						<form action="" method="post" class="hubwoo-abncart-setup-form hubwoo-abncart-setup-form--d" >
							<?php woocommerce_admin_fields( Hubwoo_Admin::hubwoo_abncart_general_settings() ); ?>									
						</form>
					</div>
				</div>
			</div>		
		</div>
	</div>
</div>

<?php

$data = get_option( 'hubwoo_created_companies', true );
echo $data;
//order ID can directly get from Woocommerce_thankyou hook

// code to sync payment method
// $orders = wc_get_order( $order_id );
// $payment = $orders->get_payment_method_title();



// code to sync total discount
// $orders = wc_get_order( $order_id );
// $discount = $orders->get_discount_total();


// code to sync shipping total in order
// $orders = wc_get_order( $order_id );
// $shipping = $orders->get_shipping_total();


//code for the custom delivery number on billing address
// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta) {
// 	$meta = $meta->get_data();
// 	if ( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if ( $data == '_billing_tel_consegna' ) {
// 			$billing_delivery_number = $meta['value'];
// 		}
// 	}
// }


//code for the delivery number on shiiping address
// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta) {
// 	$meta = $meta->get_data();
// 	if ( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if ( $data == '_shipping_tel_consegna' ) {
// 			$shipping_delivery_number = $meta['value'];
// 		}
// 	}
// }


//code for getting billing phone number
// $orders = wc_get_order( $order_id );
// $array_data = $orders->get_data();
// $billing_detail = $array_data['billing'];
// $billing_phone =  $billing_detail['phone'];


//code to get out of town option from order 

// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta ) {
// 	$meta = $meta->get_data();
// 	if( !empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if( $data == 'is_oot' ) {
// 			$out_of_town = $meta['value'];
// 			if( $out_of_town ) {
// 				$out_of_town = 'yes';
// 			}
// 			else{
// 				$out_of_town = 'no';
// 			}
// 		}
// 	}
// }


//code to get order delivery date

//also convert this date into time stamp
// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta ) {
// 	$meta = $meta->get_data();
// 	if( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if( $data == 'data_consegna' ) {
// 			$delivery date = $meta['value'];
// 		}
// 	}
// }


//code for start delivery time
// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta ) {
// 	$meta = $meta->get_data();
// 	if( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if( $data == 'inizio_ora_consegna' ) {
// 			$start_delivery_time = $meta['value'];
// 		}
// 	}
// }


//code for end delivery time
// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta ) {
// 	$meta = $meta->get_data();
// 	if( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if( $data == 'fine_ora_consegna' ) {
// 			$end_delivery_time = $meta['value'];
// 		}
// 	}
// }


//code for take away status
// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta ) {
// 	$meta = $meta->get_data();
// 	if( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if( $data == 'is_client_pickup' ) {
// 			$take_away = $meta['value'];
// 			if( $take_away ){
// 				$take_away = 'yes';
// 			}
// 			else{
// 				$take_away = 'no';
// 			}
// 		}
// 	}
// }

//code for getting busniess status
// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta ) {
// 	$meta = $meta->get_data();
// 	if( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if( $data == 'is_business' ) {
// 			$busniness = $meta['value'];
// 			if( $busniness ){
// 				$busniness = 'yes';
// 			}
// 			else{
// 				$busniness = 'no';
// 			}
// 		}
// 	}
// }

//code to get multi-order or multi-store order status
// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta ) {
// 	$meta = $meta->get_data();
// 	if( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if( $data == 'multinegozio_is_multiorder' ) {
// 			$multi_store = $meta['value'];
// 			if( $multi_store ){
// 				$multi_store = 'yes';
// 			}
// 			else{
// 				$multi_store = 'no';
// 			}
// 		}
// 	}
// }


//code for getting FCP status(is_b2b  status)
// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta ) {
// 	$meta = $meta->get_data();
// 	if( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if( $data == 'is_b2b' ) {
// 			$b2b = $meta['value'];
// 			if( $b2b ){
// 				$b2b = 'yes';
// 			}
// 			else{
// 				$b2b = 'no';
// 			}
// 		}
// 	}
// }




// $order_id = 24211;
// $orders = wc_get_order( $order_id );
// // echo '<pre>';
// // print_r($orders);
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta ) {
// 	$meta = $meta->get_data();
// 	if( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		if( $data == 'is_b2b' ) {
// 			$b2b = $meta['value'];
// 			if( $b2b ){
// 				$b2b = 'yes';
// 			}
// 			else{
// 				$b2b = 'no';
// 			}
// 		}
// 	}
// }



//code for getting parent order status
// $order_id = 24211;
// $orders = wc_get_order( $order_id );
// $meta = $orders->get_meta_data();
// foreach ( $meta as $meta ) {
// 	$meta = $meta->get_data();
//  	if( ! empty( $meta ) && is_array( $meta ) ) {
// 		$data = $meta['key'];
// 		// if( ! empty( $data ) && $data == 'gift_message' ) {
// 		// 	$gift_message = $meta['value'];
// 		// }
// 		if( ! empty( $data ) && $data == 'ship_to_friend' ) {
// 			$ship_to_friend = $meta['value'];
// 			if( empty( $ship_to_friend ) ){
// 				$ship_to_friend = 'true';
// 			}
// 			if( $ship_to_friend ) {
// 			 	$gift = 'true';
// 			}
// 			else{
// 				$gift = 'false';
// 			}
// 		}
		
//  	}
// }



//contact propertiess

//code to get birth date of the user from user meta
//$user_meta = get_user_meta( $user_id, 'birth_date', true );


//code to get preferred city of the user from user meta
// $user_id = 894;
// if( ! empty( $user_id ) ) {
// 	$pref_city = get_user_meta( $user_id, 'pref_city', true );
// 	$pref_city = HubwooGuestOrdersManager::hubwoo_format_array( $pref_city );
// }



// custom properties of company

// custom code for fetching term name
// $term_args = array( 'taxonomy' => 'partner' );
// $terms = get_terms( $term_args );
// foreach ($terms as $term ) {
// 	if( ! empty( $term ) ) {
// 		$term_array[] = $term->name;
// 	}	
// }


// // custom code for fetching term id
// $term_args = array( 'taxonomy' => 'partner' );
// $terms = get_terms( $term_args );
// foreach ($terms as $term ) {
// 	if( ! empty( $term ) ) {
// 		$term_arr[] = $term->term_id;
// 	}	
// }


// custom code for fetching amministrazione_ragione_sociale of comapny
// $term_args = array( 'taxonomy' => 'partner' );
// $terms = get_terms( $term_args );
// foreach ($terms as $term ) {
// 	if( ! empty( $term ) ) {
// 		$term_id = $term->term_id;
// 		$term_meta = get_term_meta( $term_id );
// 		$partner_business_name = get_term_meta( $term_id, 'amministrazione_ragione_sociale', true );
// 		$partner_address = get_term_meta( $term_id, 'amministrazione_cp_address', true );
// 		$partner_billing_firstname = get_term_meta( $term_id, 'contatto_commerciale_nome', true );
// 		$partner_billing_lastname = get_term_meta( $term_id, 'contatto_commerciale_cognome', true );
// 		$partner_billing_phone = get_term_meta( $term_id, 'contatto_commerciale_telefono', true );
// 		$partner_billing_email = get_term_meta( $term_id, 'contatto_commerciale_email', true );
		
// 	}	
// }
// if( ! empty( $term_id ) ){
// 	echo $term_id;
// 	echo '<br>';
// }
// if( ! empty( $partner_business_name ) ){
// 	echo $partner_business_name;
// 	echo '<br>';
// }
// if( ! empty( $partner_address ) ){
// 	echo $partner_address;
// 	echo '<br>';
// }
// if( ! empty( $partner_billing_firstname ) ){
// 	echo $partner_billing_firstname;
// 	echo '<br>';
// }
// if( ! empty( $partner_billing_lastname ) ){
// 	echo $partner_billing_lastname;
// 	echo '<br>';
// }
// if( ! empty( $partner_billing_phone ) ){
// 	echo $partner_billing_phone;
// 	echo '<br>';
// }
// if( ! empty( $partner_billing_email ) ){
// 	echo $partner_billing_email;
// 	echo '<br>';
// }

//echo $partner_business_name;
// echo '<br>';
// echo $partner_address;
// echo '<br>'
// echo $partner_billing_firstname;
// echo '<br>';
// echo $partner_billing_lastname;
// echo '<br>';
// echo $partner_billing_phone;
// echo '<br>';
// echo $partner_billing_email;

// echo '<pre>';
// print_r($term_meta);



//custom code for fetching amministrazione_cp_address of company 
// $term_args = array( 'taxonomy' => 'partner' );
// $terms = get_terms( $term_args );
// foreach ($terms as $term ) {
// 	if( ! empty( $term ) ) {
// 		$term_id = $term->term_id;
// 		//$term_meta[] = get_term_meta( $term_id );
// 		$term_meta[] = get_term_meta( $term_id, 'amministrazione_cp_address', true );
		
// 	}	
// }


// custom code for fetching contatto_commerciale_nome of company
// $term_args = array( 'taxonomy' => 'partner' );
// $terms = get_terms( $term_args );
// foreach ($terms as $term ) {
// 	if( ! empty( $term ) ) {
// 		$term_id = $term->term_id;
// 		//$term_meta[] = get_term_meta( $term_id );
// 		$term_meta[] = get_term_meta( $term_id, 'contatto_commerciale_nome', true );
		
// 	}	
// }


// custom code for fetching contatto_commerciale_cognome of company
// $term_args = array( 'taxonomy' => 'partner' );
// $terms = get_terms( $term_args );
// foreach ($terms as $term ) {
// 	if( ! empty( $term ) ) {
// 		$term_id = $term->term_id;
// 		//$term_meta[] = get_term_meta( $term_id );
// 		$term_meta[] = get_term_meta( $term_id, 'contatto_commerciale_cognome', true );
		
// 	}	
// }

//custom code for fetching contatto_commerciale_telefono of company
// $term_args = array( 'taxonomy' => 'partner' );
// $terms = get_terms( $term_args );
// foreach ($terms as $term ) {
// 	if( ! empty( $term ) ) {
// 		$term_id = $term->term_id;
// 		//$term_meta[] = get_term_meta( $term_id );
// 		$term_meta[] = get_term_meta( $term_id, 'contatto_commerciale_telefono', true );
		
// 	}	
// }


//custom code for fetching contatto_commerciale_email of company
// $term_args = array( 'taxonomy' => 'partner' );
// $terms = get_terms( $term_args );
// foreach ($terms as $term ) {
// 	if( ! empty( $term ) ) {
// 		$term_id = $term->term_id;
// 		//$term_meta[] = get_term_meta( $term_id );
// 		$term_meta[] = get_term_meta( $term_id, 'contatto_commerciale_email', true );
		
// 	}	
// }


//properties of custom post types

//custom properties for fetching post_id of CPT
// $args = array( 
// 	'post_type' => 'negozi',
// 	'post_status' => array( 'publish' ),
// 	'posts_per_page' => -1, 
// );
// $posts = get_posts( $args );
// foreach ($posts as $post) {
// 	$shop_id = $post->ID;
// 	$shop_name = $post->post_title;
// 	$shop_address = get_post_meta( $shop_id , 'indirizzo_via', true );
// 	$shop_zip_code = get_post_meta( $shop_id , 'indirizzo_cap', true );
// 	$shop_zone = get_post_meta( $shop_id , 'indirizzo_zona', true );
// 	$is_ZTL = get_post_meta( $shop_id , 'indirizzo_is_ztl_pickup', true );
// 	if( $is_ZTL ){
// 		$is_ZTL = 'true';
// 	} else{
// 		$is_ZTL = 'false';
// 	}

// 	$secondary_shop_address = get_post_meta( $shop_id , 'indirizzo_alternativo_via', true );
// 	$secondary_shop_zip_code = get_post_meta( $shop_id , 'indirizzo_alternativo_cap', true );
// 	//$secondary_zone = get_post_meta( $shop_id , 'indirizzo_zona', true );
// 	$secondary_is_ZTL = get_post_meta( $shop_id , 'indirizzo_alternativo_is_ztl_pickup', true );
// 	if( $secondary_is_ZTL ){
// 		$secondary_is_ZTL = 'true';
// 	} else{
// 		$secondary_is_ZTL = 'false';
// 	}

// }
// echo $shop_id;
// echo '<br>';
// echo $shop_name;
// echo '<br>';
// if( ! empty ( $shop_address ) ) {
// 	echo $shop_address;
// 	echo '<br>';
// }
// if( ! empty ( $shop_zip_code ) ) {
// 	echo $shop_zip_code;
// 	echo '<br>';
// }
// if( ! empty ( $shop_zone ) ) {
// 	echo $shop_zone;
// 	echo '<br>';
// }
// if( ! empty ( $is_ZTL ) ) {
// 	echo $is_ZTL;
// 	echo '<br>';
// }
// if( ! empty ( $secondary_shop_address ) ) {
// 	echo $secondary_shop_address;
// 	echo '<br>';
// }
// if( ! empty ( $secondary_shop_zip_code ) ) {
// 	echo $secondary_shop_zip_code;
// 	echo '<br>';
// }

// if( ! empty ( $secondary_is_ZTL ) ) {
// 	echo $secondary_is_ZTL;
// 	echo '<br>';
// }

//custom code for fetching post_title of CPT
// $args = array( 
// 	'post_type' => 'negozi',
// 	'post_status' => array( 'publish' ),
// 	'posts_per_page' => -1, 
// );
// //$loop = new WP_Query( $args );
// $posts = get_posts( $args );
// // echo '<pre>';
// // print_r($posts);
// foreach ($posts as $post) {
// 	$post_title[] = $post->post_title;
// }



//custom code for fetching indirizzo_via of CPT
// $args = array( 
// 	'post_type' => 'negozi',
// 	'post_status' => array( 'publish' ),
// 	'posts_per_page' => -1, 
// );
// $posts = get_posts( $args );
// // echo '<pre>';
// // print_r($posts);
// foreach ($posts as $post) {
	// 	$post_id = $post->ID;
	// 	//$meta = get_post_meta( 23857 );
	// 	$meta[] = get_post_meta( $post_id , 'indirizzo_via', true );
		// if( ! empty ( $meta ) ) {
		// 	$meta_array[] = $meta;
		// }
// }



//custom code for fetching indirizzo_cap of CPT
// $args = array( 
// 	'post_type' => 'negozi',
// 	'post_status' => array( 'publish' ),
// 	'posts_per_page' => -1, 
// );
// $posts = get_posts( $args );
// // echo '<pre>';
// // print_r($posts);
// foreach ($posts as $post) {
// 	$post_id = $post->ID;
// 	//$meta = get_post_meta( 18538 );
// 	$meta = get_post_meta( $post_id , 'indirizzo_cap', true );
// 	if( ! empty ( $meta ) ) {
// 		$meta_array[] = $meta;
// 	}
// }


//custom code for fetching indirizzo_zona of CPT
// $args = array( 
// 	'post_type' => 'negozi',
// 	'post_status' => array( 'publish' ),
// 	'posts_per_page' => -1, 
// );
// $posts = get_posts( $args );
// // echo '<pre>';
// // print_r($posts);
// foreach ($posts as $post) {
// 	$post_id = $post->ID;
// 	//$meta = get_post_meta( 18538 );
// 	$meta = get_post_meta( $post_id , 'indirizzo_zona', true );
// 	if( ! empty ( $meta ) ) {
// 		$meta_array[] = $meta;
// 	}
// }




//custom code for fetching post_id of product
// $args = array( 
// 	'post_type' => 'product',
// 	'post_status' => array( 'publish' ),
// 	'posts_per_page' => -1, 
// );
// $posts = get_posts( $args );
// // echo '<pre>';
// // print_r($posts);
// foreach ($posts as $post) {
// 	$post_id[] = $post->ID;
// }

// echo '<pre>';
// print_r($post_id)


//custom code for fetching info_prodotto_multigusto of product

// $args = array( 
// 	'post_type' => 'product',
// 	'post_status' => array( 'publish' ),
// 	'posts_per_page' => -1, 
// );
// $posts = get_posts( $args );
// //echo '<pre>';
// //print_r($posts);
// foreach ($posts as $post) {
// 	$post_id = $post->ID;
// 	//$meta = get_post_meta( 23864 );
// 	$meta = get_post_meta( $post_id, 'info_prodotto_multigusto', true );
// }


//custom code for fetching multigusto_type of product
// $args = array( 
// 	'post_type' => 'product',
// 	'post_status' => array( 'publish' ),
// 	'posts_per_page' => -1, 
// );
// $posts = get_posts( $args );

// //echo '<pre>';
// //print_r($posts);
// foreach ($posts as $post) {
// 	$product_id = '24313';
// 	//$meta = get_post_meta( 23864 );
// 	$meta = get_post_meta( $product_id );
// 	if( ! empty ( $meta ) ) {
// 		$meta_array[] = $meta;
// 	}
// }
// echo '<pre>';
// print_r($meta_array);

// $user_id = 4218;

// if( ! empty( $user_id ) ) {
// 	$birth_date = get_user_meta( $user_id, 'birth_date', true );
// 	$birth_date = str_replace( '/', '-', $birth_date );
// 	$birth_date = strtotime( $birth_date );	
// 	$birth_date = HubwooObjectProperties::hubwoo_set_utc_midnight( $birth_date );
// }

// // $birth_date = get_user_meta( $user_id, 'birth_date', true );
// // $birth_date = str_replace( '/', '-', $birth_date );
// // $birth_date = strtotime( $birth_date );	
// // $date = HubwooGuestOrdersManager::hubwoo_set_utc_midnight( $birth_date );

// $date = HubwooGuestOrdersManager::hubwoo_set_utc_midnight( $birth_date );
// echo $date;
