<?php
$isActive = isset($args['mod_name']) && (Tbp_Utils::$isActive===true || Themify_Builder::$frontedit_active===true);
$isLoop=$ThemifyBuilder->in_the_loop===true;
$ThemifyBuilder->in_the_loop = true;
if(isset($args['description']) && $args['description']==='short'){
    if($isActive==true){
	ob_start();
    }
    woocommerce_template_single_excerpt();
    if($isActive==true){
	$content = ob_get_contents();
	ob_end_flush();
	echo $content;
    }
}
else{
    ?>
    <div class="product-description">
	<?php 
	if($isActive==true){
	    ob_start();
	}
	the_content();
	if($isActive==true){
	    $content = ob_get_contents();
	    ob_end_flush();
	    echo $content;
	}
	?>
    </div>
<?php
}
$ThemifyBuilder->in_the_loop = $isLoop;
if($isActive==true && empty($content)){
?>
    <div class="tbp_empty_module">
	<?php echo Themify_Builder_Model::get_module_name($args['mod_name']);?>
    </div>
<?php
}
$args=null;