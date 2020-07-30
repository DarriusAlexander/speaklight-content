<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://themify.me/
 * @since      1.0.0
 *
 * @package    Tbp
 * @subpackage Tbp/admin/partials
 */

$themes = self::prepare_themes_for_js();
$message = 'Themes';
// Plupload
if(current_user_can('upload_files') ) {
	wp_enqueue_media();
}
$canInstall=current_user_can( 'manage_options' );
$canEdit=current_user_can( 'edit_theme_options' );
$canSwitch=current_user_can( 'switch_themes' );
$canDelete=current_user_can( 'delete_themes' );
$count = count( $themes );
wp_localize_script( 'tbp-admin', '_tbpThemeSettings', $themes);
if ( $count===0) {
    add_filter( 'tbp_pointers_register', array('TBP_Pointers','setNoThemePointer' ));
}
?>

<div class="wrap">
	
	<h2><?php _e( 'Themes', 'themify' ); ?>
		<span class="title-count theme-count"><?php echo $count; ?></span>
		<?php if($canInstall):?>
		    <div id="placeholder_pointer_add_new_theme" style="display: inline-block;position: relative;">
		    	<a data-type="theme" class="page-title-action" href="#"><?php _e('Add New', 'themify') ?></a>
		    </div>
		<?php elseif(defined('DISALLOW_FILE_MODS') && DISALLOW_FILE_MODS):?>
		    <div class="notice notice-warning"><p><?php _e("Can't add themes because 'DISALLOW_FILE_MODS' is defined on your WordPress config file 'wp-config.php'",'themify')?></p></div>
		<?php endif;?>
		<div id="placeholder_pointer_theme_activated" style="position: relative;display: inline-block;">
			<a class="add-new-h2" href="<?php echo admin_url( 'edit.php?post_type='.Tbp_Templates::$post_type ); ?>"><?php _e('Go to Templates', 'themify') ?></a>
		</div>
		<input placeholder="<?php _e('Search installed themes...', 'themify') ?>" type="search" id="wp-filter-search-input" class="wp-filter-search">
	</h2>

	<div class="theme-browser rendered tbp_theme_browser">
		<div class="tbp_themes">
			<?php
			/*
			 * This PHP is synchronized with the tmpl-theme template below!
			 */

			foreach ( $themes as $theme ) :?>
			<div class="theme tbp_theme<?php if ( $theme['active'] ) echo ' active'; ?>" tabindex="0">
				<div class="tb_more_details" data-id="<?php echo $theme['theme_id']; ?>">
				    <?php if ( ! empty( $theme['screenshot'][0] ) ) { ?>
					    <div class="theme-screenshot">
						    <img src="<?php echo $theme['screenshot'][0]; ?>" alt="" />
					    </div>
				    <?php } else { ?>
					    <div class="theme-screenshot blank"></div>
				    <?php } ?>

				    <span class="more-details"><?php _e( 'Theme Details','themify'); ?></span>
				</div>
				<div class="theme-id-container">
				    <h3 class="theme-name">
					<?php if ( $theme['active'] ):?>
					    <span><?php _e( 'Active:','themify'); ?></span>
					<?php endif;?>
					<?php echo $theme['name']; ?>
				    </h3>
				    <div class="theme-actions">
				    <?php if($canSwitch ):?>
					<?php if ( $theme['active'] ): ?>
					    <a href="<?php echo $theme['actions']['deactivate']; ?>" class="button button-secondary"><?php _e( 'Deactivate', 'themify' ); ?></a>
					<?php else:?>
					    <a class="button button-secondary" href="<?php echo $theme['actions']['activate']; ?>"><?php _e( 'Activate', 'themify' ); ?></a>
					<?php endif;?>
				    <?php endif;?>
				    <?php if($canEdit):?>
					<a class="button button-primary tbp_lightbox_edit" href="#" data-post-id="<?php echo $theme['theme_id']; ?>"><?php _e( 'Edit', 'themify' ); ?></a>
				    <?php endif?>
				    </div>
				</div>
				<?php if ( $theme['hasUpdate'] ): ?>
					<div class="theme-update"><?php _e( 'Update Available','themify' ); ?></div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
			<?php if($canInstall):?>
			    <div class="theme add-new-theme">
				<a href="#" class="tbp_lightbox_edit">
				<div class="theme-screenshot"><span></span></div>
				<h3 class="theme-name"><?php _e('Add New Theme','themify')?></h3></a>
			    </div>
			<?php endif;?>
			<br class="clear" />		
		</div>
		<!-- /themes -->
	</div>
	<!-- /theme-browser -->
	<div class="theme-overlay"></div>
	<p class="no-themes"><?php _e( 'No themes found. Try a different search.','themify' ); ?></p>
