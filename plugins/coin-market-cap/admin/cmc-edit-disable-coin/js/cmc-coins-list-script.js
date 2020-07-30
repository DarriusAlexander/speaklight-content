jQuery(document).ready(function($) {

    $(".cmc-add-button").on("click", function(e) {
        $(this).text('Wait...');
        var edit_coin_nonce = $(this).data('edit_coin_nonce');
        var coin_id = $(this).data('coin-id');
        var coin_name = $(this).data('coin-name');
        var ajax_url = $(this).data('ajax-url');
        var send_data = {
            'action': 'edit_coin_to_list',
            'coin_id': coin_id,
            'coin_name': coin_name,
            'edit_coin_nonce': edit_coin_nonce,
        };
        $.ajax({
            type: 'POST',
            url: ajax_url,
            data: send_data,
            success: function(response) {
                var rs = JSON.parse(response);
                if (rs.status == "success") {
                    window.location = rs.url;
                } else {
                    console.log('error', rs);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
        return false;
    });

    function check_btn_status(El) {
        let btn_status = $(El).children('.coin-disable-checkbox').attr('data-btn-action');
        switch (btn_status) {
            case 'enable':
                $(El).parents('tr').addClass('coin-disabled');
                $(El).children('input.coin-disable-checkbox').attr("checked", "checked");
                break;
            case 'disable':
                $(El).parents('tr').removeClass('coin-disabled');
                $(El).children('input.coin-disable-checkbox').removeAttr("checked");
            default:
                $(this).addClass('enable');
                $(El).children('input.coin-disable-checkbox').removeAttr("checked");
        }
    }

    // Disable coin
    $(".cmc-disable-button").each(function(index) {

        check_btn_status(this);

        $(this).on("click", function(e) {

            let Btn_action = $(this).children('.coin-disable-checkbox').attr('data-btn-action');

            var disable_coin_nonce = $(this).data('disable_coin_nonce');
            //$(this).text() === 'Disable' ? $(this).text('Enable') : $(this).text('Disable');

            if ($(this).hasClass('disable')) {
                $(this).removeClass('disable');
                $(this).addClass('enable');
                $(this).children('.coin-disable-checkbox').attr('data-btn-action', 'enable');
            } else if ($(this).hasClass('enable')) {
                $(this).removeClass('enable');
                $(this).addClass('disable');
                $(this).children('.coin-disable-checkbox').attr('data-btn-action', 'disable');
            }
            check_btn_status(this);
            var coin_id = $(this).data('coin-id');
            var coin_name = $(this).data('coin-name');
            var ajax_url = $(this).data('ajax-url');
            var send_data = {
                'action': 'disable_coin_from_mainlist',
                'coin_id': coin_id,
                'coin_name': coin_name,
                'btn_action': Btn_action.toLowerCase(),
                'disable_coin_nonce': disable_coin_nonce,
            };
            $.ajax({
                type: 'POST',
                url: ajax_url,
                data: send_data,
                success: function(response) {

                },
                error: function(error) {
                    console.log(error);
                }
            });
            return false;
        });

    });
});