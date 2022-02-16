<?php

if (!defined('ABSPATH')){
    exit;
}

if ( ! class_exists( 'TH_Variation_Swatches_Notice' ) ){

class TH_Variation_Swatches_Notice{

    function __construct(){

        add_action( 'admin_enqueue_scripts', array($this,'th_variation_swatches_admin_enqueue_style') );
        add_action( 'admin_notices', array($this,'th_variation_swatches_admin_notice' ));

    }

    function th_variation_swatches_admin_enqueue_style(){

         wp_enqueue_style( 'th-variation-swatches-notice-style', TH_VARIATION_SWATCHES_PLUGIN_URI.'notice/css/th-notice.css', array(), '1.0.0' );

    } 

    function th_variation_swatches_admin_notice() { ?>

    <div class="th-variation-swatches-notice notice notice-success is-dismissible">
        <div class="th-variation-swatches-notice-wrap">
            <div class="th-variation-swatches-notice-image"><img src="<?php echo esc_url( TH_VARIATION_SWATCHES_PLUGIN_URI.'notice/img/swatches-pro.png');?>" alt="<?php _e('TH Variation Swatches Pro','th-variation-swatches'); ?>"></div>
            <div class="th-variation-swatches-notice-content-wrap">
                <div class="th-variation-swatches-notice-content">
                <p class="th-variation-swatches-heading"><?php _e('Use woo swatches premium and display product attributes in an attractive and professional way.','th-variation-swatches'); ?></p>
                <p><?php _e('Premium version of swatches allow you to display styled attributes at the product page. This will definitely improve user experience and increase your sales.','th-variation-swatches'); ?></p>
                </div>
                <a target="_blank" href="<?php echo esc_url('https://themehunk.com/th-variation-swatches/');?>" class="upgradetopro-btn"><?php _e('Upgrade To Pro','th-variation-swatches');?> </a>
            </div>
        </div>
    </div>

    <?php }

    
}

$obj = New TH_Variation_Swatches_Notice();

}