</div>

<script id="tmpl-tbp-theme-single" type="text/template">
	<div class="theme-backdrop"></div>
	<div class="theme-wrap">
		<div class="theme-header">
			<button class="left dashicons dashicons-no<# if ( !data.prev ) { #> disabled<#}#>"><span class="screen-reader-text"><?php _e( 'Show previous theme','themify' ); ?></span></button>
			<button class="right dashicons dashicons-no<# if ( !data.next ) { #> disabled<#}#>"><span class="screen-reader-text"><?php _e( 'Show next theme','themify' ); ?></span></button>
			<button class="close dashicons dashicons-no"><span class="screen-reader-text"><?php _e( 'Close overlay','themify' ); ?></span></button>
		</div>
		<div class="theme-about">
			<div class="theme-screenshots">
			<# if ( data.screenshot[0] ) { #>
				<div class="screenshot"><img src="{{ data.screenshot[0] }}" alt="{{data.name }}" /></div>
			<# } else { #>
				<div class="screenshot blank"></div>
			<# } #>
			</div>

			<div class="theme-info">
				<# if ( data.active ) { #>
					<span class="current-label"><?php _e( 'Current Theme','themify' ); ?></span>
				<# } #>
				<h3 class="theme-name">{{{ data.name }}}<span class="theme-version"><?php printf( __( 'Version: %s','themify' ), '{{ data.version }}' ); ?></span></h3>
				<h4 class="theme-author"><?php printf( __( 'By %s','themify' ), '{{{ data.authorAndUri }}}' ); ?></h4>

				<# if ( data.hasUpdate ) { #>
				<div class="theme-update-message">
					<h4 class="theme-update"><?php _e( 'Update Available','themify' ); ?></h4>
					{{{ data.update }}}
				</div>
				<# } #>
				<p class="theme-description">{{{ data.description }}}</p>
			</div>
		</div>

		<div class="theme-actions">
			<?php if($canSwitch ):?>
			     <# if (!data.active && data.actions.activate ) { #>
				     <a href="{{{ data.actions.activate }}}" class="button button-secondary"><?php _e( 'Activate','themify'); ?></a>
			     <# } #>
			 <?php endif;?>
			<?php if($canEdit ):?>
			    <a class="button button-primary tbp_lightbox_edit" href="#" data-post-id="{{{ data.theme_id }}}"><?php _e( 'Edit', 'themify' ); ?></a>
			    <# if ( data.actions.export ) { #>
				    <a href="{{{ data.actions.export }}}" class="button button-secondary"><?php _e( 'Export', 'themify' ); ?></a>
			    <# } #>
			    <a class="button button-secondary tbp_lightbox_duplicate" href="#" data-post-id="{{{ data.theme_id }}}"><?php _e( 'Duplicate', 'themify' ); ?></a>
			<?php endif;?>
			<?php if($canSwitch ):?>
			    <# if ( data.active ) { #>
				    <a href="{{{ data.actions.deactivate }}}" class="button button-secondary"><?php _e( 'Deactivate', 'themify' ); ?></a>
			    <# } #>
			<?php endif;?>
			<?php if($canDelete):?>
			    <# if ( ! data.active && data.actions['delete'] ) { #>
				    <a href="{{{ data.actions['delete'] }}}" class="button button-secondary delete-theme"><?php _e( 'Delete','themify'); ?></a>
			    <# } #>
			<?php endif;?>
			<a class="button button-secondary" href="<?php echo add_query_arg( 'tbp_erase_demo', 1 ); ?>"><?php _e( 'Erase Demo', 'themify' ); ?></a>
		</div>
	</div>
</script>
