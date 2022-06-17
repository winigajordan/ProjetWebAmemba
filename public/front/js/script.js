// Carousel 
$(document).ready(function() {
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
        responsive: [{
                breakpoint: 760,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                },

            }
        ]
    });
    //Events
    $('.events-carousel-container').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        nextArrow: '#events-arrow-right',
        prevArrow: '#events-arrow-left',
        responsive: [{
                breakpoint: 760,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                },

            }
        ]
    });
    //Partners
    $('.partners-carousel-container').slick({
        autoplay: true,
        autoplaySpeed: 1000,
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        dots: false,
        arrows: false,
        responsive: [{
                breakpoint: 850,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4
                }
            },
            {
                breakpoint: 760,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            }
        ]
    });
    // Realisations 
    $('.realisations-carousel-container').slick({
        autoplay: true,
        autoplaySpeed: 3000,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        dots: false,
        arrows: false,
    });
    // Historique 
    $('.historiques-caroussel-container').slick({
        autoplay: true,
        autoplaySpeed: 3000,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        dots: false,
        arrows: false,
    });
    // Stats Animation
    $('.count').each(function() {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
        }, {
            duration: 4000,
            easing: 'linear',
            step: function(now) {
                $(this).text(Math.ceil(now));
            }
        });
    });
    // Membre Toggle
    $('#show').click(function() {
        $('.menu-membre').toggle("slide");
    });

    // change src image on form submit
    $(function() {
        $('#formFileImage').change(function() {
            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#addArticleImg').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                $('#addArticleImg').attr('src', '/assets/images/nopreview.jpeg');
            }
        });
    });
});