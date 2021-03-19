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


if ( $_SERVER['REMOTE_ADDR'] == '223.236.165.235' ) {
// $terms = get_terms( array(
//     'taxonomy' => 'partner',
//     'hide_empty' => false,
// ) );
// echo '<pre>';
// print_r($terms);
// foreach ($terms as $term) {
// 	$term_id = $term->term_id;
// 	$term_title = $term->name;
// // echo '<pre>';
// // 	print_r($term_id);
// // 	echo ',';
// // 	print_r($term_title);
// 	$partner_term_id_and_name = $term_id . ',' . $term_title;
// 	echo '<pre>';
// 	print_r($partner_term_id_and_name);
	
// }	
// // $cmp_properties = array();

		$args = array( 
			'post_type' => 'negozi',
			'post_status' => array( 'publish' ),
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'hubwoo_created_companies',
					'compare' => 'NOT EXISTS',
				),
			),
										
		);

		$posts = get_posts( $args );
		echo '<pre>';
// 		print_r($posts);
// // 		if( ! empty( $posts ) ) {
// 			foreach ($posts as $post) {
// 				$shop_id = $post->ID;
// 				echo '<pre>';
// 				print_r($shop_id);
// 	delete_post_meta( $shop_id, 'hubwoo_created_companies' );
// 		//}
// 	}		


// $orders = wc_get_order( 24344 );
// echo '<pre>';
// print_r($orders);















































































// $shop_id = 857;
// $jk = get_post_meta( $shop_id );
// echo '<pre>';
// print_r($jk);
// $terms = get_terms( 
// 		array(
// 		    'taxonomy' => 'partner',
// 		    'hide_empty' => false,
// 		  	'object_ids' => $shop_id,
// 		) 
// 	);

// echo '<pre>';
// print_r($terms);

// foreach ($terms as $term) {
// 	$term_id = $term->term_id;

// // 	echo '<pre>';
// // 	print_r($terms);
// }
// echo '<pre>';
// print_r($term_id);


// $partner_business_name = get_term_meta( $term_id );



// echo '<pre>';
// print_r($partner_business_name);


//information_order_list_partner

//base_shop_id





}