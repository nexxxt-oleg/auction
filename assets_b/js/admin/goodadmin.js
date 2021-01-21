$(function() {
    var gi = $('.good-index');
    gi.on('change', '.au-list', function() {
        var goodId = $(this).data('good-id');
        var auId = $(this).find('option:selected').val();
        if (goodId && auId) {
            $.ajax({
                url: '/admingood/link_auction',
                data: {
                    good_id:goodId,
                    auction_id:auId
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

    gi.on('change', '.cat-list', function() {
        var goodId = $(this).data('good-id');
        var catId = $(this).find('option:selected').val();
        if (goodId && catId) {
            $.ajax({
                url: '/admingood/link_category',
                data: {
                    good_id:goodId,
                    category_id:catId
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

    gi.on('change', '.sell-rule', function() {
        var goodId = $(this).data('good-id');
        var ruleId = $(this).find('option:selected').val();
        if (goodId && ruleId) {
            $.ajax({
                url: '/admingood/update_sellrule',
                data: {
                    good_id:goodId,
                    rule_id:ruleId
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

    gi.on('change', '.filter-list', function() {
        var goodId = $(this).data('good-id');
        var arFilter = [];
        $(this).find('option:selected').each(function (i, selected) {
           arFilter[i] = $(selected).val();
        });
        var filterId = $(this).find('option:selected').val();
        if (goodId && filterId) {
            $.ajax({
                url: '/admingood/link_filter',
                data: {
                    good_id:goodId,
                    ar_filter:arFilter
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
