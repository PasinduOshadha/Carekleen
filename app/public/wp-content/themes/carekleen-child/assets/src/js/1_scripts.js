$ = jQuery;
$(document).ready(function () {
    $(".owl-carousel").owlCarousel({
        items: 4,
        margin: 33,
        loop: true,
        autoplay: true,
        autoplayTimeout: 3500,
        responsive: {
            0: {
                items: 1,
            },
            480: {
                items: 1,
            },
            768: {
                items: 2,
            },
            1024: {
                items: 3,
            },
            1139: {
                items: 4,
            }
        }
    });
    
    $('.header-button, .mobile-header-button').click(function (e) { 
        e.preventDefault();
        
        $('.popup-wrapper').show();
        $('body').css('overflow', 'hidden');
    });
    
    $('.popup-overlay, .popup-close-btn').click(function (e) { 
        e.preventDefault();

        if($(document).find('#gform_3').length == 0){
            window.location.reload()
        }
        
        $('.popup-wrapper').fadeOut(200);
        $('.popup-container').fadeOut(50);
        $('body').css('overflow', 'inherit');
    });
    

});