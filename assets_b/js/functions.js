/**
 * Created by afilatov on 18.07.2016.
 */
function ajax_loading_screen(el) {
    el.find("#grid-loading").remove();
    el.prepend('<div id="grid-loading"><div id="icon-cont"><i class="fa fa-circle-o-notch fa-spin fa-5x"></i></div></div>');
    el.find('#icon-cont').css({
        'position' : 'absolute',
        'left' : '50%',
        'top' : '50%',
        'z-index':'9999'
        //'margin-left' : -$('#grid-loading').outerWidth()/2,
        //'margin-top' : -$('#grid-loading').outerHeight()/2
    });

    el.find('#grid-loading').css({
        'z-index':'9990',
        'position':'absolute',
        'height':el.outerHeight(),
        'width':'100%',
        'background-color':'#fff',
        'opacity':'0.7'
    });
}