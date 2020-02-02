/**
 * Created by Vanush on 19.07.2017.
 */
//login
var wordsAnimate = 0;
var playVideo = 0;

function tenwebLogin() {
    var email = jQuery("#tenweb_email");
    var pass = jQuery("#password");
    var data = {};
    data.action = 'tenweb_dashboard_login';
    data.email = email.val();
    data.password = pass.val();
    data.type = 'login_user';
    data.tenweb_nonce = jQuery('#tenweb_login_nonce').val();

    if (tenwebIsValid([email, pass])) {
        jQuery("#error_response").hide();
        jQuery("#button_login span.spinner").css({"visibility": "visible", "display": "inline-block"});
        jQuery.ajax({
            type: "POST",
            //  dataType: 'json',
            url: tenweb.ajaxurl,
            data: data,
            success: function (response) {
                var response = JSON.parse(response);
                if (typeof response.error != "undefined") {
                    var errorMessage = response.message == "Subscription could not be found." ? "Your subscription is expired. <a class='db-link' href='http://my.10web.io/account/subscription-plan' target='_blank'>Renew subscription or choose another plan.</a>" : response.message;
                    jQuery("#error_response").html(errorMessage).show();
                    jQuery("#button_login span.spinner").css({"visibility": "hidden", "display": "none"});
                } else {
                    location.reload();
                }
            },
            failure: function (errorMsg) {
                console.log('Failure' + errorMsg);
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
}

function tenwebCachePurge() {

    var data = {};
    data.action = 'tenweb_cache_purge_all';

        jQuery("#error_response").hide();
        jQuery.ajax({
            type: "POST",
            //  dataType: 'json',
            url: tenweb.ajaxurl,
            data: data,
            success: function (response) {
                var response = JSON.parse(response);
                if (typeof response.error != "undefined") {

                    jQuery('#tenweb_cache_message').removeClass('hidden').addClass('error').html('<p>'+response.error+'</p>')
                } else {
                    jQuery('#tenweb_cache_message').removeClass('hidden').addClass('success').html('<p>'+response.message+'</p>')
                }
            },
            failure: function (errorMsg) {
                console.log('Failure' + errorMsg);
            },
            error: function (error) {
                console.log(error);
            }
        });
}

function scrollFunction() {
    if (jQuery(".animate-words").length && wordsAnimate === 0) {
        jQuery(".animate-words").addClass("animate");
        jQuery("#manager_header,#manager_section_1").addClass("animate");
        setTimeout(function () {
            jQuery("#manager_section_1").addClass("animated");
        }, 800);
        wordsAnimate++;
    }
}


function tenwebIsValid(elem) {
    var hasError = 0;
    jQuery(elem).each(function (i, el) {
        var inValidEmail = false;
        if (el.prop("id") == "tenweb_email" && !tenwebIsValidEmail(el.val())) {
            inValidEmail = true;
        }
        if (el.val().trim() == "" || inValidEmail) {
            el.addClass("error_input");
            el.next(".error_label").show();
            hasError++;
        } else {
            el.removeClass("error_input");
            el.next(".error_label").hide();
        }
    });

    if (!hasError)
        return true;
    return false;
}


function tenwebIsValidEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

jQuery(window).scroll(function () {
    scrollFunction();
});
jQuery(document).ready(function () {
    scrollFunction();

    jQuery("#twebman-login-form input").blur(function () {
        tenwebIsValid([jQuery(this)]);
    });
    jQuery("#twebman-login-form input").keyup(function () {
        tenwebIsValid([jQuery(this)]);
    });

    if (jQuery("#twebman-login-form").length) {
        jQuery(document).keypress(function (e) {
            if (e.which == 13) {
                tenwebLogin();
                return false;
            }
        });
    }

    //debug mode
    jQuery('#tenweb_clear_logs').on('click', function () {
        jQuery.ajax({
            type: "POST",
            url: tenweb.ajaxurl,
            data: {
                action: "tenweb_clear_logs",
                tenweb_nonce: tenweb.ajaxnonce
            },
            success: function (response) {
                window.location.reload();
            },
            failure: function (errorMsg) {
                window.location.reload();
            },
            error: function (error) {
                window.location.reload();
            }
        });
    });

    jQuery('#tenweb_clear_cache, .tenweb_clear_cache_button').on('click', function () {
        window.tenWebClearCache();
    });

    window.tenWebClearCache = function () {
        jQuery.ajax({
            type: "POST",
            url: tenweb.ajaxurl,
            data: {
                action: "tenweb_clear_cache",
                tenweb_nonce: tenweb.ajaxnonce
            },
            success: function (response) {
                window.location.reload();
            },
            failure: function (errorMsg) {
                window.location.reload();
            },
            error: function (error) {
                window.location.reload();
            }
        });
    };
    jQuery('#tenweb_check_curl').on('click', function () {
        jQuery.ajax({
            type: "POST",
            url: tenweb.ajaxurl,
            data: {
                action: "tenweb_check_curl",
                tenweb_nonce: tenweb.ajaxnonce
            },
            success: function (response) {
                console.log(response);
            },
            failure: function (errorMsg) {
                console.log(response);
            },
            error: function (error) {
                console.log(response);
            }
        });
    });

    jQuery("#self_update").on('click', function () {
        jQuery(this).find(".spinner").css({"display": "inline-block", "visibility": "visible"});
        jQuery.ajax({
            type: "POST",
            url: tenweb.action_endpoint,
            headers: {
                "Authorization": "Bearer " + tenweb.auth_header,
                "Accept": "application/x.10webmanager.v1+json"
            },
            data: {
                domain_id: tenweb.domain_id,
            },
            success: function (response) {
                window.location.reload();
            },
            failure: function (errorMsg) {
                window.location.reload();
            },
            error: function (error) {
                window.location.reload();
            }
        });
    });

    jQuery("#tenweb_manager_products .tm_products_logout").on('click', function () {
        jQuery('#tenweb_manager_logout_form').submit();
    });

    jQuery("#video_container").on('click', function (a) {
        jQuery(this).hide();
        jQuery('.iframe-container iframe').remove();
        jQuery('.iframe-container').html("<div id='iframe-container'></div>");
    });

    /*Show Video*/
    jQuery(".manager_watch_video").on('click', function () {
        var tenWebPluginVideoId = jQuery(this).attr("data-id");
        var tenwebplayer = new YT.Player('iframe-container', {
            height: '675',
            width: '1200',
            videoId: tenWebPluginVideoId,
            playerVars: {
                autoplay: 1,
                modestbranding: 1,
                vq: 'hd2160',
                rel: 0,
                showinfo: 0,
                cc_load_policy: 0,
                iv_load_policy: 3,
            }
        });
        jQuery("#video_container").show();
    });

    /*Image Optimizer*/
    jQuery("#image_optimizer_content").on("mousemove", function (event) {
        v = event.pageX - jQuery(this).offset().left + 1;
        if (v > 20 && v < (jQuery(this).width() - 20)) {
            jQuery("#separator").css("left", v + "px");
            jQuery("#horizon-original").css("clip", "rect(0px " + v + "px 405px 0px)");
        }
    });
    jQuery("#image_optimizer").on("touchmove", function (event) {
        v = event.originalEvent.touches[0].clientX - jQuery(this).offset().left + 1;
        if (v > 20 && v < (jQuery(this).width() - 20)) {
            jQuery("#separator").css("left", v + "px");
            jQuery("#horizon-original").css("clip", "rect(0px " + v + "px 405px 0px)");
        }
    });

    if (matchMedia('screen and (max-width: 1024px)').matches) {
        jQuery("div#tenweb_menu.photo-gallery #speed .floating-img1 img").attr("src", tenweb.plugin_url + "/assets/images/plugins_from/img1_768.png");
        jQuery("div#tenweb_menu.photo-gallery #speed .floating-img2 img").attr("src", tenweb.plugin_url + "/assets/images/plugins_from/img2_768.png");
    }
    if (matchMedia('screen and (max-width: 767px)').matches) {
        jQuery("div#tenweb_menu.photo-gallery #speed .floating-img1 img").attr("src", tenweb.plugin_url + "/assets/images/plugins_from/img1_320.png");
        jQuery("div#tenweb_menu.photo-gallery #speed .floating-img2 img").attr("src", tenweb.plugin_url + "/assets/images/plugins_from/img2_320.png");
    }
});

function tenwebstopCycle(event) {
    if (event.data === 0) {
        setTimeout(function () {
            jQuery("#video_container").fadeOut(800);
            jQuery('.iframe-container iframe').remove();
            jQuery('.iframe-container').html("<div id='iframe-container'></div>");
        }, 1000);

    }
}


