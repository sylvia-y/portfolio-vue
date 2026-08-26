$(function () {
    AOS.init();

    //swiper
    let swiper = new Swiper('.swiper', {
        spaceBetween : 30,
    pagination: {
        el: '.swiper-pagination',
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },  
    });

    swiper = new Swiper('.game-slider', {
        spaceBetween: 30,
        effect: 'fade',
        loop: true,
        mousewheel: {
          invert: false,
        },
        pagination: {
          el: '.game-slider__pagination',
          clickable: true,
        }
      });

    //mousepointer
    const mousePointer = $("#mouse-pointer"),
    clickElements = $('a, button, input, img')
    function moveCursor(e){
        mousePointer.css({
            "left":e.pageX,
            "top":e.pageY
        })
    }
    clickElements.mouseenter(function(){
        mousePointer.addClass('hover')
    });
    clickElements.mouseleave(function(){
        mousePointer.removeClass('hover')
    });
    $(window).on('mousemove',moveCursor);
    
    //about-ani (aos와 같이 사용불가)
    
    var el = $('.js-tilt-container');
    
    el.on('mousemove', function(e){
        const {left, top} = $(this).offset();
        const cursPosX = e.pageX - left;
        const cursPosY = e.pageY - top;
        const cursFromCenterX = $(this).width() / 2 - cursPosX;
        const cursFromCenterY = $(this).height() / 2 - cursPosY;
        
    
        $(this).css('transform','perspective(500px) rotateX('+ (cursFromCenterY / 40) +'deg) rotateY('+ -(cursFromCenterX / 40) +'deg) translateZ(10px)');
      
      const invertedX = Math.sign(cursFromCenterX) > 0 ? -Math.abs( cursFromCenterX ) : Math.abs( cursFromCenterX );
      
      //Parallax transform on image
      $(this).find('.js-perspective-neg').css('transform','translateY('+ ( cursFromCenterY / 10) +'px) translateX('+ -(invertedX  / 10) +'px) scale(1.15)');
        $(this).removeClass('leave');
    });
    
    el.on('mouseleave', function(){
        $(this).addClass('leave');
    });

    
});