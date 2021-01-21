$(function() {
    var ui = $('.user-index');

    ui.on('change', '.active-status', function() {
        var userId = $(this).data('user-id');
        var activeId = $(this).find('option:selected').val();
        if (userId && activeId) {
            $.ajax({
                url: '/admin/user/update_active',
                data: {
                    user_id: userId,
                    active_id: activeId
                },
                success: function(data){
                    if (data.status == true) {
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msgError);
                    }
                },
                error: function (xhr, textStatus, e) {
                    toastr.error(xhr.responseText);
                }
            });
        }

    });

    ui.on('click', '.reset-password', function() {
        var userId = $(this).data('user-id');
        if (userId) {
            $.ajax({
                url: '/admin/user/reset_password',
                data: {
                    user_id: userId
                },
                success: function(data){
                    if (data.status == true) {
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msgError);
                    }
                },
                error: function (xhr, textStatus, e) {
                    toastr.error(xhr.responseText);
                }
            });
        }

    });
});
