/**
 * Created by afilatov on 18.07.2016.
 */
$(function() {
    /* filter form START */
    $('form#form-filter input').on('change', function(){
        // todo: wait a little before doing request. Especially for slider.
        //$.ias().destroy();
        formAjaxRequest();
    });

    // this event makes sure that the back/forward buttons work as well
    /* todo: disable it, because there is a bug with onpopstate event in /site/faq
    window.onpopstate = function(event) {
        ajax_loading_screen($('.auction-list'));
        console.log("pathname: "+location.href);
        location.href = location.href;
    };*/
    /* filter form END */

    window.popupCallbacks = {
        open: function() {
            var previewModal = $('#preview-modal');
            var id = this.items[this.index].el.data('id');
            if (previewModal.length > 0 && id) {
                $.ajax({
                    url: '/good/preview_modal?id='+id,
                    beforeSend: function() {ajax_loading_screen($('#preview-modal'));},
                    success: function(data){
                        previewModal.html(data);
                        window.sync.init();
                        var modalContent = previewModal.find('.popup-modal');
                        if (modalContent) {
                            modalContent.magnificPopup({
                                preloader: false,
                                modal: true,
                                callbacks: window.popupCallbacks
                            });
                        }
                        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent) == false) {
                            $(".item--magnifier").magnifierRentgen();
                        }

                    },
                    error: function (xhr, textStatus, e) {
                        alert(textStatus);
                    }
                });
            }


            $.fn.fullpage.setAllowScrolling(false);
        },
        close: function() {
            $.fn.fullpage.setAllowScrolling(true);
        }
    };
    $('.popup-modal-item').magnificPopup({
        preloader: false,
        modal: true,
        callbacks: window.popupCallbacks
    });

});



function formAjaxRequest() {
    var form = $('#form-filter');
    var url = $('#clearUrl').text();
    var formDataObject = form.serializeArray();
    formDataObject.push({name:"antiCache", value : Math.random()});
    var formData = $.param(formDataObject);
    $.ajax({
        url: url,
        data: formData,
        beforeSend: function() {ajax_loading_screen($('.auction-list'));},
        success: function(data){
            $('.auction-list').html(data);
            var currUrl = url + (url.indexOf("?") == -1 ? "?" : "&")  + form.serialize();
            window.history.pushState('', '', currUrl);
            $('.popup-modal-item').magnificPopup({
                preloader: false,
                modal: true,
                callbacks: window.popupCallbacks
            });
            jQuery.ias().reinitialize();
            $('.auction-item__countdown time').countDown();
        },
        error: function (xhr, textStatus, e) {
            alert(textStatus);
        },
        //,complete: function() {window.scrollTo(0, 0);}
    });
}