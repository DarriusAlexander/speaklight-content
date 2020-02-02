<?php
/*
Custom sidebars
http://marquex.es/698/custom-sidebars-1-0
1.1
*/

if ( ! class_exists( 'GhostPool_Custom_Sidebars' ) ) {

	class GhostPool_Custom_Sidebars {
	
		var $message = '';
		var $message_class = '';
	
		// The name of the option that stores the info of the new bars.
		var $option_name = "ghostpool_sidebars";
	
		// The name of the option that stores which bars are replaceable, and the default replacements. The value is stored in $this->options
		var $option_modifiable = "ghostpool_modifiable";
	
		var $sidebar_prefix = 'gp-cs-';
		var $postmeta_key = '_ghostpool_replacements';
		var $cap_required = 'switch_themes';
		var $ignore_post_types = array( 'attachment', 'revision', 'nav_menu_item', 'pt-widget' );
		var $options = array();
	
		var $replaceable_sidebars = array();
		var $replacements = array();
		var $replacements_todo;
	
		function custom_sidebars() {
			$this->retrieveOptions();
			$this->replaceable_sidebars = $this->getModifiableSidebars();
			$this->replacements_todo = sizeof( $this->replaceable_sidebars );
			foreach( $this->replaceable_sidebars as $sb )
				$this->replacements[$sb] = FALSE;
		}
	
		function retrieveOptions() {
			$this->options = get_option( $this->option_modifiable );
		}
	
		function getcustom_sidebars() {
			$sidebars = get_option( $this->option_name );
			if ( $sidebars )
				return $sidebars;
			return array();
		}
	
		function getThemeSidebars( $include_custom_sidebars = FALSE ) {
		
			global $wp_registered_sidebars;		
			$all_sidebars = $wp_registered_sidebars;
			ksort( $all_sidebars );
			if ( $include_custom_sidebars )
				return $all_sidebars;
		
			$theme_sidebars = array();
			foreach( $all_sidebars as $key => $sb ) {
				if ( substr( $key, 0, 3 ) != $this->sidebar_prefix )
					$theme_sidebars[$key] = $sb;
			}
		
			return $theme_sidebars;
		}
	
		function registercustom_sidebars() {
			$sb = $this->getcustom_sidebars();
			if ( ! empty( $sb ) ) {
				foreach( $sb as $sidebar ) {
					register_sidebar( $sidebar );
				}
			}
		}
	
		function checkAndFixSidebar( $sidebar, $replacement, $method, $extra_index ) {
			global $wp_registered_sidebars;
		
		
			if ( isset( $wp_registered_sidebars[$replacement] ) )
				return true;
		
			if ( $method == 'particular' ) {
				global $post;
				$sidebars = get_post_meta( $post->ID, $this->postmeta_key, TRUE );
				if ( $sidebars && isset( $sidebars[$sidebar] ) ) {
					unset( $sidebars[$sidebar] );
					update_post_meta( $post->ID, $this->postmeta_key, $sidebars );	
				}
			}
			else{
				if ( isset( $this->options[$method] ) ) {
					if ( $extra_index != -1 && isset( $this->options[$method][$extra_index] ) && isset( $this->options[$method][$extra_index][$sidebar] ) ) {
						unset( $this->options[$method][$extra_index][$sidebar] );
						update_option( $this->option_modifiable, $this->options );
					}
					if ( $extra_index == 1 && isset( $this->options[$method] ) && isset( $this->options[$method][$sidebar] ) ) {
						unset( $this->options[$method][$sidebar] );
						update_option( $this->option_modifiable, $this->options );				
					}
				}
			}
		
			return false;
		}
	
		function deleteSidebar() {
			if ( ! current_user_can( $this->cap_required ) )
				return new WP_Error( 'ghostpool_cant_delete', esc_html__( 'You do not have permission to delete sidebars.', 'socialize' ) );
		
					if ( ! defined( 'DOING_AJAX' ) && ! DOING_AJAX && ! wp_verify_nonce( $_REQUEST['_n'], 'ghostpool_delete_sidebars_action' ) ) 
							die( 'Security check stop your request.' ); 
		
			$newsidebars = array();
			$deleted = FALSE;
		
			$custom = $this->getcustom_sidebars();
		
			if ( ! empty( $custom ) ) {
		
			foreach( $custom as $sb ) {
				if ( $sb['id']!=$_REQUEST['delete'] )
					$newsidebars[] = $sb;
				else
					$deleted = TRUE;
			}
			}//endif custom
		
			//update option
			update_option( $this->option_name, $newsidebars );

			$this->refreshSidebarsWidgets();
		
			if ( $deleted )
				$this->setMessage( sprintf( esc_html__( 'The sidebar "%s" has been deleted.', 'socialize' ), $_REQUEST['delete'] ) );
			else
				$this->setError( sprintf( esc_html__( 'There was not any sidebar called "%s" and it could not been deleted.', 'socialize' ), $_GET['delete'] ) );
		}
	
		function createPage() {
		
			//$this->refreshSidebarsWidgets();
			if ( ! empty( $_POST ) ) {
				if ( isset( $_POST['create-sidebars'] ) ) {
					check_admin_referer( 'ghostpool_new_sidebars_action' );
					$this->storeSidebar();
				}
				else if ( isset( $_POST['update-sidebar'] ) ) {
					check_admin_referer( 'ghostpool_edit_sidebar_action' );
					$this->updateSidebar();
				}		
				else if ( isset( $_POST['update-modifiable'] ) ) {
					$this->updateModifiable();
									$this->retrieveOptions();
									$this->replaceable_sidebars = $this->getModifiableSidebars();
							}
				else if ( isset( $_POST['update-defaults-posts'] ) OR isset( $_POST['update-defaults-pages'] ) ) {
					$this->storeDefaults();
			
				}
				
				else if ( isset( $_POST['reset-sidebars'] ) )
					$this->resetSidebars();			
				
				$this->retrieveOptions();
			}
			else if ( ! empty( $_GET['delete'] ) ) {
				$this->deleteSidebar();
				$this->retrieveOptions();			
			}
			else if ( ! empty( $_GET['p'] ) ) {
				if ( $_GET['p']=='edit' && ! empty( $_GET['id'] ) ) {
					$custom_sidebars = $this->getcustom_sidebars();
					if ( ! $sb = $this->getSidebar( $_GET['id'], $custom_sidebars ) )
						return new WP_Error( 'ghostpool_cant_delete', esc_html__( 'You do not have permission to delete sidebars', 'socialize' ) );
					include( get_template_directory() . '/lib/framework/custom-sidebars/edit.php' );
					return;	
				}
			}
		
			$custom_sidebars = $this->getcustom_sidebars();
			$theme_sidebars = $this->getThemeSidebars();
			$all_sidebars = $this->getThemeSidebars( TRUE );
			$defaults = $this->getDefaultReplacements();
			$modifiable = $this->replaceable_sidebars;
			$post_types = $this->getPostTypes();
		
			$delete_nonce = wp_create_nonce( 'ghostpool_delete_sidebars_action' );
		
			//var_dump( $defaults );
		
			//Form
			if ( ! empty( $_GET['p'] ) ) {
				if ( $_GET['p']=='defaults' ) {
					$categories = get_categories( array( 'hide_empty' => 0 ) );
					if ( sizeof( $categories )==1 && $categories[0]->cat_ID == 1 )
						unset( $categories[0] );
				}
				else if ( $_GET['p']=='edit' )
					include( get_template_directory() . '/lib/framework/custom-sidebars/edit.php' );
							else if ( $_GET['p']=='removebanner' )
								return $this->removeBanner();
				else
					include( get_template_directory() . '/lib/framework/custom-sidebars/settings.php' );	
				
			}
			else		
				include( get_template_directory() . '/lib/framework/custom-sidebars/settings.php' );		
		}
	
		function addSubMenus() {
			$page = add_theme_page( esc_html__( 'Sidebars', 'socialize' ), esc_html__( 'Sidebars', 'socialize' ), $this->cap_required, 'sidebars', array( $this, 'createPage' ) );
			add_action( 'admin_print_scripts-' . $page, array( $this, 'addScripts' ) );
		}
	
		function addScripts() {
			wp_enqueue_script( 'post' );
		}

		function getReplacements( $postid ) {
			$replacements = get_post_meta( $postid, $this->postmeta_key, TRUE );
			if ( $replacements == '' )
				$replacements = array();
			else
				$replacements = $replacements;
			return $replacements;
		}
	
		function getModifiableSidebars() {
			if (  $modifiable = $this->options )
				return $modifiable['modifiable'];
			return array(); 
		}
	
		function getDefaultReplacements() {
			if (  $defaults = $this->options  ) {
				$defaults['post_type_posts'] = $defaults['defaults'];
				unset( $defaults['modifiable'] );
				unset( $defaults['defaults'] );
				return $defaults;
			}
			return array(); 
		}
	
		function updateModifiable() {
			check_admin_referer( 'custom-sidebars-options', 'options_wpnonce' );
			$options = $this->options ? $this->options : array();
		
			//Modifiable bars
			if ( isset( $_POST['modifiable'] ) && is_array( $_POST['modifiable'] ) )
				$options['modifiable'] = $_POST['modifiable'];

		
			if ( $this->options !== FALSE )
				update_option( $this->option_modifiable, $options );
			else
				add_option( $this->option_modifiable, $options );
			
			$this->setMessage( esc_html__( 'The custom sidebars settings has been updated successfully.', 'socialize' ) );
		}
	
		function storeSidebar() {
			$name = trim( $_POST['sidebar_name'] );
			$description = trim( $_POST['sidebar_description'] );
			if ( empty( $name ) OR empty( $description ) )
				$this->setError( esc_html__( 'You have to fill all the fields to create a new sidebar.', 'socialize' ) );
			else{
				$id = $this->sidebar_prefix . sanitize_html_class( sanitize_title_with_dashes( $name ) );
				$sidebars = get_option( $this->option_name, FALSE );
				if ( $sidebars !== FALSE ) {
					$sidebars = $sidebars;
					if ( ! $this->getSidebar( $id,$sidebars )  ) {
						//Create a new sidebar
						$sidebars[] = array( 
							'name' => $name,
							'id' => $id,
							'description' => $description,
							'before_widget' => '<div id="%1$s" class="widget %2$s">',
							'after_widget'  => '</div>',
							'before_title'  => '<h3 class="widgettitle">',
							'after_title'   => '</h3>',
							 ) ;
						
					
						//update option
						update_option( $this->option_name, $sidebars );
						
						$this->refreshSidebarsWidgets();
					
						$this->setMessage( esc_html__( 'The sidebar has been created successfully.', 'socialize' ) );
					
					
					}
					else
						$this->setError( esc_html__( 'There is already a sidebar registered with that name, please choose a different one.', 'socialize' ) );
				}
				else{
					$id = $this->sidebar_prefix . sanitize_html_class( sanitize_title_with_dashes( $name ) );
					$sidebars= array( array( 
							'name' => $name,
							'id' => $id,
							'description' => $description,
							'before_widget' => '<div id="%1$s" class="widget %2$s">',
							'after_widget'  => '</div>',
							'before_title'  => '<h3 class="widgettitle">',
							'after_title'   => '</h3>',	
							 ) );
					add_option( $this->option_name, $sidebars );
				
				
					$this->refreshSidebarsWidgets();
				
					$this->setMessage( esc_html__( 'The sidebar has been created successfully.', 'socialize' ) );					
				}
			}
		}
	
		function updateSidebar() {
			$id = trim( $_POST['sidebar_id'] );
			$name = trim( $_POST['sidebar_name'] );
			$description = trim( $_POST['sidebar_description'] );

		
			$sidebars = $this->getcustom_sidebars();
		
			//Check the id		
			$url = parse_url( $_POST['_wp_http_referer'] );
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {} else {
				if ( isset( $url['query'] ) ) {
						parse_str( $url['query'], $args );
						if ( $args['id'] != $id )
								return new WP_Error( esc_html__( 'The operation is not secure and it cannot be completed.', 'socialize' ) );
				}
				else
						return new WP_Error( esc_html__( 'The operation is not secure and it cannot be completed.', 'socialize' ) );
			}

			$newsidebars = array();
			foreach( $sidebars as $sb ) {
				if ( $sb['id'] != $id )
					$newsidebars[] = $sb;
				else
					$newsidebars[] = array( 
							'name' => $name,
							'id' => $id,
							'description' => $description,
							'before_widget' => '<div id="%1$s" class="widget %2$s">',
							'after_widget'  => '</div>',
							'before_title'  => '<h3 class="widgettitle">',
							'after_title'   => '</h3>',	
							 ) ;
			}
		
			//update option
			update_option( $this->option_name, $newsidebars );
			$this->refreshSidebarsWidgets();
		
			$this->setMessage( sprintf( esc_html__( 'The sidebar "%s" has been updated successfully.', 'socialize' ), $id ) );
		}
	
		function getSidebar( $id, $sidebars ) {
			$sidebar = false;
			$nsidebars = sizeof( $sidebars );
			$i = 0;
			while( ! $sidebar && $i<$nsidebars ) {
				if ( $sidebars[$i]['id'] == $id )
					$sidebar = $sidebars[$i];
				$i++;
			}
			return $sidebar;
		}
	
		function message( $echo = TRUE ) {
			$message = '';
			if ( ! empty( $this->message ) )
				$message = $this->message;
		
			if ( $echo )
				echo '<div id="message" class="' . $this->message_class . '"><p><strong>' . esc_attr( $message ) . '</strong></p></div>';
			else
				return '<div id="message" class="' . $this->message_class . '"><p><strong>' . esc_attr( $message ) . '</strong></p></div>';
		}
	
		function setMessage( $text ) {
			$this->message = $text;
			$this->message_class = 'updated';
		}
	
		function setError( $text ) {
			$this->message = $text;
			$this->message_class = 'error';
		}
	
		function getPostTypes() {
			$pt = get_post_types();
			$ptok = array();
		
			foreach( $pt as $t ) {
				if ( array_search( $t, $this->ignore_post_types ) === FALSE )
					$ptok[] = $t;
			}
		
			return $ptok; 
		}
	
		function getEmptyWidget() {
			return array( 
				'name' => esc_html__( 'GhostPool Empty Widget', 'socialize' ),
				'id' => 'gp-cs-empty-widget',
				'callback' => array( new custom_sidebarsEmptyPlugin(), 'display_callback' ),
				'params' => array( array( 'number' => 2 ) ),
				'classname' => 'GhostPool_Custom_Sidebars_Empty_Plugin',
				'description' => esc_html__( 'Custom sidebar description', 'socialize' ),
			 );
		}
	
		function refreshSidebarsWidgets() {
			$widgetized_sidebars = get_option( 'sidebars_widgets' );
			$delete_widgetized_sidebars = array();
			$custom_sidebars = get_option( $this->option_name );
		
			foreach( $widgetized_sidebars as $id => $bar ) {
				if ( substr( $id,0,3 )=='gp-cs-' ) {
					$found = FALSE;
					foreach( $custom_sidebars as $custom_sidebar ) {
						if ( $custom_sidebar['id'] == $id )
							$found = TRUE;
					}
					if ( ! $found )
						$delete_widgetized_sidebars[] = $id;
				}
			}
		
		
			foreach( $custom_sidebars as $custom_sidebar ) {
				if ( array_search( $custom_sidebar['id'], array_keys( $widgetized_sidebars ) )===FALSE ) {
					$widgetized_sidebars[$custom_sidebar['id']] = array(); 
				}
			}
		
			foreach( $delete_widgetized_sidebars as $id ) {
				unset( $widgetized_sidebars[$id] );
			}
		
			update_option( 'sidebars_widgets', $widgetized_sidebars );
		
		}
	
		function resetSidebars() {
			if ( ! current_user_can( $this->cap_required ) )
				return new WP_Error( 'ghostpool_cant_delete', esc_html__( 'You do not have permission to delete sidebars.', 'socialize' ) );
			
			if ( ! wp_verify_nonce( $_REQUEST['reset-n'], 'ghostpool_delete_sidebars_action' ) ) die( 'Security check stopped your request.' ); 
		
			delete_option( $this->option_modifiable );
			delete_option( $this->option_name );
		
			$widgetized_sidebars = get_option( 'sidebars_widgets' );	
			$delete_widgetized_sidebars = array();	
			foreach( $widgetized_sidebars as $id => $bar ) {
				if ( substr( $id,0,3 ) == 'gp-cs-' ) {
					$found = FALSE;
					if ( empty( $custom_sidebars ) )
						$found = TRUE;
					else{
						foreach( $custom_sidebars as $custom_sidebar ) {
							if ( $custom_sidebar['id'] == $id )
								$found = TRUE;
						}
					}
					if ( ! $found )
						$delete_widgetized_sidebars[] = $id;
				}
			}
		
			foreach( $delete_widgetized_sidebars as $id ) {
				unset( $widgetized_sidebars[$id] );
			}
		
			update_option( 'sidebars_widgets', $widgetized_sidebars );
		
			$this->setMessage( esc_html__( 'The custom sidebars data has been removed successfully.', 'socialize' ) );	
		}

	}

} //exists class


if ( ! isset( $plugin_sidebars ) ) {
	$plugin_sidebars = new GhostPool_Custom_Sidebars();	
	add_action( 'widgets_init', array( $plugin_sidebars,'registercustom_sidebars' ) );
	add_action( 'admin_menu', array( $plugin_sidebars,'addSubMenus' ) );
}

if ( ! class_exists( 'GhostPool_Custom_Sidebars_Empty_Plugin' ) ) {
	class GhostPool_Custom_Sidebars_Empty_Plugin extends WP_Widget {
		function custom_sidebarsEmptyPlugin() {
			parent::WP_Widget( false, $name = 'GhostPool_Custom_Sidebars_Empty_Plugin' );
		}
		function form( $instance ) {
			//Nothing, just a dummy plugin to display nothing
		}
		function update( $new_instance, $old_instance ) {
			//Nothing, just a dummy plugin to display nothing
		}
		function widget( $args, $instance ) {		
			echo '';
		}
	} //end class
} //end if class exists