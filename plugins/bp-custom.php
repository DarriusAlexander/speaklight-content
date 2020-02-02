<?php


function bpcodex_rename_profile_tabs() {
  
    buddypress()->members->nav->edit_nav( array( 'Activity' => __( 'Story', 'textdomain' ) ), 'activity' );

}
add_action( 'bp_actions', 'bpcodex_rename_profile_tabs' );