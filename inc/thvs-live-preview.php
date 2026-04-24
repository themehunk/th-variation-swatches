<?php if ( ! defined( 'ABSPATH' ) ) exit;?>
<!-- preview wrapper -->
			<div class="setting-preview-wrap style-wrapper" data-tab="thvs-style">
				    <div class="page-wrapper">
              <span class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye text-blue-500" aria-hidden="true"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg><?php esc_html_e('Live Preview','th-variation-swatches-pro'); ?></span>
        <div class="product-variations-preview">

          <!-- Color -->
          <div class="variation-group">
            <label>Color</label>
            <div class="variation-options colors">
              <span class="color-option" style="background:#000fef;" data-value="blue" data-th-border="attr_brdr_color-field"></span>
              <span class="color-option" style="background:#59d600;" data-value="green" data-th-border="attr_brdr_color-field"></span>
              <span class="color-option" style="background:#ff5d00;" data-value="orange" data-th-border="attr_brdr_color-field"></span>
              <span class="color-option" style="background:#eded00;" data-value="yellow" data-th-bg-tooltip="tooltip_background_color-field" data-th-color-tooltip="tooltip_text_color-field" data-th-border="attr_brdr_color-field"></span>
            </div>
          </div>

          <!-- Size -->
          <div class="variation-group">
            <label>Size</label>
            <div class="variation-options sizes">
              <span class="size-option" data-value="l" data-th-border="attr_brdr_color-field" data-th-bg="attr_bg_btn_color-field" data-th-color="attr_text_color-field">L</span>
              <span class="size-option" data-value="m" data-th-border="attr_brdr_color-field" data-th-bg="attr_bg_btn_color-field" data-th-color="attr_text_color-field">M</span>
              <span class="size-option" data-value="s" data-th-border="attr_brdr_color-field" data-th-bg="attr_bg_btn_color-field" data-th-color="attr_text_color-field">S</span>
              <span class="size-option" data-value="xl" data-th-border="attr_brdr_color-field" data-th-bg="attr_bg_btn_color-field" data-th-color="attr_text_color-field" data-th-bg-hover="attr_bg_btn_hvr_color-field" data-th-color-hover="attr_text_hvr_color-field" data-th-border-hover="attr_brdr_hvr_color-field">XL</span>
            </div>
          </div>

        </div>
        
        <!-- Empty content area -->
        <div class="content-area"></div>
    </div>
				
			   </div>
<!-- preview wrapper -->  