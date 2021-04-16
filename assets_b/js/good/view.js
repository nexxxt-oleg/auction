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

  $('#offer-blitz').click(() => {
    const btn = $(this)
    Swal.fire({
      title: 'Вы уверены?',
      text: 'Вы собираетесь предложить блитц цену!',
      icon: 'warning',
      cancelButtonText: 'Отмена',
      showCancelButton: true,
      confirmButtonColor: '#518145',
      cancelButtonColor: '#9a9a9a',
      confirmButtonText: 'Да'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/basket/blitz',
          type: 'post',
          data: {
            goodId: $('#good_id').val()
          },
          beforeSend: function () { btn.prop('disabled', true) },
          success: function (outMsg) {
            if (outMsg.status == true) {
              $('#curr_price').text(outMsg.bidVal)
              $('#price-name').text('Текущая цена:')
              $('.action-button__link--basket span').text(outMsg.data.countCart)
              toastr.success(outMsg.msg, null, { onHidden: () => { window.location.reload() } })
            } else {
              toastr.error(outMsg.msgError)
            }
          },
          error: function (xhr, textStatus, e) {
            toastr.error(textStatus)
          },
          complete: function () {btn.prop('disabled', false)}
        })
      }
    })
  });

    $('#offer-price').click(() => {
        const btn = $(this);
        const bidVal = $('#bid-value').val()
        const bidMsg = resolveBidMsg(bidVal)
        Swal.fire({
            //title: 'Вы уверены?',
            text: bidMsg.msg,
            icon: 'warning',
            cancelButtonText: 'Отмена',
            showCancelButton: true,
            confirmButtonColor: '#518145',
            cancelButtonColor: '#9a9a9a',
            confirmButtonText: bidMsg.confirm
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/basket/offer',
                    type: 'post',
                    data: {
                        // bidValue: bidValue,
                        goodId: $('#good_id').val(),
                        price: bidVal
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
            }
        })
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

  function resolveBidMsg (bidVal) {
    const commissionBid = Math.round(bidVal * 1.15)
    const bidMsg = JSON.parse($('#bid-msg').val())
    let out = { msg: '', confirm: 'Сделать ставку' }
    if (!bidMsg.maxBid) {
      out.msg = `Вы хотите сделать стартовую ставку ${bidVal} ${bidMsg.currency} и начать торги по этому лоту. Итого: ${commissionBid} ${bidMsg.currency}, включая комиссию аукциона 15%`
    } else if (bidVal >= bidMsg.blitz) {
      out.msg = `Ваша ставка соответствует Блитц цене и будет победной на торгах по этому лоту.  Итого: ${commissionBid} ${bidMsg.currency}, включая комиссию аукциона 15%`
      out.confirm = 'Купить'
    } else if (isItStepBid(bidVal)) {
      out.msg = `Вы хотите сделать ставку ${bidVal} ${bidMsg.currency}. Итого: ${commissionBid} ${bidMsg.currency}, включая комиссию аукциона 15%`
    } else {
      out.msg = `Вы хотите установить предельную ставку ${bidVal} ${bidMsg.currency} по этому лоту. Все ставки не достигшие Вашего предела будут перебиты Вами автоматически. Итого, в случае достижения предельной ставки: ${commissionBid} ${bidMsg.currency}, включая комиссию аукциона 15%`
    }
    return out
  }

  function isItStepBid (bidVal) {
    return bidVal == startBid + step || bidVal == startBid
  }
})
