// Carousel 
$(document).ready(function(){
    // Hero
    $('.sliders-container').slick({
        autoplay: true,
        autoplaySpeed: 3000,
        arrows: true,
        nextArrow: '#arrow-right',
        prevArrow: '#arrow-left',
        dots: false,
    });
    // Blog
    $('.blog-carousel-container').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 3,
    nextArrow: '#blog-arrow-right',
    prevArrow: '#blog-arrow-left',
    });
    //Events
    $('.events-carousel-container').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    nextArrow: '#events-arrow-right',
    prevArrow: '#events-arrow-left',
    });
    //Partners
    $('.partners-carousel-container').slick({
        autoplay:true,
        autoplaySpeed: 1000,
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        dots:false,
        arrows:false,
        });
});