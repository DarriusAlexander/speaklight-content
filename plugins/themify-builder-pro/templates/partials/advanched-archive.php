<?php if(!empty($args['builder_content'])):?>
    <div class="tbp_advanchd_archive_wrap">
	<?php
	    $builderId=$args['builder_id'];
	    foreach ($args['builder_content'] as $rows => $row){
		if (!empty($row)) {
		    if (!isset($row['row_order'])) {
			$row['row_order'] = $rows; 
		    }
		    Themify_Builder_Component_Row::template($rows, $row, $builderId, true);
		}
	    }
	    $args=$builderId=null;
	?>
    </div>
<?php endif;?>