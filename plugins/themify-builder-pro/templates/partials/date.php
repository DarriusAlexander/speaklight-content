<?php if (!empty($args['val']['icon'])): ?>
    <span class="ti <?php echo $args['val']['icon']; ?>"></span>
<?php endif; ?>
<?php
$args['val'] = empty($args['val']) ? array() : $args['val'];
$format =Tbp_Utils::getDateFormat($args['val']);
$isDate=$args['type']!=='time';
$args=null;
/* default format value */
if ( empty( $format ) ) {
    $format = $isDate===true?get_option('date_format'):get_option('time_format');
}
$time = get_the_time('c');
$format=str_split( $format );
?>
<time itemprop="datePublished" content="<?php echo $time?>" class="entry-date updated" datetime="<?php echo $time; ?>">
    <?php foreach ( $format as $format_character ):?>
	<?php if ( in_array( $format_character, array( 'd', 'D', 'j', 'l', 'N', 'S', 'w', 'z' ),true ) ):?>
	    <span class="tbp_post_day"><?php the_time( $format_character )?></span>
	<?php elseif ( $format_character==='w'):?>
	    <span class="tbp_post_week"><?php the_time( $format_character )?></span>
	<?php  elseif ( in_array( $format_character, array( 'F', 'm', 'M', 'n', 't' ),true ) ):?>
	    <span class="tbp_post_month"><?php the_time( $format_character )?></span>
	<?php  elseif ( in_array( $format_character, array( 'L', 'o', 'Y', 'y' ),true) ):?>
	    <span class="tbp_post_year"><?php the_time( $format_character )?></span>
	<?php  elseif ( in_array( $format_character, array( 'a', 'A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u', 'v' ),true ) ):?>
	    <span class="tbp_post_time"><?php the_time( $format_character )?></span>
	<?php  elseif ( in_array( $format_character, array( 'e', 'I', 'O', 'P', 'T', 'Z' ),true ) ):?>
	    <span class="tbp_post_timezone"><?php the_time( $format_character )?></span>
	<?php  elseif ( in_array( $format_character, array( 'c', 'r', 'U' ),true ) ):?>
	    <span class="tbp_post_datetime"><?php the_time( $format_character )?></span>
       <?php else:?>
	    <?php echo $format_character;?>
	<?php endif;?>
    <?php endforeach;?>
    <?php if ( $isDate === true ) : ?>
	    <meta itemprop="dateModified" content="<?php echo get_the_modified_time('c') ?>">
    <?php endif; ?>
</time>
