<template id="tmpl-tbp_builder_lightbox">
    <div id="tb_lightbox_parent" class="builder-lightbox">
        <div class="tbp_lightbox_bar clearfix">
	    <div class="tbp_lightbox_title"></div>
	    <div class="ti-close"></div>
        </div>
        <div id="tb_lightbox_container" class="tb_options_tab_wrapper"></div>
	<div class="tbp_lightbox_save tbp_lightbox_bar">
	    <i class="ti-check"></i>
	    <button type="button" class="builder_button builder_button_edit"></button>
	    <div class="tbp_step_2_actions">
		<a href="#" class="tbp_submit_draft_btn"></a>
		<button type="button" class="tbp_btn_save builder_button"></button>
	    </div>
	</div>
    </div>
    <div class="tb_resizable_overlay"></div>
</template>
<template id="tmpl-tbp_pagination">
    <div class="tbp_pagination_header" data-select="<?php _e('Select','themify')?>" data-all="<?php _e('All','themify')?>"><?php _e('All','themify')?></div>
    <div class="tbp_pagination_inner">
	<input type="checkbox" value="all" class="tbp_pagination_all" checked="checked" />
	<label><?php _e('All','themify')?></label>
	<div class="tbp_pagination_search">
	    <span class="ti-search"></span>
	    <input type="search" class="tbp_pagination_search_input"/>
	    <div class="tbp_pagination_result_wrap"></div>
	</div>
    </div>
</template>