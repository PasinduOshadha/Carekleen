$=jQuery,$(document).ready((function(){$(".owl-carousel").owlCarousel({items:4,margin:33,loop:!0,autoplay:!0,autoplayTimeout:3500,responsive:{0:{items:1},480:{items:1},768:{items:2},1024:{items:3},1139:{items:4}}}),$(".header-button, .mobile-header-button").click((function(e){e.preventDefault(),$(".popup-wrapper").show(),$("body").css("overflow","hidden")})),$(".popup-overlay, .popup-close-btn").click((function(e){e.preventDefault(),$(".popup-wrapper").hide(),$("body").css("overflow","inherit")}))}));
//# sourceMappingURL=bundle.js.map