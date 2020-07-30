jQuery(document).ready(function($) {
    var choose_affi = $('[name="cmc_single_settings_choose_affiliate_type"]');
    var checked = $('[name="cmc_single_settings_choose_affiliate_type"]:checked').val();
    var changelly_seciton = $("#cmc_single_settings_affiliate_id").parents("tr");
    var other_section = $("#cmc_single_settings_other_affiliate_link").parents("tr");

    changelly_seciton.hide();
    other_section.hide();

    if (checked == "changelly_aff_id") {
        changelly_seciton.show();
    } else if (checked == "any_other_aff_id") {
        other_section.show();
    }
    choose_affi.on('change', function() {
        if ($(this).val() == "changelly_aff_id") {
            changelly_seciton.fadeIn();
            other_section.fadeOut();
        } else {
            changelly_seciton.fadeOut();
            other_section.fadeIn();
        }
    });

    if ($('body.post-type-cmc-description').length > 0) {
        $('[name="cmc_single_settings_des_coin_name"] option').each(function() {
            if ((cmc_description.already_created).includes($(this).val()) && $(this).attr('selected') != 'selected') {
                $(this).attr('disabled', 'disabled');
            }

        })
    }

});