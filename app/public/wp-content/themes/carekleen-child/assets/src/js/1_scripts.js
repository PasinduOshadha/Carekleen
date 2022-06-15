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
});