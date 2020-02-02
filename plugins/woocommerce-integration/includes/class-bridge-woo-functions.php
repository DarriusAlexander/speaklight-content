<?php

namespace NmBridgeWoocommerce;

/**
 * File used to define the commonly used functions all over the woocommerce Integration plugin.
 */




/**
 * Functionality to check if the Woocommerce Membership plugin is activated.
 * @return
 */
function checkWoocommerceMembershipIsActive()
{
    $activatedPlugins = apply_filters('active_plugins', get_option('active_plugins'));

    if (in_array('woocommerce-memberships/woocommerce-memberships.php', $activatedPlugins)) {
        return true;
    }
    return false;
}


/**
 * returns wordpress course ids which are associated to the product Id
 * @param  [type] $productId [description]
 * @return [type]            [description]
 */
function getWpCoursesFromProductId($productId)
{
    $productMeta = get_post_meta($productId, "product_options", 1);
    $associatedCourses = $productMeta["moodle_post_course_id"];
    return $associatedCourses;
}


/**
 * returns wordpress course ids which are associated to the product Id
 * @param  [type] $productId [description]
 * @return [type]            [description]
 */
function getMdlCoursesFromProductId($productId)
{
    $productMeta = get_post_meta($productId, "product_options", 1);
    $associatedCourses = $productMeta["moodle_course_id"];
    $associatedCourses = explode(",", $associatedCourses);
    return $associatedCourses;
}
