/**
 * Created by afilatov on 18.07.2016.
 */
$(function() {
    $('.lot-content__form-button').click(function() {
        // var bidValue = $('.lot-content__form-input').val();
        var btn = $(this);
        $.ajax({
            url: '/basket/bid',
            type: 'post',
            data: {
                // bidValue: bidValue,
                goodId: $('#good_id').val()
            },
            beforeSend: function() {btn.prop('disabled', true);},
            success: function(outMsg){
                if (outMsg.status == true) {
                    $('#curr_price').text(outMsg.bidVal);
                    $('#price-name').text('Текущая цена:');
                    $('.action-button__link--basket span').text(outMsg.data.countCart);
                    toastr.success(outMsg.msg);
                } else {
                    toastr.error(outMsg.msgError);
                }

            },
            error: function (xhr, textStatus, e) {
                toastr.error(textStatus);
            }
            ,complete: function() {btn.prop('disabled', false);}
        });
    });


});