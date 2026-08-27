$(function(){
    //페이지 구분
    let page = $("body").attr("id");
    let mainNum = page.slice(3,4); //대메뉴 번호
    let subNum = page.slice(5,6); //서브메뉴 번호
    let lnbActiveNum = page.slice(-1); //현재 메뉴 표시
    let bgColor = ["#FF4DAB", "#403A9E", "#0095E5", "#FFC919","#FF4DAB", "#403A9E", "#0095E5", "#FFC919"];
    
    //서브메뉴 레이블
    let sub = [];
    sub = "YouTube|플레이 리스트|애니메이션|영화|모바일 앱|제품|브랜드 마케팅|공연";
    sub = sub.split("|");
    console.log(sub);
    let subHdBgImg;

        
        //1. 제목 설명 밑 배경색 지정
        $(".desc-line, .lnb-depth2").css("background-color", bgColor[subNum]);
              
        //2. 서브비주얼 이미지 출력
        
        $(".pinkfong-buis").css("background-image", "url('./images/buisiness/b_" + subNum + ".png')");
 

    //서브메뉴 URL
    let subUrl = [];
    subUrl = ["./youtube.html","./playlist.html","./animation.html","./movie.html","./mobile_app.html","./product.html","./brand.html","./performance.html",];

    
    // 4. 제목 출력
    $(".desc-txt").text(sub[subNum]);
    
    // 5. 서브메뉴(lnb) 리스트 생성
    $(".lnb-depth1").append("<li class=\"active\"><a href=\"#\">" + 'Buisiness' + "</a></li>");

    $(".lnb-depth2").append("<li class=\"active\"><a href=\"#\">" + sub[subNum] + "</a></li>");
    for(let k = 0; k < sub.length; k++) {
        $(".lnb-depth2").append("<li><a href=\"" + subUrl[k] +"\">" + sub[k] + "</a></li>");
  
    }

    // 6. lnb 작동
    $(".lnb").on({
        "mouseenter focusin": function(){
            $(this).addClass("active");
        },
        "mouseleave focusout": function(){
            $(this).removeClass("active");
        }
    });
    
    //7. 글자크기 확대/축소
    let fz = 10;
    let fzMax = 15;
    let fzMin = 6;
    $(".txt-reset-btn").click(function(){
        fz = 10;
        $("html").css("font-size", fz + "px");
    });
    $(".txt-size-plus-btn").click(function(){
        // condition ? true : false;
        fz == fzMax ? fz = fzMax : fz++;
        $("html").css("font-size", fz + "px");
    });
    $(".txt-size-minus-btn").click(function(){
        fz == fzMin ? fz = fzMin : fz--;
        $("html").css("font-size", fz + "px");
    });

     // 8. LNB고정을 위한 값 설정
     let wH = $(window).height();
     let lnbTg = $(".lnb-container");
     let lnbTop = lnbTg.offset().top + lnbTg.height();
     let scTop = $(window).scrollTop();
     $(window).resize(function(){
         wH = $(window).height();
         lnbTop = lnbTg.offset().top + lnbTg.height();
     });

    // 9. 스크롤시 LNB고정
    $(window).scroll(function(){
        scTop = $(window).scrollTop();
        if(scTop > lnbTop) {
            lnbTg.addClass("fixed");
        } else {
            lnbTg.removeClass("fixed");
        }
    });
});