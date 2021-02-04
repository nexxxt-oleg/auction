/**
 * Created by afilatov on 18.07.2016.
 */
$(function() {
    const step =  Number.parseInt($('#bid-step').val())
    const startBid = Number.parseInt($('#bid-value').val())
    // $('#make-bid').click(function() {
    //     // var bidValue = $('.lot-content__form-input').val();
    //     var btn = $(this);
    //     $.ajax({
    //         url: '/basket/bid',
    //         type: 'post',
    //         data: {
    //             // bidValue: bidValue,
    //             goodId: $('#good_id').val()
    //         },
    //         beforeSend: function() {btn.prop('disabled', true);},
    //         success: function(outMsg){
    //             if (outMsg.status == true) {
    //                 $('#curr_price').text(outMsg.bidVal);
    //                 $('#price-name').text('Текущая цена:');
    //                 $('.action-button__link--basket span').text(outMsg.data.countCart);
    //                 toastr.success(outMsg.msg, null, { onHidden: () => { window.location.reload() }});
    //             } else {
    //                 toastr.error(outMsg.msgError);
    //             }
    //
    //         },
    //         error: function (xhr, textStatus, e) {
    //             toastr.error(textStatus);
    //         },
    //         complete: function() {btn.prop('disabled', false);}
    //     });
    // });
    $('#bid-value').select2({
        tags: true,
        width: '100%'
    });
    $(document).on('keypress', '.select2-search__field', function () {
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    $('#offer-price').click(() => {
        var btn = $(this);
        $.ajax({
            url: '/basket/offer',
            type: 'post',
            data: {
                // bidValue: bidValue,
                goodId: $('#good_id').val(),
                price: $('#bid-value').val()
            },
            beforeSend: function() { btn.prop('disabled', true); },
            success: function(outMsg) {
                if (outMsg.status == true) {
                    $('#curr_price').text(outMsg.bidVal);
                    $('#price-name').text('Текущая цена:');
                    $('.action-button__link--basket span').text(outMsg.data.countCart);
                    toastr.success(outMsg.msg, null, { onHidden: () => { window.location.reload() }});
                } else {
                    toastr.error(outMsg.msgError);
                }

            },
            error: function (xhr, textStatus, e) {
                toastr.error(textStatus);
            },
            complete: function() {btn.prop('disabled', false);}
        });
    })

    $('#bid-up').click(() => {
        let curVal = Number.parseInt($('#bid-value').find(':selected').val())
        curVal += step
        createIfNotExists(curVal)
    })
    $('#bid-down').click(() => {
        let curVal = $('#bid-value').find(':selected').val()
        curVal -= step
        if (curVal < startBid) {
            toastr.error('Ставка не может быть меньше');
            return
        }
        createIfNotExists(curVal)
    })

    function createIfNotExists (val) {
        if ($('#bid-value').find("option[value='" + val + "']").length) {
            $('#bid-value').val(val).trigger('change');
        } else {
            const newOption = new Option(val, val, true, true);
            $('#bid-value').append(newOption).trigger('change');
        }
    }
});