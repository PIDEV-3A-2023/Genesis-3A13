/*================================
    | | | Preloader | | |
==================================*/
$(window).on('load', function () {
    $('#status').fadeOut();
    $('#preloader').delay(350).fadeOut('slow');
    //$("header").load("nav").delay(400); 

});

/*================================
    | | | Team owl-carousel | | |
==================================*/
$(document).ready(function () {
    $('#team-members').owlCarousel({
        items: 2,
        loop: true,
        autoplay: true,
        SmartSpeed: 700,
        autoplayHoverPause: true,
        nav: true,
        /* <> */
        dots: false,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        responsive: {
            // breakpoint from 0 up
            0: {
                items: 1
            },
            // breakpoint from 480 up
            480: {
                items: 2
            }
        }
    });
});

/*================================
    | | | Progress Bars | | |
==================================*/
$(function () {
    $("#progress-elements").waypoint(function () {
        // alert("hiiiii") ;
        $(".progress-bar").each(function () {
            $(this).animate({
                width: $(this).attr("aria-valuenow") + "%"
            }, 2000);
        });
        this.destroy();
    }, {
        offset: "bottom-in-view"
    });

});

/*================================
    | | | Responsive Tabs | | |
==================================*/
$(function () {
    $('#services-tabs').responsiveTabs({
        animation: 'slide', // The panels will slide up and down
    });
});

/*================================
    | | | Isotop Filters | | |
==================================*/
$(window).on('load', function () {
    //Initialize Isotope
    $('#isotop-container').isotope({});
    //filter items on click button
    $('#isotop-filters').on('click', 'button', function () {
        //alert("you click me") ;
        //get the value name of filter
        var filterValue = $(this).attr('data-filter');
        //alert('you click ' + filterValue) ;
        //filter portfolio 
        $('#isotop-container').isotope({
            filter: filterValue
        });
        //active button
        $('#isotop-filters').find('.active').removeClass('active');
        $(this).addClass('active');

    });
});

/*================================
    | | | MAgnific Popup | | |
==================================*/
$(function () {
    /** Initialize  */
    $('#portfolio-wrapper').magnificPopup({
        delegate: 'a', // child items selector, by clicking on it popup will open
        type: 'image',
        gallery: {
            enabled: true // displaay all galery 
        },
    });
});

/*=========================================
    | | | testemonials owl-carousel | | |
===========================================*/
$(document).ready(function () {
    $('.testemonial-slider').owlCarousel({
        items: 1,
        loop: true,
        autoplay: false,
        SmartSpeed: 700,
        autoplayHoverPause: true,
        nav: true,
        /* <> */
        dots: false,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],

    });
});

/*=========================================
    | | | Counter | | |
===========================================*/
$(document).ready(function () {
    $('.counter').counterUp({
        delay: 10,
        time: 2000,
    });
});

/*=======================================
    | | | clients owl-carousel | | |
=========================================*/
$(document).ready(function () {
    $('#client-list').owlCarousel({
        items: 6,
        loop: true,
        autoplay: true,
        SmartSpeed: 700,
        autoplayHoverPause: true,
        nav: true,
        /* <> */
        dots: false,
        navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        responsive: {
            // breakpoint from 0 up // screen < 480px
            0: {
                items: 2
            },
            // breakpoint from 480 up // scree between then 767px and 480px 
            480: {
                items: 3
            },
             // breakpoint from 768 up // screen >=768px
             768: {
                items: 6 
            }
        }
    });
});

/*=======================================
    | | |   Navigation    | | |
=========================================*/



/* SHow and Hide Navigation */
$(function () {
    //show or hide on page load
    showHideNavigation();

    $(window).scroll(function () {
        showHideNavigation();
    });

    function showHideNavigation() {
        if ($(window).scrollTop() > 50) {
            //alert("you scroll grater than 50  your scroll = " + $(window).scrollTop());
            $("nav").addClass("white-nav-top");
            $(".navbar-brand img").attr("src", "Front/images/logo/maktabti.png")
            //show the icon back-to-top
            $("#back-to-top").fadeIn();
        } else {
            //alert("you scroll less than 50  your scroll = " + $(window).scrollTop());
            $("nav").removeClass("white-nav-top");
            $(".navbar-brand img").attr("src", "Front/images/logo/maktabti.png");
            //hide the icon back-to-top
            $("#back-to-top").fadeOut();
        }
    }
});

//smooth scrolling
$(function () {
    $("a.smooth-scroll").click(function () {

        //get the id of a section like #home
        var section_id = $(this).attr("href");

        $("html , body").animate({
            scrollTop: $(section_id).offset().top - 64
        }, 1250, "easeInOutExpo");

    });
});

/*=======================================
    | | |   Animation    | | |
=========================================*/
$(function () {
    /* Animate on scroll */
    new WOW().init();
});

/** home animation on page load */
$(window).on('load', function () {
    $("#home-heading-1").addClass("animated fadeInDown") ;
    $("#home-heading-2").addClass("animated fadeInLeft") ;
    $("#home-text").addClass("animated zoomIn") ;
    $("#home-btn").addClass("animated zoomIn") ;
    $("#arrow-down").addClass("animated fadeInDown infinite") ;
});

/*=======================================
    | | |   Mobile Menu    | | |
=========================================*/
$(function(){
  //show mobile navigation
  $("#mobile-nav-open-btn").click(function(){
     //alert("you open nav") ;
     $("#mobile-nav").css("height","100%") ;
  });
  //hide mobile navigation
  $("#mobile-nav-close-btn , #mobile-nav a").click(function(){
    //alert("you open nav") ;
    $("#mobile-nav").css("height","0%") ;
 });
});