<?php
/**
 * Dashoard of all of the plugin features.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    hubspot-for-woocommerce
 * @subpackage hubspot-for-woocommerce/admin/templates/
 */

// check if the license is entered and have valid license.

$hubspot_url  = Hubwoo::hubwoo_get_auth_url();
$display_data = Hubwoo::hubwoo_setup_overview();

if ( isset( $_GET['task'] ) && 'install-plugin' == $_GET['task'] ) {
	Hubwoo::hubwoo_setup_overview( true );
}

?>
<div class="hubwoo-db-wrap">
	<div class="hubwoo-db">
		<div class="hubwoo-db__row">
			<div class="hubwoo-db__column">
				<div class="hubwoo-db__box-full">
					<div class="hubwoo-db__box-title">
						<h4><?php esc_html_e( 'Connected Hubspot Account', 'hubspot-for-woocommerce' ); ?></h4>
						<p>
							<?php echo esc_textarea( get_option( 'hubwoo_pro_hubspot_id', '' ) ); ?> 
							<a id ="hubwoo-re-auth" href="<?php echo esc_url( $hubspot_url ); ?>" class="hubwoo-discon">
								<?php esc_html_e( 'Re-Authorize your Account', 'hubspot-for-woocommerce' ); ?>
							</a>
						</p>
					</div>
					<div class="hubwoo-db__box-full-content">
						<a id="hubwoo_disconnect_account" href="javascript:void(0);" class="hubwoo-btn--dashboard hubwoo-discon hubwoo-btn--primary">
							<?php esc_html_e( 'Disconnect this Account', 'hubspot-for-woocommerce' ); ?>           
						</a>			
					</div>
				</div>
			</div>
		</div>
		<div class="hubwoo-db__row hubwoo-db__row--info">
			<div class="hubwoo-db__box hubwoo-db__box--info">
				<div class="hubwoo-db__box--infoCon">
					<div class="hubwoo-db__box-title">
						<?php
						esc_html_e( 'Sync WooCommerce orders with HubSpot eCommerce pipeline', 'hubspot-for-woocommerce' );
						?>
																		
					</div>
					<div class="hubwoo-db__box-row-content">
						<p>
							<?php
							esc_html_e( 'Automatically create deals in your HubSpot sales pipeline when new WooCommerce orders are created.', 'hubspot-for-woocommerce' );
							?>

						</p>
					</div>
					<div class="mwb-heb-wlcm__btn-wrap">
						<a href="<?php echo esc_attr( admin_url( 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-deals' ) ); ?>" class="hubwoo-btn--primary hubwoo-btn--dashboard"><?php esc_html_e( 'Deals Settings', 'hubspot-for-woocommerce' ); ?></a>
					</div>
				</div>
			</div>
			<div class="hubwoo-db__box hubwoo-db__box--info">
				<div class="hubwoo-db__box--infoCon">
					<div class="hubwoo-db__box-title">
						<?php esc_html_e( 'Set up abandoned cart capture', 'hubspot-for-woocommerce' ); ?> 
					</div>
					<div class="hubwoo-db__box-row-content">
						<p>
							<?php
							esc_html_e( 'Automatically track people that have added products to their cart and did not check out.', 'hubspot-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="mwb-heb-wlcm__btn-wrap">
						<a href="<?php echo esc_attr( admin_url( 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-abncart' ) ); ?>" class="hubwoo-btn--primary hubwoo-btn--dashboard"><?php esc_html_e( 'Abandoned Cart Settings', 'hubspot-for-woocommerce' ); ?></a>
					</div>
				</div>
			</div>
			<div class="hubwoo-db__box hubwoo-db__box--info">
				<div class="hubwoo-db__box--infoCon">
					<div class="hubwoo-db__box-title">
						<?php
						esc_html_e( 'Automate your sales, marketing, and support', 'hubspot-for-woocommerce' );
						?>

					</div>
					<div class="hubwoo-db__box-row-content">
						<p>
							<?php
							esc_html_e( 'Convert more leads into customers, drive more sales, and scale your support.', 'hubspot-for-woocommerce' );
							?>
						</p>
						<p>
							<?php esc_html_e( 'This requires a ', 'hubspot-for-woocommerce' ); ?>
							<a class="redirect-link" href="http://www.hubspot.com/pricing" target="_blank">
								<?php
								esc_html_e( 'HubSpot Professional or Enterprise plan.', 'hubspot-for-woocommerce' );
								?>

							</a>					
						</p>
					</div>
					<div class="mwb-heb-wlcm__btn-wrap">
						<a href="<?php echo esc_attr( admin_url( 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-automation' ) ); ?>" class="hubwoo-btn--primary hubwoo-btn--dashboard"><?php esc_html_e( 'Automation Settings', 'hubspot-for-woocommerce' ); ?></a>
					</div>
				</div>
			</div>
			<div class="hubwoo-db__box hubwoo-db__box--info">
				<div class="hubwoo-db__box--infoCon">
					<div class="hubwoo-db__box-title">
						<?php
						esc_html_e( 'Manage basic & advanced settings', 'hubspot-for-woocommerce' );
						?>

					</div>
					<div class="hubwoo-db__box-row-content">
						<p>
							<?php
							esc_html_e( 'Modify any of your basic & advanced settings to make sure the HubSpot for WooCommerce integration is set up for your business needs.', 'hubspot-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="mwb-heb-wlcm__btn-wrap">
						<a href="<?php echo esc_attr( admin_url( 'admin.php?page=hubwoo&hubwoo_tab=hubwoo-general-settings' ) ); ?>" class="hubwoo-btn--primary hubwoo-btn--dashboard"><?php esc_html_e( 'View Basic & Advanced Settings', 'hubspot-for-woocommerce' ); ?></a>
					</div>
				</div>
			</div>
			<div class="hubwoo-db__box hubwoo-db__box--info">
				<div class="hubwoo-db__box--infoCon">
					<div class="hubwoo-db__box-title">
						<?php
						esc_html_e( 'Download HubSpot’s WordPress plugin', 'hubspot-for-woocommerce' );
						?>

					</div>
					<div class="hubwoo-db__box-row-content">
						<p>
							<?php
							esc_html_e( 'Do even more for your online store with HubSpot’s official WordPress plugin. Download the plugin to easily manage your HubSpot account without navigating away from the WordPress backend.', 'hubspot-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="mwb-heb-wlcm__btn-wrap">
						<a href="<?php echo esc_attr( $display_data['plugin-install']['href'] ); ?>" class="hubwoo-btn--primary hubwoo-btn--dashboard"><?php echo esc_textarea( $display_data['plugin-install']['label'], 'hubspot-for-woocommerce' ); ?></a>
					</div>
				</div>
			</div>
			<div class="hubwoo-db__box hubwoo-db__box--info">
				<div class="hubwoo-db__box--infoCon">
					<div class="hubwoo-db__box-title">
						<?php
						esc_html_e( 'User guide documentation', 'hubspot-for-woocommerce' );
						?>

					</div>
					<div class="hubwoo-db__box-row-content">
						<p>
							<?php
							esc_html_e( 'To get the most out of the WooCommerce HubSpot integration, check out the user guide documentation for more details.', 'hubspot-for-woocommerce' );
							?>
						</p>
					</div>
					<div class="mwb-heb-wlcm__btn-wrap">
						<a target="_blank" href="https://docs.makewebbetter.com/hubspot-integration-for-woocommerce" class="hubwoo-btn--primary hubwoo-btn--dashboard"><?php esc_html_e( 'View User Guides', 'hubspot-for-woocommerce' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<div class="hubwoo-db__row">
			<div class="hubwoo-db__column">
				<div class="hubwoo-db__box-full box-services">
					<div class="hubwoo-db__box-title">
						<h4><?php esc_html_e( 'Need Support or advanced consulting services?', 'hubspot-for-woocommerce' ); ?></h4>
					</div>
					<div class="hubwoo-db__box-full-content">
						<a target="_blank" href="https://tawk.to/chat/5e2bf07cdaaca76c6fcfca43/default" class="hubwoo-btn--primary hubwoo-btn--dashboard hubwoo-btn--dashboard-chat"><?php esc_html_e( 'Chat with Support', 'hubspot-for-woocommerce' ); ?></a>
						<a target="_blank" href="https://makewebbetter.com/hubspot-woocommerce-onboarding" class="hubwoo-btn--primary hubwoo-btn--dashboard"><?php esc_html_e( 'View Consulting packages', 'hubspot-for-woocommerce' ); ?></a>  
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
