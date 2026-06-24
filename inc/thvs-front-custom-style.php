<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
/***Front Custom Style********/

function thvs_front_custom_style($thvs_frnt_custom_css=''){
if(wc_string_to_bool( th_variation_swatches()->th_variation_swatches_get_option( 'tooltip' ) )):	
$thvs_frnt_custom_css.="[data-thvstooltip]:before {
  width: max-content;
  content: attr(data-thvstooltip);
}
.th-variation-swatches .variable-items-wrapper .variable-item:not(.disabled)[data-thvstooltip]::before {
    margin-bottom: 5px;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);
    padding: 5px 12px;
    border-radius: 5px;
    background-color: #111;
    color: #fff;
    text-align: center;
    font-size: 14px;
    line-height: 1.2;
}
.th-variation-swatches .variable-items-wrapper .variable-item:not(.disabled)[data-thvstooltip]::before,
.th-variation-swatches .variable-items-wrapper .variable-item:not(.disabled)[data-thvstooltip]::after{
    visibility: hidden;
    opacity: 0;
    pointer-events: none;
    box-sizing: inherit;
    position: absolute;
    bottom: 121%;
    left: 50%;
    z-index: 999;
    
}
.th-variation-swatches .variable-items-wrapper .variable-item:not(.disabled)[data-thvstooltip]::after {
    margin-left: -5px;
    width: 0;
    border-top: 5px solid #111;
    border-right: 5px solid transparent;
    border-left: 5px solid transparent;
    content:'';
    font-size: 0;
    line-height: 0;
}
.th-variation-swatches .variable-items-wrapper .variable-item:not(.disabled)[data-thvstooltip]:hover::before,
.th-variation-swatches .variable-items-wrapper .variable-item:not(.disabled)[data-thvstooltip]:hover::after {
    bottom: 120%;
    visibility: visible;
    opacity: 1;
}
";
endif;
$attrwdht = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'width' ));
$attrhgt = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'height' ));
$attrsingle_font_size = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'single_font_size' ));
$thvs_frnt_custom_css.=".variable-item:not(.radio-variable-item){
	height:{$attrwdht}px;width:{$attrhgt}px;
} 
.thvs-attr-behavior-blur .variable-item.disabled .variable-item-contents span:after{
    height:{$attrwdht}px;
    line-height:{$attrwdht}px;
}
.woo-variation-items-wrapper .button-variable-item span,.th-variation-swatches.thvs-style-squared .variable-items-wrapper .variable-item.button-variable-item .variable-item-span {
    font-size:{$attrsingle_font_size}px;
}";

if(wc_string_to_bool( th_variation_swatches()->th_variation_swatches_get_option( 'show_title' ) )==''):
    $thvs_frnt_custom_css.=".thvs-loaded .variations th.label{
        display:none!important;
    }";
endif;

//Tooltip
$tooltip_background_color = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'tooltip_background_color' ));
$tooltip_text_color       = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'tooltip_text_color' ));
$thvs_frnt_custom_css.="[data-thvstooltip]:before,.tooltip-image,.th-variation-swatches .variable-items-wrapper .variable-item.thvs-show-stock-left-info:not(.disabled) .thvs-stock-left-info:after{background:{$tooltip_background_color};color:{$tooltip_text_color };} 
    [data-thvstooltip]:after,.tooltip-image:after{border-top-color:{$tooltip_background_color};}.th-variation-swatches .variable-items-wrapper .variable-item.thvs-show-stock-left-info:not(.disabled):not(.radio-variable-item) .thvs-stock-left-info:before{border-bottom-color:{$tooltip_background_color};}";

//Attribute color
$attr_brdr_color       = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'attr_brdr_color' ));
$attr_text_color       = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'attr_text_color' ));
$attr_bg_btn_color     = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'attr_bg_btn_color' ));
$attr_brdr_size        = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'attr_brdr_size' ));



$thvs_frnt_custom_css.=".th-variation-swatches .variable-items-wrapper .variable-item:not(.radio-variable-item){border:{$attr_brdr_size}px solid {$attr_brdr_color};} .th-variation-swatches .variable-items-wrapper .variable-item:not(.radio-variable-item){background-color:{$attr_bg_btn_color}!important;color:{$attr_text_color};}";

//hover attribute
$attr_brdr_hvr_color          = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'attr_brdr_hvr_color' ));
$attr_text_hvr_color          = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'attr_text_hvr_color' ));
$attr_bg_btn_hvr_color        = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'attr_bg_btn_hvr_color' ));

$thvs_frnt_custom_css.=".th-variation-swatches .variable-items-wrapper .variable-item:hover,.th-variation-swatches .variable-items-wrapper .variable-item.selected{border:{$attr_brdr_size}px solid {$attr_brdr_hvr_color };} .th-variation-swatches .variable-items-wrapper .variable-item.selected{background-color:{$attr_bg_btn_hvr_color}!important;color:{$attr_text_hvr_color};box-shadow:none;}
.th-variation-swatches .variable-items-wrapper .variable-item:hover{border-color:{$attr_bg_btn_hvr_color};} ";
$attr_title_font_size        = esc_html(th_variation_swatches()->th_variation_swatches_get_option( 'attr_title_font_size' ));
$thvs_frnt_custom_css.=".variations .woo-selected-variation-item-name,.variations .label label{
font-size:{$attr_title_font_size}px;
}";


return $thvs_frnt_custom_css;
}