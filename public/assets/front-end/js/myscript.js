$(document).ready(function () {
    $(".left-promo").slick({
        dots: true,
        infinite: false,
        speed: 1000,
        autoplay: true,
        autoplaySpeed: 20000,
        fade: true,
        cssEase: "linear",
        arrows: false,
        centerMode: false, // best
        centerPadding: "0px",
    });

    $(".right-promo").slick({
        dots: true,
        infinite: true,
        speed: 1000,
        autoplay: true,
        autoplaySpeed: 20000,
        fade: true,
        cssEase: "linear",
        arrows: false,
        centerMode: false, // best
        centerPadding: "0px",
    });
});
