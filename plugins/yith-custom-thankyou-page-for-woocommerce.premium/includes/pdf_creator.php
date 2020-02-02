<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/* check for security check and action*/
if ( isset($_GET['secure_check']) && $_GET['secure_check'] == 'yctpw_sec_check' && isset($_GET['pdf'])) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="yctpw.pdf"');
    if ( strpos($_GET['pdf'],'.pdf') ) {
        echo file_get_contents($_GET['pdf']);
        unlink($_GET['pdf']);
    }
}