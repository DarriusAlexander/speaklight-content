jQuery(document).ready(function($) {

    $(".celp-add-button").on("click", function(e) {
        $(this).text('Wait...');
        var edit_exec_nonce = $(this).data('edit-exec-nonce');
        var ex_id = $(this).data('ex-id');
        var ex_name = $(this).data('ex-name');
        var ajax_url = $(this).data('ajax-url');
        var send_data = {
            'action': 'celp_edit_ex_to_list',
            'ex_id': ex_id,
            'ex_name': ex_name,
            'edit_exec_nonce': edit_exec_nonce,
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
        let btn_status = $(El).children('.celp-disable-checkbox').attr('data-btn-action');
        switch (btn_status) {
            case 'enable':
                $(El).parents('tr').addClass('celp-disabled');
                $(El).children('input.celp-disable-checkbox').attr("checked", "checked");
                break;
            case 'disable':
                $(El).parents('tr').removeClass('celp-disabled');
                $(El).children('input.celp-disable-checkbox').removeAttr("checked");
            default:
                $(this).addClass('enable');
                $(El).children('input.celp-disable-checkbox').removeAttr("checked");
        }
    }

    // Disable Exchange
    $(".celp-disable-button").each(function(index) {

        check_btn_status(this);

        $(this).on("click", function(e) {

            let Btn_action = $(this).children('.celp-disable-checkbox').attr('data-btn-action');

            var disable_ex_nonce = $(this).data('disable-ex-nounce');
            //$(this).text() === 'Disable' ? $(this).text('Enable') : $(this).text('Disable');

            if ($(this).hasClass('disable')) {
                $(this).removeClass('disable');
                $(this).addClass('enable');
                $(this).children('.celp-disable-checkbox').attr('data-btn-action', 'enable');
            } else if ($(this).hasClass('enable')) {
                $(this).removeClass('enable');
                $(this).addClass('disable');
                $(this).children('.celp-disable-checkbox').attr('data-btn-action', 'disable');
            }
            check_btn_status(this);
            var ex_id = $(this).data('ex-id');
            var ajax_url = $(this).data('ajax-url');
            var send_data = {
                'action': 'celp_disable_ex_from_mainlist',
                'ex_id': ex_id,
                'btn_action': Btn_action.toLowerCase(),
                'celp_disable_ex_nonce': disable_ex_nonce,
            };
            $.ajax({
                type: 'POST',
                url: ajax_url,
                data: send_data,
                success: function(res) {},
                error: function(error) {
                    console.log(error);
                }
            });
            return false;
        });

    });
});