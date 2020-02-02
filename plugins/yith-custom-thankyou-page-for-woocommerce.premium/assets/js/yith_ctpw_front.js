/**
 * Yith Custom Thank You Page for Woocommerce Frontend Script
 *
 * @category Script
 * @package  Yith Custom Thank You Page for Woocommerce
 * @author    Armando Liccardo
 * @license  http://www.gnu.org/licenses/gpl-3.0.txt GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * @link http://www.yithemes.com
 */
jQuery.noConflict();
jQuery( document ).ready(function( $ ) {
    /* create PDF and download on the fly */
    $('#yith_ctwp_pdf_button').on('click', function() {
        $.ajax({
            type: "POST",
            url: yith_ctpw_ajax.ajaxurl,
            data: { "action": 'yith_ctpw_get_pdf', "order_id": yith_ctpw_ajax.order_id },
            dataType: 'json',
            success: function(result, textStatus, jqXHR) {
                if ( result['status'] && result['file'] != '' ) {
                    //create the pdf iframe to start the download
                    $('<iframe id="pdf_creator_ctpw" src="' + yith_ctpw_ajax.pdf_creator + '?pdf='+ result['file'] + '&secure_check=yctpw_sec_check' + '"></iframe>').appendTo('body').hide();
                    //remove it from the page
                    setTimeout(function(){
                        $('#pdf_creator_ctpw').remove();
                    }, 500);
                }
            },
            error: function(jqXHR) {
                console.log('yith_ctpw_pdf_creation_error');
            }
        });



    });

}); //end document ready
