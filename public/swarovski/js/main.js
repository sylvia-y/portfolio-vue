$(document).ready(function(){
    const body = $("body");
    //바로가기 메뉴 제어(첫화면에서는 서브메뉴 바로가기가 필요없음)
    $("#skip a:eq(2)").hide();
    //움직이는 배너영역, 배너슬라이드 아이템, 이전/다음/페이지 버튼
    const mainBanner = $(".main-banner");
    const mbSlide = $(".mb-slide");
    const mbSlideCount = mbSlide.length; //5
    const mbPrev = $(".mb-prev");
    const mbNext = $(".mb-next");
    const mbPager = $(".mb-btn");
    //화면에 표시되는 배너개수
    let mbSlideShow;
    //배너 한개의 너비값 
    let liWidth = mbSlide.width();
    //슬라이드 개별 활성화, 페이지 표시 변수(0~4)
    let pageNum = 0;
    //몇번째 배너가 보이고 있는지를 체크할 변수(0~2)
    let showBanner = 0;
    //이동 가능한 최대값
    let maxNum;
    
    //이동할 거리
    let moveX = 0;

    //초기에 활성화될 슬라이드
    mbSlide.eq(showBanner).addClass("active");
    mbNum();
    
    $(window).resize(function(){
        liWidth = mbSlide.width();
        showBanner = pageNum = 0;
        mbNum();
        moveSlide();
        activeControl();
    });

    function mbNum() { //해상도에 따라 최대값 계산하는 함수
        if(body.hasClass("mo")){
            mbSlideShow = 1;
        } else if(body.hasClass("tb")){
            mbSlideShow = 2;
        } else {
            mbSlideShow = 3;
        }
        maxNum = mbSlideCount - mbSlideShow;
    }
    
    function moveSlide() { //메인배너 움직임(slide)
        moveX = -liWidth * showBanner;
        mainBanner.css("transform", "translateX(" + moveX + "px)");
    }

    function activeControl() { //메인배너 현재 페이지 버튼 표시
        //모든 페이지버튼, 슬라이드 활성화 원상복귀
        mbPager.removeClass("active");
        mbSlide.removeClass("active");
        //클릭한 버튼만 active class
        mbPager.eq(pageNum).addClass("active");
        //클릭한 버튼 번호에 해당하는 슬라이드만 active class
        mbSlide.eq(pageNum).addClass("active");
    }

    mbPager.click(function () {
        //클릭한 페이지버튼 인덱스 저장
        pageNum = showBanner = $(this).index();
        if(showBanner >= maxNum ? showBanner = maxNum : showBanner = showBanner);
        moveSlide();
        activeControl();
    })

    function moveNext() { //메인배너 다음으로 넘김
        if (showBanner == maxNum ? showBanner = maxNum : showBanner++);
        moveSlide();
        if (pageNum == mbSlideCount - 1 ? pageNum = mbSlideCount - 1 : pageNum++);
        activeControl();
    }

    function movePrev() { //메인배너 이전으로 넘김
        if(showBanner == 0 ? showBanner = 0 : showBanner--);
        moveSlide();
        if(pageNum == 0 ? pageNum = 0 : pageNum--);
        activeControl();
    }

    //다음> 버튼을 클릭하면 moveNext함수 호출
    mbNext.click(function(){
        moveNext();
    });

    //이전< 버튼을 클릭하면 movePrev함수 호출
    mbPrev.click(function(){
        movePrev();
    });

});