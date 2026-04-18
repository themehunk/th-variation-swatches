<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Th_Variation_Swatches_Settings' ) ):

	class Th_Variation_Swatches_Settings {

    private $setting_name = 'th_variation_swatches';
		private $setting_reset_name = 'reset';
		private $theme_feature_name = 'th-variation-swatches';
		private $slug;
		private $plugin_class;
		private $defaults = array();
		private $reserved_key = '';
		private $reserved_fields = array();
		private $fields = array();
		private $settings_name;
		
             public function __construct() {
             $this->settings_name   = apply_filters( 'thvs_settings_name', $this->setting_name );
             $this->fields          = apply_filters( 'thvs_settings', $this->fields );
		     $this->reserved_key    = sprintf( '%s_reserved', $this->settings_name );
		     $this->reserved_fields = apply_filters( 'thvs_reserved_fields', array() );
		         
             add_action( 'admin_menu', array( $this, 'add_menu' ) );
             add_action( 'init', array( $this, 'set_defaults' ), 8 );
             add_action( 'admin_init', array( $this, 'settings_init' ), 90 );
             add_action( 'admin_enqueue_scripts', array( $this, 'script_enqueue' ) );
			 add_action('wp_ajax_thvs_form_setting', array($this, 'thvs_form_setting'));
          }
            
            public function add_menu() {
						 $page_title = esc_html__( 'Variation Swatches', 'th-variation-swatches' );
						 $menu_title = esc_html__( 'Variation Swatches', 'th-variation-swatches' );
						 add_submenu_page( 'themehunk-plugins', $page_title, $menu_title, 'manage_options', 'th-variation-swatches', array($this, 'settings_form'),15 );
		    }

		public function admin_add_class(){
			  $classes ='';
			  if($this->get_option( 'show_title' ) == 1){
			  	return $classes = 'show-title';
			  }
				
		}

	public function settings_form() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
		  if( ! class_exists( 'WooCommerce' ) ){

				   printf('<h2 class="requirement-notice">%s</h2>',__('Th Variation Swatches requires WooCommerce to work. Make sure that you have installed and activated WooCommerce Plugin.','th-variation-swatches-pro' ) );

             return;

				}
			?>
			<div id="thvs" class="settings-wrap  <?php echo esc_attr($this->admin_add_class());?>">

				<?php $this->options_tabs(); ?>
				 <div class="setting-wrap">

				 		<div class="top-header">
                <h2 class="tabheading"><?php esc_html_e("Basic", 'th-variation-swatches-pro'); ?></h2>
               
             
		      <a href="<?php echo esc_url( 'https://themehunk.com/th-variation-swatches/' ); ?>"
					   title="<?php esc_attr_e( 'Upgrade', 'th-advance-product-search' ); ?>"
					   target="_blank" class="upgrade-button">
						<?php esc_html_e( 'Upgrade', 'th-advance-product-search' ); ?>
					</a>

					<p class="submit thvs-button-wrapper">
						

						<a onclick="return confirm('<?php esc_attr_e( 'Are you sure to reset current settings?', 'th-variation-swatches-pro' ) ?>')" class="reset" href="<?php echo $this->reset_url() ?>"><?php esc_html_e( 'Reset all', 'th-variation-swatches-pro' ) ?>
						</a>

						 <button disabled id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes', 'th-variation-swatches-pro' ) ?>"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save transition-transform group-hover:scale-110" aria-hidden="true"><path d="M15.2 3a2 2 0 0 1 1.4.6l3.8 3.8a2 2 0 0 1 .6 1.4V19a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z"></path><path d="M17 21v-7a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v7"></path><path d="M7 3v4a1 1 0 0 0 1 1h7"></path></svg></span><span class="text"><?php esc_html_e( 'Save All Changes', 'th-variation-swatches-pro' ) ?></span>
						 </button>
					</p> 


					</div>

       <div class="setting-content"> 

				<form method="post" action="" enctype="multipart/form-data" class="thvs-setting-form">
                     <input type="hidden" name="action" value="thvs_form_setting">          
						<div id="settings-tabs" class="thvs-tabs-wrapper">
							<?php foreach ( $this->fields as $tab ):

								if ( ! isset( $tab['active'] ) ) {
									$tab['active'] = false;
								}
								$is_active = ( $this->get_last_active_tab() == $tab['id'] );

								?>

								<div id="<?php echo $tab['id'] ?>"
									 class="settings-tab thvs-setting-tab"
									 style="<?php echo ! $is_active ? 'display: none' : '' ?>">
									 
									<?php foreach ( $tab['sections'] as $section ):

							$this->do_settings_sections( $tab['id'] . $section['id'] );
									endforeach; ?>
								</div>

							<?php endforeach; ?>
						</div>
						<?php
						$this->last_tab_input();
						 // submit_button();
						?>
                 
					</form>

			</div>

					<?php require_once TH_VARIATION_SWATCHES_PLUGIN_PATH . '/inc/thvs-live-preview.php'; ?> 
          <?php require_once TH_VARIATION_SWATCHES_PLUGIN_PATH . '/inc/thvs-reset.php'; ?> 
          <?php require_once TH_VARIATION_SWATCHES_PLUGIN_PATH . '/inc/thvs-help.php'; ?> 

				 </div>
			</div>
			<?php
			
		}

    public function thvs_form_setting(){  

    	      if ( ! current_user_can( 'administrator' ) ) {

		            wp_die( - 1, 403 );
		            
		         } 

              check_ajax_referer( 'thvs_plugin_nonce','_wpnonce');

             if( isset($_POST['th_variation_swatches']) ){

                      $sanitize_data_array = $this->thvs_form_sanitize($_POST['th_variation_swatches']);

                      update_option('th_variation_swatches',$sanitize_data_array);         
	            }

	            die();  

    }
        
    public function thvs_form_sanitize( $input ){
			$new_input = array();
			foreach ( $input as $key => $val ){
				$new_input[ $key ] = ( isset( $input[ $key ] ) ) ? sanitize_text_field( $val ) :'';
	   }
	   return $new_input;

    }

		public function reset_url() {
			return add_query_arg( 
				array( 
					'page' => 'th-variation-swatches', 
					'reset' => 'reset',
					'delete_wpnonce' => wp_create_nonce('delete_nonce') 
				), admin_url( 'admin.php' ) );
		}

		public function settings_url(){
			return add_query_arg( 
				array( 
					'page' => 'th-variation-swatches',
					'_wpnonce' => wp_create_nonce('_nonce'),
					 ), admin_url( 'admin.php' )
				 );
		}
    private function set_default( $key, $type, $value ) {
		$this->defaults[ $key ] = array( 'id' => $key, 'type' => $type, 'value' => $value );
		}

		private function get_default( $key ) {
			return isset( $this->defaults[ $key ] ) ? $this->defaults[ $key ] : null;
		}

		public function get_defaults() {
			return $this->defaults;
		}


    public function is_reset_all() {
			return isset( $_GET['page'] ) && ( $_GET['page'] == 'th-variation-swatches' ) && isset( $_GET[ $this->setting_reset_name ] );
		}  

	public function delete_settings() {

			if ( ! current_user_can( 'administrator' ) ) {

            wp_die( - 1, 403 );

            }

			check_ajax_referer('delete_nonce','delete_wpnonce');

			do_action( sprintf( 'delete_%s_settings', $this->settings_name ), $this );

			return delete_option( $this->settings_name );

		

		}
		public function set_defaults() {
			foreach ( $this->fields as $tab_key => $tab ) {
				$tab = apply_filters( 'thvs_settings_tab', $tab );

				foreach ( $tab['sections'] as $section_key => $section ) {

					$section = apply_filters( 'thvs_settings_section', $section, $tab );

					$section['id'] = ! isset( $section['id'] ) ? $tab['id'] . '-section' : $section['id'];

					$section['fields'] = apply_filters( 'thvs_settings_fields', $section['fields'], $section, $tab );

					foreach ( $section['fields'] as $field ) {
						if ( isset( $field['pro'] ) ) {
							continue;
						}
						$field['default'] = isset( $field['default'] ) ? $field['default'] : null;
						$this->set_default( $field['id'], $field['type'], $field['default'] );
					}
				}
			}
		}
		
		public function sanitize_callback( $options ) {

			foreach ( $this->get_defaults() as $opt ) {
				if ( $opt['type'] === 'checkbox' && ! isset( $options[ $opt['id'] ] ) ){
					$options[ $opt['id'] ] = 0;
				}
			}

			return $options;
		}

		public function settings_init() {

			if ( $this->is_reset_all() ) {
				 $this->delete_settings();
				 wp_redirect($this->settings_url());
			}
              
		  register_setting( $this->settings_name, $this->settings_name, array( $this, 'sanitize_callback' ) );

			foreach ( $this->fields as $tab_key => $tab ) {

				$tab = apply_filters( 'thvs_settings_tab', $tab );

				foreach ( $tab['sections'] as $section_key => $section ) {

					$section = apply_filters( 'thvs_settings_section', $section, $tab );

					$section['id'] = ! isset( $section['id'] ) ? $tab['id'] . '-section-' . $section_key : $section['id'];

					// Adding Settings section id
					$this->fields[ $tab_key ]['sections'][ $section_key ]['id'] = $section['id'];

					add_settings_section( $tab['id'] . $section['id'], $section['title'], function () use ( $section ) {
						if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
							echo '<div class="inside">' . esc_html($section['desc']) . '</div>';
						}
					}, $tab['id'] . $section['id'] );

					$section['fields'] = apply_filters( 'thvs_settings_fields', $section['fields'], $section, $tab );

					foreach ( $section['fields'] as $field ) {

						$field['label_for'] = $field['id'] . '-field';
						$field['default']   = isset( $field['default'] ) ? $field['default'] : null;

						if ( $field['type'] == 'checkbox' || $field['type'] == 'radio' ) {
							unset( $field['label_for'] );
						}

						add_settings_field( $this->settings_name . '[' . $field['id'] . ']', $field['title'], array(
							$this,
							'field_callback'
						), $tab['id'] . $section['id'], $tab['id'] . $section['id'], $field );

					}
				}
			}
		}

		public function options_tabs() {
			?>
			<div class="nav-tab-wrapper wp-clearfix">
				<div class="top-wrap">

					<div id="logo">
						<a href="<?php echo esc_url('https://themehunk.com/advance-product-search/'); ?>" target="_blank">
						<img src='<?php echo esc_url(TH_VARIATION_SWATCHES_IMAGES_URI.'/responsive-logo.png') ?>' alt="th-logo" class="resp-logo"/>
						<img src='<?php echo esc_url(TH_VARIATION_SWATCHES_IMAGES_URI.'/th-logo.png') ?>' alt="tapsp-logo" class="th-logo"/>
					</a>
					</div>

				  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

			     </div>
				<?php foreach ( $this->fields as $tabs ): ?>
					<a data-target="<?php echo esc_attr($tabs['id']); ?>"  class="thvs-setting-nav-tab nav-tab <?php echo esc_html($this->get_options_tab_css_classes( $tabs )); ?> " href="#<?php echo esc_attr($tabs['id']); ?>"><span><?php echo $this->icon_list($tabs['id']); ?></span><?php echo esc_html($tabs['title']); ?></a>
				<?php endforeach; ?>

				<div class="thvs-collapse-sidebar">
				    <button id="thvs-toggle-sidebar">
				        <span class="dashicons dashicons-arrow-left-alt2"></span>
				        <span class="collapse-text">Collapse Sidebar</span>
				    </button>
				</div>

			</div>
			<?php
		}
	
	function icon_list($id ='dashicons-menu'){
			$icon = array(
				'simple'=>'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wand-sparkles text-blue-600 shrink-0" aria-hidden="true"><path d="m21.64 3.64-1.28-1.28a1.21 1.21 0 0 0-1.72 0L2.36 18.64a1.21 1.21 0 0 0 0 1.72l1.28 1.28a1.2 1.2 0 0 0 1.72 0L21.64 5.36a1.2 1.2 0 0 0 0-1.72"></path><path d="m14 7 3 3"></path><path d="M5 6v4"></path><path d="M19 14v4"></path><path d="M10 2v2"></path><path d="M7 8H3"></path><path d="M21 16h-4"></path><path d="M11 3H9"></path></svg>',
				'advanced' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hammer text-slate-400 group-hover:text-slate-600 shrink-0" aria-hidden="true"><path d="m15 12-9.373 9.373a1 1 0 0 1-3.001-3L12 9"></path><path d="m18 15 4-4"></path><path d="m21.5 11.5-1.914-1.914A2 2 0 0 1 19 8.172v-.344a2 2 0 0 0-.586-1.414l-1.657-1.657A6 6 0 0 0 12.516 3H9l1.243 1.243A6 6 0 0 1 12 8.485V10l2 2h1.172a2 2 0 0 1 1.414.586L18.5 14.5"></path></svg>',
				'thvs_style'=>'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-paintbrush text-slate-400 group-hover:text-slate-600 shrink-0" aria-hidden="true"><path d="m14.622 17.897-10.68-2.913"></path><path d="M18.376 2.622a1 1 0 1 1 3.002 3.002L17.36 9.643a.5.5 0 0 0 0 .707l.944.944a2.41 2.41 0 0 1 0 3.408l-.944.944a.5.5 0 0 1-.707 0L8.354 7.348a.5.5 0 0 1 0-.707l.944-.944a2.41 2.41 0 0 1 3.408 0l.944.944a.5.5 0 0 0 .707 0z"></path><path d="M9 8c-1.804 2.71-3.97 3.46-6.583 3.948a.507.507 0 0 0-.302.819l7.32 8.883a1 1 0 0 0 1.185.204C12.735 20.405 16 16.792 16 15"></path></svg>',
				'thvs_special'=>'<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings text-slate-400 group-hover:text-slate-600 shrink-0" aria-hidden="true"><path d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915"></path><circle cx="12" cy="12" r="3"></circle></svg>',
				'thvs_documention'=>'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap h-4 w-4 text-indigo-600" aria-hidden="true"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path></svg>',
				'thvs_reset'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-ccw" aria-hidden="true"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"></path><path d="M16 16h5v5"></path></svg>',
				'thvs_help'=>'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                <path d="M12 17h.01"></path>
            </svg>',
		);

			return $icon[$id];

		}

		private function get_last_active_tab() {
			$last_option_tab = '';
			$last_tab        = $last_option_tab;

			if ( isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ) {
				$last_tab = trim( sanitize_key($_GET['tab']) );
			}

			if ( $last_option_tab ) {
				$last_tab = $last_option_tab;
			}

			$default_tab = '';
			foreach ( $this->fields as $tabs ) {
				if ( isset( $tabs['active'] ) && $tabs['active'] ) {
					$default_tab = sanitize_key($tabs['id']);
					break;
				}
			}

			return ! empty( $last_tab ) ? esc_html( $last_tab ) : esc_html( $default_tab );

		}
		private function last_tab_input() {
			printf( '<input type="hidden" id="_last_active_tab" name="%s[_last_active_tab]" value="%s">', $this->settings_name, $this->get_last_active_tab() );
		}

			private function get_options_tab_pro_attr( $tabs ) {
			
			$attrs[] = ( isset( $tabs['is_new'] ) && $tabs['is_new'] ) ? sprintf( 'data-new-text="%s"', apply_filters( 'thvs_settings_tab_new_text', 'New' ) ) : false;

			return implode( ' ', $attrs );
		}

		private function get_options_tab_css_classes( $tabs ) {
			$classes = array();

			$classes[] = ( $this->get_last_active_tab() == $tabs['id'] ) ? 'nav-tab-active' : '';

			return implode( ' ', array_unique( apply_filters( 'get_options_tab_css_classes', $classes ) ) );
		}

			private function do_settings_sections( $page ) {
			global $wp_settings_sections, $wp_settings_fields;
			$subtitle = '';

			if ( ! isset( $wp_settings_sections[ $page ] ) ) {
				return;
			}

			foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
				
				// if ( $section['title'] ) {
				// 	echo "<h2>{$section['title']}</h2>\n";
				// }

				if ( $section['callback'] ) {
					call_user_func( $section['callback'], $section );
				}

				if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
					continue;
				}

				echo '<div class="form-table" id='.esc_attr($section['id']).'>';

				// To add subtitle start
				foreach ( $this->fields as $tab ) {
				    foreach ( $tab['sections'] as $sec ) {
				        if ( $tab['id'] . $sec['id'] === $page ) {
				            if ( ! empty( $sec['subtitle'] ) ) {
				                $subtitle = $sec['subtitle'];
				            }
				        }
				    }
				}

					echo '<div class="headingwrapper">';
				if ( $section['title'] ) {

					echo '<h2 class="heading ' . esc_attr($section['id']) . '">' . esc_html($section['title']) . '</h2>';

				}

				if ( $subtitle ) {
				    echo '<p class="tapsp-section-subtitle">' . esc_html( $subtitle ) . '</p>';
				}
				echo '</div>';

				$this->do_settings_fields( $page, $section['id'] );
				echo '</div>';
			}
		}

				private function do_settings_fields( $page, $section ) {
			global $wp_settings_fields;

			if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
				return;
			}

			foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
				/*$class = '';

				if ( ! empty( $field[ 'args' ][ 'class' ] ) ) {
					$class = ' class="' . esc_attr( $field[ 'args' ][ 'class' ] ) . '"';
				}*/

				$custom_attributes = $this->array2html_attr( isset( $field['args']['attributes'] ) ? $field['args']['attributes'] : array() );

				$wrapper_id = ! empty( $field['args']['id'] ) ? esc_attr( $field['args']['id'] ) . '-wrapper' : '';
				$dependency = ! empty( $field['args']['require'] ) ? $this->build_dependency( $field['args']['require'] ) : '';

				$is_new   = ( isset( $field['args']['is_new'] ) && $field['args']['is_new'] );
				$new_html = $is_new ? '<span class="thvs-new-feature-tick">' . esc_html__( 'NEW', 'tthvs' ) . '</span>' : '';

				printf( '<div id="%s" class="thvs-settings-row" %s %s>', $wrapper_id, $custom_attributes, $dependency );

				if ( isset( $field['args']['pro'] ) ) {
					echo '<div colspan="2" style="padding: 0; margin: 0">';
					$this->pro_field_callback( $field['args'] );
					echo '</div>';
				} else {
					echo '<div scope="row" class="thvs-settings-label">';
					if ( ! empty( $field['args']['label_for'] ) ) {
						echo '<label for="' . esc_attr( $field['args']['label_for'] ) . '">' . $field['title'] . $new_html . '</label>';
					} else {
						echo $field['title'] . $new_html;
					}

					
					echo '</div>';


					echo '<div class="thvs-settings-field-content">';
					call_user_func( $field['callback'], $field['args'] );
					echo '</div>';
				}

				echo '</div>';
			}
		}

		 public function array2html_attr( $attributes, $do_not_add = array() ) {

			$attributes = wp_parse_args( $attributes, array() );

			if ( ! empty( $do_not_add ) and is_array( $do_not_add ) ) {
				foreach ( $do_not_add as $att_name ) {
					unset( $attributes[ $att_name ] );
				}
			}


			$attributes_array = array();

			foreach ( $attributes as $key => $value ) {

				if ( is_bool( $attributes[ $key ] ) and $attributes[ $key ] === true ) {
					return $attributes[ $key ] ? $key : '';
				} elseif ( is_bool( $attributes[ $key ] ) and $attributes[ $key ] === false ) {
					$attributes_array[] = '';
				} else {
					$attributes_array[] = $key . '="' . esc_attr($value) . '"';
				}
			}

			return implode( ' ', $attributes_array );
		}
      private function build_dependency( $require_array ) {
			$b_array = array();
			foreach ( $require_array as $k => $v ) {
				$b_array[ '#' . $k . '-field' ] = $v;
			}

			return 'data-thvsdepends="[' . esc_attr( wp_json_encode( $b_array ) ) . ']"';
		}
		
	
      public function make_implode_html_attributes( $attributes, $except = array( 'type', 'id', 'name', 'value' ) ) {
			$attrs = array();
			foreach ( $attributes as $name => $value ) {
				if ( in_array( $name, $except, true ) ) {
					continue;
				}
				$attrs[] = esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
			}

			return implode( ' ', array_unique( $attrs ) );
		}
		public function get_option( $option ) {
			$default = $this->get_default( $option );
			$options = get_option( $this->settings_name );
			$is_new = ( ! is_array( $options ) && is_bool( $options ) );

			// Theme Support
			if ( current_theme_supports( $this->theme_feature_name ) ) {
				$theme_support    = get_theme_support( $this->theme_feature_name );
				$default['value'] = isset( $theme_support[0][ $option ] ) ? $theme_support[0][ $option ] : $default['value'];
			}

			$default_value = isset( $default['value'] ) ? $default['value'] : null;

			if ( ! is_null( $this->get_reserved( $option ) ) ) {
				$default_value = $this->get_reserved( $option );
			}

			if ( $is_new ) {
			
				return $default_value;
			} else {
			
				return isset( $options[ $option ] ) ? $options[ $option ] : $default_value;
			}
		}

		public function get_options(){
			return get_option( $this->settings_name );
		}

		public function get_reserved( $key = false ){

			$data = (array) get_option( $this->reserved_key );
			if ( $key ) {
				return isset( $data[ $key ] ) ? $data[ $key ] : null;
			} else {
				return $data;
			}
		}

		public function save_reserved( $value ) {
			$reserved_data = array();
			foreach ( (array) $this->reserved_fields as $fieldKey ) {
				if ( ! empty( $value[ $fieldKey ] ) ) {
					$reserved_data[ $fieldKey ] = $value[ $fieldKey ];
				}
			}

			if ( ! empty( $reserved_data ) ) {
				update_option( $this->reserved_key, $reserved_data );
			} else {
				delete_option( $this->reserved_key );
			}
		}
	
    /***************/
		// Field call back function
		/***************/

		public function field_callback( $field ) {

			switch ( $field['type'] ) {
				case 'radio':
					$this->radio_field_callback( $field );
					break;

				case 'checkbox':
					$this->checkbox_field_callback( $field );
					break;

				case 'select':
					$this->select_field_callback( $field );
					break;

				case 'number':
					$this->number_field_callback( $field );
					break;

				case 'pro':
					$this->pro_field_callback( $field );
					break;

				case 'usefullplugin':
					$this->usefullplugin_field_callback( $field );
					break;	

				case 'iframe':
					$this->iframe_field_callback( $field );
					break;

				case 'color':
					$this->color_field_callback( $field );
					break;

				default:
					$this->text_field_callback( $field );
					break;
			}
			do_action( 'thvs_settings_field_callback', $field );
		}

		// 🔥 COMMON FUNCTION
		    public function allowed_html() {
		        return array(
		            'a' => array(
		                'href'   => array(),
		                'title'  => array(),
		                'target' => array(),
		                'rel'    => array(),
		                'class'  => array(),
		                'id'     => array(),
		            ),
		            'span' => array(
		                'class'  => array(),
		                'id'     => array(),
		                'style'  => array(),
		                'title'  => array(),
		                'data-*' => true,
		            ),
		            'code' => array(
		                'class' => array(),
		                'id'    => array(),
		                'style' => array(),
		            ),
		        );
		    }
      public function checkbox_field_callback( $args ) {
               
			$value = (bool)( $this->get_option( $args['id'] ) );

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';?>

            <fieldset>
            	<label class="th-toggle">
            		<input <?php echo esc_attr($attrs); ?> type="checkbox" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]" value="1" <?php echo esc_attr(checked( $value, true, false ));?>> <?php if ( ! empty( $args['desc'] ) ) {  echo esc_html($args['desc']); } ?>
            	</label>     
            </fieldset> <?php 
		  }

			public function radio_field_callback( $args ) {
		
      $options = apply_filters( "thvs_settings_{$args[ 'id' ]}_radio_options", $args['options'] );

			$value   = esc_attr( $this->get_option( $args['id'] ) );

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';
		
		$html = '<fieldset>';
			$html .= implode( '<br />', array_map( function ( $key, $option ) use ( $attrs, $args, $value ) {
				return sprintf( '<label><input %1$s type="radio"  name="%4$s[%2$s]" value="%3$s" %5$s/> %6$s</label>', $attrs, $args['id'], $key, $this->settings_name, checked( $value, $key, false ), $option );
			}, array_keys( $options ), $options ) );
			$html .= $this->get_field_description( $args );
			$html .= '</fieldset>';

			echo $html;
		}

		public function select_field_callback( $args ) {

			$options = apply_filters( "thvs_settings_{$args[ 'id' ]}_select_options", $args['options'] );

			$valuee   = $this->get_option( $args['id'] );

		
			$size    = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';
			?>

			<select <?php echo esc_attr($attrs); ?> class="<?php echo esc_attr($size); ?>-text" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]">

				<?php foreach($options as $key => $value){ ?>

                <option <?php echo esc_attr(selected( $key, $valuee, false )) ;?> value="<?php echo esc_attr($key);?>">
                	
                	<?php echo esc_html($value);?> 	

                </option> 

               <?php } ?>

			</select>

			<?php if ( ! empty( $args['desc'] ) ) { 

       $arr = array( 
    'a' => array(
        'href' => array(),
        'title' => array(),
        'target' => array(),
        'rel' => array(),
        'class' => array(),
        'id' => array(),
    ),
    'span' => array(
        'class' => array(),
        'id' => array(),
        'style' => array(),
        'title' => array(),
        'data-*' => true,
    ),
    'code' => array(
        'class' => array(),
        'id' => array(),
        'style' => array(),
    )
);

				?>

      <p class="description"><?php echo wp_kses($args['desc'],$arr);?></p>

		    <?php } }


		  public function get_field_description( $args ) {

			$desc = '';
			

			if ( ! empty( $args['desc'] ) ) {
				$desc .= sprintf( '<p class="description">%s</p>', $args['desc'] );
			} else {
				$desc .= '';
			}

			return ( ( $args['type'] === 'checkbox' ) ) ? '' : $desc;

		}

		public function text_field_callback( $args ) {
			$value =  $this->get_option( $args['id'] );

			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';?>

            <input type="text" class="<?php echo esc_attr($size); ?>-text" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]" value="<?php echo esc_attr($value); ?>"/>

            <?php if ( ! empty( $args['desc'] ) ) { ?>

            <p class="description"><?php echo esc_html($args['desc']);?></p>

	        <?php 

	           }
				
		}
     

     public function pro_field_callback( $args ) {

			$is_html = isset( $args['html'] );

			if ( $is_html ) {
				$html = $args['html'];
			} else {
				$image1 = esc_url( $args['screen_shot1'] );
				$image2 = esc_url( $args['screen_shot2'] );
				$image3 = esc_url( $args['screen_shot3'] );
				$image4 = esc_url( $args['screen_shot4'] );
				$image5 = esc_url( $args['screen_shot5'] );
				$image6 = esc_url( $args['screen_shot6'] );
				$link1  = $args['link1'];
				$link2  = $args['link2'];
				$width = isset( $args['width'] ) ? $args['width'] : '70%';?>

				<a target="_blank" href="<?php echo esc_url($link1); ?>"><img style="width: <?php echo esc_attr($width); ?>" src="<?php echo esc_url($image1); ?>" /></a>
				<a target="_blank" href="<?php echo esc_url($link1); ?>"><img style="width: <?php echo esc_attr($width); ?>" src="<?php echo esc_url($image2); ?>" /></a>
				<a target="_blank" href="<?php echo esc_url($link1); ?>"><img style="width: <?php echo esc_attr($width); ?>" src="<?php echo esc_url($image3); ?>" /></a>
				<a target="_blank" href="<?php echo esc_url($link1); ?>"><img style="width: <?php echo esc_attr($width); ?>" src="<?php echo esc_url($image4); ?>" /></a>
				<a target="_blank" href="<?php echo esc_url($link1); ?>"><img style="width: <?php echo esc_attr($width); ?>" src="<?php echo esc_url($image5); ?>" /></a>
				<a target="_blank" href="<?php echo esc_url($link1); ?>"><img style="width: <?php echo esc_attr($width); ?>" src="<?php echo esc_url($image6); ?>" /></a>

				<a class="pro-button" target="_blank" href="<?php echo esc_url($link2); ?>"><?php echo esc_html__('MORE DETAIL','th-variation-swatches')?></a>

				<a class="pro-button buynow" target="_blank" href="<?php echo esc_url($link1); ?>"><?php echo esc_html__('BUY NOW','th-variation-swatches')?></a>

				<?php 

			}

		 }

		 public function color_field_callback( $args ){
			$value = esc_attr( $this->get_option( $args['id'] ) );
			// $size  = isset( $args[ 'size' ] ) && ! is_null( $args[ 'size' ] ) ? $args[ 'size' ] : 'regular';
			$alpha = isset( $args['alpha'] ) && $args['alpha'] === true ? ' data-alpha-enabled="true"' : '';
			$html  = sprintf( '<input type="text" %1$s class="thvs-color-picker" id="%2$s-field" name="%4$s[%2$s]" value="%3$s"  data-default-color="%3$s" />', $alpha, $args['id'], $value, $this->settings_name );
			$html  .= $this->get_field_description( $args );

			echo $html;
		}

			public function usefullplugin_field_callback( $args ) {

			$is_html = isset( $args['html'] );

			if ( $is_html ) {

				$html = $args['html'];

			  } else {

				$plugin_image  = $args['plugin_image'];
				$plugin_title  = $args['plugin_title'];
				$plugin_link   = $args['plugin_link'];
				
			}

			?>

			<div class="thvs-use-plugin"><img src="<?php echo esc_url($plugin_image);?>" /><a target="_blank" href="<?php echo esc_url($plugin_link);?>"><?php echo esc_html($plugin_title);?></a>
			</div>

		<?php }

		public function iframe_field_callback( $args ) {

			$is_html = isset( $args['html'] );

			if ( $is_html ){

				$html = $args['html'];

			  } else {

				$screen_frame = esc_url( $args['screen_frame'] );
        $doc_link     = esc_url( $args['doc_link'] );
        $doc_text     = esc_html($args['doc-texti']);
				$width        = isset( $args['width'] ) ? $args['width'] : '100%';
				$height       = isset( $args['height'] ) ? $args['height'] : '100%';

       ?>

        <iframe width="<?php echo esc_attr($width); ?>" height="<?php echo esc_attr($height); ?>" src="<?php echo esc_url($screen_frame); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe><a target="_blank" href="<?php echo esc_url($doc_link); ?>"><?php echo esc_attr($doc_text); ?></a>

			<?php 	

			}
			
		}

		public function number_field_callback( $args ) {

			$value = $this->get_option( $args['id'] );

			$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'small';

			$attrs = isset( $args['attrs'] ) ? $this->make_implode_html_attributes( $args['attrs'] ) : '';
            ?>

			<input type="number"  <?php echo esc_attr($attrs); ?> class="<?php echo esc_attr($size); ?>-text" id="<?php echo esc_attr($args['id']); ?>-field" name="<?php echo esc_attr($this->settings_name);?>[<?php echo esc_attr($args['id']);?>]" value="<?php echo esc_attr($value); ?>"  min="<?php echo esc_attr($args['min']); ?>" max="<?php echo esc_attr($args['max']); ?>" step="<?php  if ( ! empty($args['step']) ) { 
				echo esc_attr($args['step']); } ?>" />

           <?php if(isset( $args['suffix'] ) && ! is_null( $args['suffix'] ) ){ ?>

			     <span><?php echo esc_attr($args['suffix']); ?></span>
         
             <?php

               }

           if ( ! empty( $args['desc'] ) ) { 

                  $arr = array( 
    'a' => array(
        'href' => array(),
        'title' => array(),
        'target' => array(),
        'rel' => array(),
        'class' => array(),
        'id' => array(),
    ),
    'span' => array(
        'class' => array(),
        'id' => array(),
        'style' => array(),
        'title' => array(),
        'data-*' => true,
    ),
    'code' => array(
        'class' => array(),
        'id' => array(),
        'style' => array(),
    )
);
				?>

      <p class="description"><?php echo wp_kses($args['desc'],$arr);?></p>  

		  <?php 	

	         } 
		}

		public function script_enqueue(){

				wp_enqueue_media();
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style( 'th-variation-swatches-admin', TH_VARIATION_SWATCHES_PLUGIN_URI. '/assets/css/admin.css', array(), TH_VARIATION_SWATCHES_VERSION );
				
				wp_enqueue_script( 'wp-color-picker-alpha', TH_VARIATION_SWATCHES_PLUGIN_URI. '/assets/js/wp-color-picker-alpha.js', array('wp-color-picker'),true);
				wp_enqueue_script( 'wp-color-picker-alpha' );
				wp_enqueue_script( 'thvs-setting-script', TH_VARIATION_SWATCHES_PLUGIN_URI. '/assets/js/thvs-setting.js', array('jquery'),true);
				wp_localize_script(
					'thvs-setting-script', 'THVSPluginObject', array(
						'media_title'   => esc_html__( 'Choose an Image', 'th-variation-swatches' ),
						'button_title'  => esc_html__( 'Use Image', 'th-variation-swatches' ),
						'add_media'     => esc_html__( 'Add Media', 'th-variation-swatches' ),
						'ajaxurl'       => esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
						'nonce'         => wp_create_nonce( 'thvs_plugin_nonce' ),
					)
				);

		}
  }

endif;