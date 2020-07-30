<?php
   
	
/**For exchange description settings**/
	
	/**
     * Initiate the metabox
     */
    $cmbdes = new_cmb2_box( array(
        'id'            => 'celp_exchange_des',
        'title'         => __( 'Exchange Description', 'cmb2' ),
        'object_types'  => array( 'celp'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );
	

	
$cmbdes->add_field( array(
    'name'    => __('Select Exchange', 'celp'),
    'desc'    => '',
    'id'      => 'custom_ex_id',
    'type'    => 'select',
     'default' => '',
    'options' => celp_get_exchange_id_api_data(),
	'column' => array(
		'position' => 2,
		'name'     => __('Exchange Name', 'celp'),
	),
	
    ) );

	
    $cmbdes->add_field( array(
        'name' => __( 'Description', 'celp' ),
        'id' =>'custom_description',
        'type' => 'wysiwyg',
    ) );

    $cmbdes->add_field( array(
        'name' => __( 'Affiliate Links', 'celp' ),
        'id' =>'affiliate_link',
        'type' => 'text_url',
		'column' => array(
		'position' => 3,
		'name'     => __('Affiliate Links', 'celp'),
	),
    ) );

function celp_cmb2_set_checkbox_default( $default ) 
{
return isset( $_GET['post'] ) ? '' : ( $default ? (string) $default : '' );
}


	