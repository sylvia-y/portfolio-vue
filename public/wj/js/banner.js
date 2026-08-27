
$(document).ready(function () {
   //움직이는 배너영역, 배너슬라이드 아이템, 이전/다음/페이지 버튼
   const mainBanner = $(".main-banner");
   const mbSlide = $(".banner");
   const mbSlideCount = mbSlide.length; //2
   const mbPrev = $(".left-btn");
   const mbNext = $(".right-btn");
   //화면에 표시되는 배너개수
   let mbSlideShow;
   //배너 한개의 너비값 
   let liWidth = mbSlide.width();
   //몇번째 배너가 보이고 있는지를 체크할 변수(0~2)
   let showBanner = 0;
   //이동 가능한 최대값
   let maxNum;
   //이동할 거리
   let moveX = 0;
   $(window).resize(function () {
       liWidth = mbSlide.width();
       moveSlide();
   });

   function moveSlide() { //메인배너 움직임(slide)
       moveX = -liWidth * showBanner;
       mainBanner.css("transform", "translateX(" + moveX + "px)");
       $(".count").text(showBanner+1)
   }
   function moveNext() { //메인배너 다음으로 넘김
       console.log(showBanner,mbSlideCount)
       if (showBanner < mbSlideCount-1) {
           showBanner++
       }
       else {

       }
       moveSlide();

   }
   function movePrev() { //메인배너 이전으로 넘김
       console.log(showBanner,mbSlideCount)
       if (showBanner >0) {
           showBanner--
       }
       else {

       }
       moveSlide();

   }
   //다음> 버튼을 클릭하면 moveNext함수 호출
   mbNext.click(function () {
       moveNext();
   });

   //이전< 버튼을 클릭하면 movePrev함수 호출
   mbPrev.click(function () {
       movePrev();
   });

   //biz 배너 영역
    $(".main-biz-list-wrap").slick({
        slidesToShow: 3,
        autoplay: true,
        autoplaySpeed: 2000,
        speed: 500,
        dots:true,
        centerMode: true,
        centerPadding: '120px',
        appendArrows: $('.slick-controls'),
        appendDots: $('.slick-pagination'),
        responsive : [
            {
                breakpoint: 1280,//미만
                settings: {
                    centerPadding: '100px',
                    slidesToShow : 1.5,
                    
                }
            },
            {
                breakpoint: 768,//미만
                settings: {
                    centerPadding: '0',
                    slidesToShow: 1,
                }
            }
        ]
    });
});