$(function() {
    var ci = $('.category-index');

    ci.on('change', '.au-list', function() {
        var catId = $(this).data('id');
        var arAuction = [];
        $(this).find('option:selected').each(function (i, selected) {
           arAuction[i] = $(selected).val();
        });
        if (catId && arAuction.length>0) {
            $.ajax({
                url: '/admincategory/link_auction',
                data: {
                    cat_id:catId,
                    ar_auction:arAuction
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
