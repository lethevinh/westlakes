(function($) {
    'use strict';

    jQuery(document).on('ready', function() {

        /*PRELOADER JS*/
        $(window).on('load', function() {
            // $('.spinner').fadeOut();
            // $('.preloader').delay(350).fadeOut('slow');
            // $('#modalPopup').modal('show');
            /*setTimeout(function (){
                $('#modalPopup').modal('show');
            }, 15000);*/
            setTimeout(function (){
                $('#exampleModal').modal('show');
            }, 30000);
            setTimeout(function (){
                $('#exampleModal').modal('show');
            }, 60000);
        });
        /*END PRELOADER JS*/
        $('.lazy').lazy({ placeholder: '', defaultImage: '' });
        /*START MENU JS*/
        if ($(window).scrollTop() > 200) {
            $('.fixed-top').addClass('menu-bg');
        } else {
            $('.fixed-top').removeClass('menu-bg');
        }
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 100) {
                $('.site-navigation, .header-white, .header').addClass('navbar-fixed');
            } else {
                $('.site-navigation, .header-white, .header').removeClass('navbar-fixed');
            }
        });
        /*END MENU JS*/

        /*START HOME WATER JS*/
        if (typeof $.fn.ripples == 'function') {
            try {
                $('.ripple').ripples({
                    resolution: 500,
                    perturbance: 0.01
                });
            } catch (e) {
                $('.error').show().text(e);
            }
        }
        /*END HOME WATER JS*/

        /*START VIDEO JS*/
        $('.video-play').magnificPopup({
            type: 'iframe'
        });
        /*END VIDEO JS*/

        /* START JQUERY LIGHTBOX*/
        jQuery('.lightbox').venobox({
            numeratio: true,
            infinigall: true
        });
        /* END JQUERY LIGHTBOX*/

        /*START PORTFOLIOS JS*/
        $("#work-slider").owlCarousel({
            items: 3,
            itemsDesktop: [1000, 3],
            itemsDesktopSmall: [980, 2],
            itemsTablet: [768, 2],
            itemsMobile: [650, 1],
            pagination: false,
            navigation: true,
            slideSpeed: 1000,
            autoPlay: false,
            nav: true,
            navigationText: ["<img src='"+themeUrl+"/assets/images/prev.png'>", "<img src='"+themeUrl+"/assets/images/next.png'>"]
        });
        $("#work-slider-2").owlCarousel({
            items: 3,
            itemsDesktop: [1000, 3],
            itemsDesktopSmall: [980, 2],
            itemsTablet: [768, 2],
            itemsMobile: [650, 1],
            pagination: false,
            navigation: true,
            slideSpeed: 1000,
            autoPlay: false,
            nav: true,
            navigationText: ["<img src='"+themeUrl+"/assets/images/prev.png'>", "<img src='"+themeUrl+"/assets/images/next.png'>"]
        });
        $(".owl-carousel-portfolio").owlCarousel({
            items: 3,
            itemsDesktop: [1000, 3],
            itemsDesktopSmall: [980, 2],
            itemsTablet: [768, 2],
            itemsMobile: [650, 1],
            pagination: false,
            navigation: true,
            slideSpeed: 1000,
            autoPlay: false,
            nav: true,
            navigationText: ["<img src='"+themeUrl+"/assets/images/prev.png'>", "<img src='"+themeUrl+"/assets/images/next.png'>"]
        });
        $("#work-slider-3").owlCarousel({
            items: 3,
            itemsDesktop: [1000, 3],
            itemsDesktopSmall: [980, 2],
            itemsTablet: [768, 2],
            itemsMobile: [650, 1],
            pagination: false,
            navigation: true,
            slideSpeed: 1000,
            autoPlay: false,
            nav: true,
            navigationText: ["<img src='"+themeUrl+"/assets/images/prev.png'>", "<img src='"+themeUrl+"/assets/images/next.png'>"]
        });
        /*END PORTFOLIOS JS*/

        /*START TESTIMONIAL JS*/
        $("#testimonial-slider").owlCarousel({
            items: 1,
            itemsDesktop: [1000, 1],
            itemsDesktopSmall: [980, 1],
            itemsTablet: [768, 1],
            itemsMobile: [650, 1],
            pagination: true,
            navigation: true,
            slideSpeed: 1000,
            autoPlay: false
        });
        $(".carousel-single-slider").owlCarousel({
            items: 1,
            itemsDesktop: [1000, 1],
            itemsDesktopSmall: [980, 1],
            itemsTablet: [768, 1],
            itemsMobile: [650, 1],
            pagination: false,
            navigation: true,
            slideSpeed: 1000,
            slideSpeed: 1000,
            autoPlay: false,
            nav: true,
            navigationText: ["<img src='"+themeUrl+"/assets/images/prev-1.png'>", "<img src='"+themeUrl+"/assets/images/next-1.png'>"]
        });
        /*END TESTIMONIAL JS*/

    });

    /* START PARALLAX JS */
    (function() {

        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {

        } else {
            $(window).stellar({
                horizontalScrolling: false,
                responsive: true
            });
        }

    }());
    /* END PARALLAX JS  */

    /*START WOW ANIMATION JS*/
    new WOW().init();
    /*END WOW ANIMATION JS*/
    $('.btn-register-b').click(function(e) {

        let form = $(this).parents('form');
        let data = form.serializeArray();
        if (data.every(item => item.value)) {
            $.post('/', data)
                .done(function() {
                    // alert("second success");
                })
                .fail(function() {
                    // alert("error");
                })
                .always(function() {
                    $('#exampleModal').modal('hide');
                    $('#thankModal').modal('show');
                    form.get(0).reset();
                });
            e.stopPropagation();
            e.preventDefault();
        }
    });
    $('.btn-register-a').click(function(e) {
        e.stopPropagation();
        e.preventDefault();
        let form = $(this).parents('form');
        let data = form.serializeArray();
        if (data.every(item => item.value)) {
            let a = {};
            $.each(data, function (i, field) {
                a[field.name] = field.value;
            });
            let url = '/forms/register?';
            url += 'fullname=' + a.Name;
            url += '&email=' + a.Email;
            url += '&phone=' + a.Phone;
            $.post(url, data)
                .done(function() {
                    // alert("second success");
                })
                .fail(function() {
                    // alert("error");
                })
                .always(function() {
                    $('#thankModal').modal('show');
                    form.get(0).reset();
                });
            e.stopPropagation();
            e.preventDefault();
        }
    });
   /* particlesJS('particles-js',

        {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    },
                    "image": {
                        "src": "img/github.svg",
                        "width": 100,
                        "height": 100
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 5,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 6,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 400,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true,
            "config_demo": {
                "hide_card": false,
                "background_color": "#b61924",
                "background_image": "",
                "background_position": "50% 50%",
                "background_repeat": "no-repeat",
                "background_size": "cover"
            }
        }

    );*/
})(jQuery);