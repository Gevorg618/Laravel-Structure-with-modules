/* eslint-disable */

/* ----------------------------------------------------------------------------------------
* Author        : Mohsin_kabir
* Template Name : BonaFide | One Page Corporate Html Template
* File          : BonaFide main JS file
* Version       : 1.0
* ---------------------------------------------------------------------------------------- */




    
/* INDEX
----------------------------------------------------------------------------------------

01. Preloader js

02. change Menu background on scroll js 

03. Navigation js

04. Smooth scroll to anchor

05. Portfolio js

06. Magnific Popup js

07. Testimonial js

08. client js

09. Google Map js

10. Ajax Contact Form js

11. Mailchimp js

-------------------------------------------------------------------------------------- */





(function($) {
'use strict';


    var wndw = $(window);

    /*-------------------------------------------------------------------------*
     *             02. change Menu background on scroll js                     *
     *-------------------------------------------------------------------------*/
      wndw.on('scroll', function () {
          var menu_area = $('.menu-area');
          if (wndw.scrollTop() > 70) {
              menu_area.addClass('sticky-menu');
          } else {
              menu_area.removeClass('sticky-menu');
          }
      });




    /*-------------------------------------------------------------------------*
     *                   03. Navigation js                                     *
     *-------------------------------------------------------------------------*/
      $(document).on('click', '.navbar-collapse.in', function (e) {
          if ($(e.target).is('a') && $(e.target).attr('class') != 'dropdown-toggle') {
              $(this).collapse('hide');
          }
      });

      $('body').scrollspy({
          target: '.navbar-collapse',
          offset: 195
      });



    /*-------------------------------------------------------------------------*
     *                   04. Smooth scroll to anchor                           *
     *-------------------------------------------------------------------------*/
      $('a.smooth_scroll').on("click", function (e) {
          e.preventDefault();
          var anchor = $(this);
          $('html, body').stop().animate({
              scrollTop: $(anchor.attr('href')).offset().top - 50
          }, 1000);
      });



    /*-------------------------------------------------------------------------*
     *                  05. Slider js                                       *
     *-------------------------------------------------------------------------*/
     //Function to animate slider captions 
    function doAnimations( elems ) {
      //Cache the animationend event in a variable
      var animEndEv = 'webkitAnimationEnd animationend';
      
      elems.each(function () {
        var $this = $(this),
          $animationType = $this.data('animation');
        $this.addClass($animationType).one(animEndEv, function () {
          $this.removeClass($animationType);
        });
      });
    }
    
    //Variables on page load 
    var $myCarousel = $('#carousel-example-generic'),
      $firstAnimatingElems = $myCarousel.find('.item:first').find("[data-animation ^= 'animated']");
      
    //Initialize carousel 
    $myCarousel.carousel({
      interval: 5000
    });
    
    //Animate captions in first slide on page load 
    doAnimations($firstAnimatingElems);
    
    //Pause carousel  
    //$myCarousel.carousel('pause');
    
    
    //Other slides to be animated on carousel slide event 
    $myCarousel.on('slide.bs.carousel', function (e) {
      var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
      doAnimations($animatingElems);
    }); 


    /*-------------------------------------------------------------------------*
     *                  05. Portfolio js                                       *
     *-------------------------------------------------------------------------*/
      $('.portfolio').mixItUp();


 

    /*-------------------------------------------------------------------------*
     *                  06. Magnific Popup js                                  *
     *-------------------------------------------------------------------------*/
      $('.work-popup').magnificPopup({
          type: 'image',
          gallery: {
              enabled: true
          }
      });

      $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
      });
      


    /*-------------------------------------------------------------------------*
     *                  Service Carousel                                    *
     *-------------------------------------------------------------------------*/
      $(".home-services").owlCarousel({
          items               : 4,
          itemsDesktop        : [1199, 3],
          itemsDesktopSmall   : [991, 2],
          itemsTablet         : [767, 1],
          pagination          : false,
          navigation          : true,
          autoHeight          : true,
          autoPlay            : 4000, // 1000 = 1 second
          navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>']
      });


    /*-------------------------------------------------------------------------*
     *                  07. Testimonial js                                     *
     *-------------------------------------------------------------------------*/
      $(".testimonial-list").owlCarousel({
          items               : 3,
          itemsDesktop        : [1199, 3],
          itemsDesktopSmall   : [991, 2],
          itemsTablet         : [767, 1],
          pagination          : false,
          navigation          : true,
          autoHeight          : true,
          autoPlay            : 4000, // 1000 = 1 second
          navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>']
      });



    /*-------------------------------------------------------------------------*
     *                       08. client js                                     *
     *-------------------------------------------------------------------------*/
      $(".owl-client").owlCarousel({
          items               : 5,
          autoPlay            : true,
          itemsDesktop        : [1199, 5],
          itemsDesktopSmall   : [991, 4],
          itemsTablet         : [767, 3],
          itemsMobile         : [479, 2],
          pagination          : false,
          navigation          : false,
          autoHeight          : true,
      });



      /* ----------------------------------------------------------------------------------------
       *  Skill progress bar
       * --------------------------------------------------------------------------------------*/
        
        var skillbar = $(".skillbar");

        skillbar.waypoint(function () {
            skillbar.each(function () {
                $(this).find(".skillbar-child").animate({
                    height: $(this).data("percent")
                }, 1000);
            });
        }, {
            offset: "80%"
        });



    /*-------------------------------------------------------------------------*
     *                       09. Google Map js                                 *
     *-------------------------------------------------------------------------*/
    
     if(document.getElementById("contactgoogleMap")) {
      const contact_us = GlobalScope.contact_us;
      var myCenter=new google.maps.LatLng( contact_us.lat, contact_us.long);
      function initialize(){
          var mapProp = {
              zoom:14,
              center:myCenter,
              scrollwheel: false,
              mapTpeIdy:google.maps.MapTypeId.ROADMAP
          };
          var map=new google.maps.Map(document.getElementById("contactgoogleMap"),mapProp);
          var marker=new google.maps.Marker({
              position:myCenter,
              animation:google.maps.Animation.BOUNCE,
              icon:'/build/frontend/images/map-marker.png',
              map: map,
          });
          marker.setMap(map);
      }
      google.maps.event.addDomListener(window, 'load', initialize);
     }






    /*-------------------------------------------------------------------------*
     *                  10. Ajax Contact Form js                               *
     *-------------------------------------------------------------------------*/
      // Get the form.
      var form = $('#ajax-contact');

      // Get the messages div.
      var formMessages = $('#form-messages');

      // Set up an event listener for the contact form.
      $(form).on('submit', function(e) {
          // Stop the browser from submitting the form.
          e.preventDefault();

          // Serialize the form data.
          var formData = $(form).serialize();

          // Submit the form using AJAX.
          $.ajax({
              type: 'POST',
              url: $(form).attr('action'),
              data: formData
          })
          .done(function(response) {
              // Make sure that the formMessages div has the 'success' class.
              $(formMessages).removeClass('error');
              $(formMessages).addClass('success');

              // Set the message text.
              $(formMessages).text(response.message);

              // Clear the form.
              $('#name').val('');
              $('#email').val('');
              $('#message').val('');
              $('#subject').val('');
              let input_blocks = $(form).find('.form-group');
              $(input_blocks).each((index, value) => {
                  $(value.offsetParent).find('.error').remove();
              });
          })
          .fail(function(data) {
              // Make sure that the formMessages div has the 'error' class.
              $(formMessages).removeClass('success');
              $(formMessages).addClass('error');

              // Set the message text.
              if (data.responseJSON) {
                  $(formMessages).text(data.responseJSON.message);
                  $.each(data.responseJSON.errors, (index, value) => {
                      $(`#${index}`).addClass('is-invalid');
                      const parent = $(`#${index}`)[0].offsetParent;
                      $(parent).append(`<span class="error"><strong>${value[0]}</strong></span>`);
                  });
              } else {
                  $(formMessages).text('Oops! An error occured and your message could not be sent.');
              }
          });

      });

    const subscribeForm = $('#mc-form');
    $(subscribeForm).on('submit', function (e) {
        e.preventDefault();

        const formData = $(subscribeForm).serialize();

        $.ajax({
            url: 'subscribe',
            method: 'POST',
            data: formData
        }).done(res => {
            $(subscribeForm).find(`.mailchimp-error`).text('');
            $(subscribeForm).find('.mailchimp-success').text(res.message);
            $(subscribeForm).find(`#mc-email`).val('');
            console.log('response', res)
        }).fail(err => {
            console.log(err)
            $(subscribeForm).find(`.mailchimp-error`).text(err.responseJSON.errors.subscribe_email);
        })
    })

})(jQuery);