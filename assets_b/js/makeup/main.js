$(document).ready(function () {
	"use strict";

	toastr.options.closeButton = true;
	toastr.options.progressBar = true;
	toastr.options.timeOut = 5000;

	// page
	$(function() {
		$('#fullpage').fullpage({
			anchors: ['section-about', 'section-auction', 'section-lots', 'section-contacts'],
			menu: '#section-navigation',
			continuousVertical: true,
			normalScrollElements: '.fixed-search, .navigation-mobile, .basic-modal',
			afterRender: function(){
				var wow = new WOW({mobile:false});
				wow.init();
			}
		});
	});

	// timet
	$('.section__countdown time').countDown();
	$('.auction-item__countdown time').countDown();
	$('.auction-item__starting time').countDown();
	$('.lot-content time').countDown();

	// input mask
	$('.section__form-input-phone').inputmask("+7 (999) 999 99 99");

	// search popup
	$('.user-button__search, .fixed-search__close').click(function() {
		$('.fixed-search').toggleClass('fixed-search--open');
	});

	// mobile navigation
	$('.navigation-mobile-button').click(function() {
		$(this).toggleClass('navigation-mobile-button--open');
		$('.navigation-mobile').toggleClass('navigation-mobile--open');
	});

	//
	$(function () {
		$('.popup-modal').magnificPopup({
			preloader: false,
			modal: true,
			callbacks: {
				open: function() {
					$.fn.fullpage.setAllowScrolling(false);
				},
				close: function() {
					$.fn.fullpage.setAllowScrolling(true);
				}
			}
		});

		$(document).on('click', '.mfp-close, basic-modal__dismiss', function(){
			$.fn.fullpage.addScrollEvent();
		});
		$(document).on('click', '.basic-modal__dismiss', function (e) {
			e.preventDefault();
			$.magnificPopup.close();
		});

		$('.popup-video').magnificPopup({
			disableOn: 700,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false
		});

		//
		$('.price-slider').ionRangeSlider({
			type: "double",
			min: 1000,
			max: 100000,
			from: 16200,
			to: 85000,
			hide_min_max: true
		});

		$('.popup-gallery').magnificPopup({
			delegate: 'a',
			type: 'image',
			tLoading: 'Loading image #%curr%...',
			mainClass: 'mfp-img-mobile',
			gallery: {
				enabled: true,
				navigateByImgClick: true,
				preload: [0,1] // Will preload 0 - before current, and 1 after the current image
			},
		});
	});

	//

	window.sync = {
		init: function() {
			this.sync1 = $("#sync1");
			this.sync2 = $("#sync2");
			var sync1Carousel, sync2Carousel;
			if (sync1Carousel = this.sync1.data('owlCarousel')) {sync1Carousel.destroy();}
			if (sync2Carousel = this.sync2.data('owlCarousel')) {sync2Carousel.destroy();}
			this.sync1.owlCarousel({
				singleItem: true,
				slideSpeed: 1000,
				pagination: false,
				afterAction: this.syncPosition,
				responsiveRefreshRate: 200,
				autoHeight: true,
				transitionStyle: "fade"
			});

			$(".lot-navigation .next").click(function(){
				window.sync.sync1.trigger('owl.next');
			});
			$(".lot-navigation .prev").click(function(){
				window.sync.sync1.trigger('owl.prev');
			});

			this.sync2.owlCarousel({
				items: 4,
				itemsDesktop: [1199,4],
				itemsDesktopSmall: [979,4],
				itemsTablet: [767,3],
				itemsMobile: [479,2],
				pagination: false,
				responsiveRefreshRate: 100,
				afterInit : function(el){
					el.find(".owl-item").eq(0).addClass("synced");
				}
			});

			this.sync2.on("click", ".owl-item", function(e){
				e.preventDefault();
				var number = $(this).data("owlItem");
				window.sync.sync1.trigger("owl.goTo",number);
			});
		},
		sync1: null,
		sync2: null,
		center: function (number){
			var sync2visible = this.sync2.data("owlCarousel").owl.visibleItems;
			var num = number;
			var found = false;
			for(var i in sync2visible){
				if(num === sync2visible[i]){
					found = true;
				}
			}

			if(found===false){
				if(num>sync2visible[sync2visible.length-1]){
					this.sync2.trigger("owl.goTo", num - sync2visible.length+2)
				}else{
					if(num - 1 === -1){
						num = 0;
					}
					this.sync2.trigger("owl.goTo", num);
				}
			} else if(num === sync2visible[sync2visible.length-1]){
				this.sync2.trigger("owl.goTo", sync2visible[1])
			} else if(num === sync2visible[0]){
				this.sync2.trigger("owl.goTo", num-1)
			}
		},
		syncPosition: function(){
			var current = this.currentItem;
			$("#sync2")
					.find(".owl-item")
					.removeClass("synced")
					.eq(current)
					.addClass("synced")
			if($("#sync2").data("owlCarousel") !== undefined){
				window.sync.center(current)
			}
		}
	};
	$(function() {
		// slider
		window.sync.init();


		if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone/i.test(navigator.userAgent) == false) {
			$(".item--magnifier").magnifierRentgen();
		}
	});

	
	// Cache selectors
	var lastId,
	topMenu = $(".cabinet-navigation__item"),
	topMenuHeight = topMenu.outerHeight()+15,
	// All list items
	menuItems = topMenu.find("a"),
	// Anchors corresponding to menu items
	scrollItems = menuItems.map(function(){
		var item = $($(this).attr("href"));
		if (item.length) { return item; }
	});

	// Bind click handler to menu items
	// so we can get a fancy scroll animation
	menuItems.click(function(e){
		var href = $(this).attr("href"),
			offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;
		$('html, body').stop().animate({
			scrollTop: offsetTop
		}, 600);
		e.preventDefault();
	});

	// Bind to scroll
	$(window).scroll(function(){
		// Get container scroll position
		var fromTop = $(this).scrollTop()+topMenuHeight;

		// Get id of current scroll item
		var cur = scrollItems.map(function(){
			if ($(this).offset().top < fromTop)
			return this;
		});
		// Get the id of the current element
		cur = cur[cur.length-1];
		var id = cur && cur.length ? cur[0].id : "";

		if (lastId !== id) {
			lastId = id;
			// Set/remove active class
			menuItems
				.parent().removeClass("active")
				.end().filter("[href='#"+id+"']").parent().addClass("active");
		}
	});

	//
	$(".cabinet-navigation-wrap").stick_in_parent();

	// good list actions
	var body = $('body');

	body.on('click', '.to-favorite a', function(ev) {
		var btn = $(this);
		var action = btn.data('action');
		$.ajax({
			url: '/basket/favorite',
			type: 'post',
			data: {
				goodId: btn.data('good-id'),
				action: action
			},
			beforeSend: function() {btn.prop('disabled', true);},
			success: function(data){
				if (data.status == true) {
					toastr.success(data.msg);
					//alert(data.msg);
					var actionAdd = $('#favorite-action-add').text();
					if (action == actionAdd) {
						btn.data('action', $('#favorite-action-remove').text());
						btn.parent().css('opacity', 1);
					} else {
						btn.data('action', actionAdd);
						btn.parent().css('opacity', 0.5);
					}
				} else {
					toastr.error(data.msgError);
				}

			},
			error: function (xhr, textStatus, e) {
				alert(textStatus);
			}
			,complete: function() {btn.prop('disabled', false);}
		});
		ev.preventDefault();
	});

});


svgeezy.init(false, 'png');