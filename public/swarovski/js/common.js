$(document).ready(function(){
    let wW = window.innerWidth;
    let wH = window.innerHeight;
    let scTop = 0;
    const body = $("body");
    const hd = $("#swsk-hd");
    let hdH = hd.height();
    const openBtn = $(".m-gnb-open");
    const closeBtn = $(".m-gnb-close");
    const gnbWrap = $(".swsk-gnb-wrap");
    const depth1 = $(".depth1");
    const depth2 = $(".depth2");
    const slideSpeed = 200;
    
    rwd();

    $(window).resize(function(){
        rwd();
        reset();
    });

    function rwd() {
        wW = window.innerWidth;
        wH = window.innerHeight;
        if(wW < 768) {
            body.addClass("mo").removeClass("tb pc");
        } else if(wW >= 768 && wW < 1280) {
            body.addClass("tb").removeClass("mo pc");
        } else {
            body.addClass("pc").removeClass("mo tb");
        }
    }

    function reset() {
        hdH = hd.height();
        body.removeClass("hidden");
        gnbReset();
    }

    function gnbReset(){
        if(body.hasClass("mo")){
            gnbWrap.css("left", "-300px");
            depth1.removeClass("active");
            depth2.stop().slideUp(slideSpeed);
        }
    }

    openBtn.click(function(){
        body.addClass("hidden");
        gnbWrap.css("left", 0);
    });
    closeBtn.click(function(){
        body.removeClass("hidden");
        gnbWrap.css("left", "-300px");
    });

    //모바일 GNB 작동
    depth1.children("a").click(function(){
        if(body.hasClass("mo")||("tb")) {
            //메인메뉴 활성화(active)
            $(this).parent().siblings().removeClass("active");
            $(this).parent().parent().siblings().find(".depth1").removeClass("active");
            $(this).parent().toggleClass("active");
            //서브메뉴 제어
            $(this).parent().siblings().find(".depth2").stop().slideUp(slideSpeed);
            $(this).parent().parent().siblings().find(".depth2").stop().slideUp(slideSpeed);
            $(this).next().stop().slideToggle(slideSpeed);
        }
    });

    //PC GNB 작동
    //trg(depth1), event(mouseenter), method(fadeIn)
    depth1.on({
        mouseenter: function(){
            if(body.hasClass("pc")) {
                $(this).find(".depth2").stop().fadeIn(slideSpeed);
            }
        },
        mouseleave: function(){
            if(body.hasClass("pc")) {
                $(this).find(".depth2").stop().fadeOut(slideSpeed);
            }
        }
    });

    //푸터 브랜드 펼침메뉴
    $(".lab-label").click(function(){
        $(".lab-item").toggleClass("active");
        //앵커의 기능 실행을 금지
        return false;
    });

    //offset : 문서로부터 떨어진 위치
    //속성으로는 left, top
    
    $(document).scroll(function(){
        scTop = $(document).scrollTop();
        if(scTop > hdH){
            hd.addClass("fixed");
        } else {
            hd.removeClass("fixed");
        }
        $(".ani-top").each(function(){
            let offsetTop = $(this).offset().top - wH;
            //console.log(offsetTop);
            if(scTop > offsetTop) {
                $(this).addClass("fade-in");
            } else {
                $(this).removeClass("fade-in");
            }
        });
    });
});