$(function () {

    $('.banner_slide').slick({
        dots: true,
        autoplay: false,
        autoplaySpeed: 2500,
        nextArrow: `<span class="next"><iconify-icon icon="mynaui:arrow-right" width="24" height="24"></iconify-icon></span>`,
        prevArrow: `<span class="prev"><iconify-icon icon="mynaui:arrow-left" width="24" height="24"></iconify-icon></span>`
    });


    // products details slider 
    // product_image_slide

    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav',
        // autoplay: 'true',

    });
    $('.slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        centerMode: false,
        focusOnSelect: true,
        vertical: true,
        prevArrow: `<span class="up"><iconify-icon icon="iconamoon:arrow-up-2-light" width="24" height="24"></iconify-icon></span>`,
        nextArrow: `<span class="down"><iconify-icon icon="iconamoon:arrow-down-2-light" width="24" height="24"></iconify-icon></span>`,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: false,
                    vertical: false,
                    arrows: false,
                }
            },
        ]

    });
}); 