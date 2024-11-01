<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<style>
	.motpr355_woo_sale_product_date_info_div {
		margin: 10px 0;
	}
	.motpr355_woo_sale_product_date_info_span {
		background: <?php echo esc_html( $this->settings->{$this->settings_enum->background_color} ) ?>;
		color: <?php echo esc_html( $this->settings->{$this->settings_enum->color} ) ?>;
		font-weight: <?php echo esc_html( $this->settings->{$this->settings_enum->font_weight} ) ?>;
		text-transform: <?php echo esc_html( $this->settings->{$this->settings_enum->text_transform} ) ?>;
        text-align: center;
		border-radius: 25px;
		padding: 4px 10px;
		display: inline-block;
	}
</style>
