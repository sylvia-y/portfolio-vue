
$(document).ready(function () {
    let wW = window.innerWidth;
    let wH = window.innerHeight;
    const mdepth2 = $(".m-depth2");
    const mdepth1 = $(".m-depth1");
    const mgnb = $(".m-gnb-container");
    const body = $("body");
    const hd = $("#wj-hd");
    let hdH = hd.height();
    const openBtn = $(".m-gnb-open");
    const closeBtn = $(".m-gnb-close");
    const gnbWrap = $(".wj-hd-wrap");
    const depth1 = $(".depth1");
    const depth2 = $(".depth2");
    const d12 = $(".depth1-2");
    const d13 = $(".depth1-3");
    const d14 = $(".depth1-4");
    const d15 = $(".depth1-5");
    const slideSpeed = 200;
   
    rwd();

    $(window).resize(function () {
        rwd();
        reset();
    });
    function rwd() {
        wW = window.innerWidth;
        wH = window.innerHeight;
        
        if (wW < 768) {
            body.addClass("mo").removeClass("tb pc");
        } else if (wW >= 768 && wW < 1024) {
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

    function gnbReset() {
        if (body.hasClass("mo")) {
            gnbWrap.css("left", "-300px");
            depth2.stop().slideUp(slideSpeed);
        } else {
            gnbWrap.css("left", "0");
        }
    }

    openBtn.click(function () {
        if (body.hasClass("mo") || body.hasClass("tb")){
            body.addClass("hidden");
            mgnb.css("display", "block");
            mgnb.css("right", "0px");
        }
        if (body.hasClass("pc")) {
            if ($(".wj-hd-container").hasClass("wide")) {
                $(".wj-hd-container").removeClass("wide");
                depth2.css("display","none");
                hd.css("background-color","rgba(0,0,0,0.2)");
                hd.css("border","1px solid rgba(255,255,255,0.5)");
            }
            else  {
                $(".wj-hd-container").addClass("wide")
                depth2.css("display","block");
                hd.css("background-color","transparent");
                hd.css("border","none");
            }
        }
        
        
    });
    closeBtn.click(function () {
        body.removeClass("hidden");
        mgnb.css("right", "-9999999px");
    });

    //PC GNB 작동
    //trg(depth1), event(mouseenter), method(fadeIn)
    depth1.on({
        mouseenter: function () {
            if (body.hasClass("pc") || body.hasClass("tb")) {
                if(!$(".wj-hd-container").hasClass("wide")) {
                    $(this).find(".depth2").stop().fadeIn(slideSpeed);
                }
            }
        },
        mouseleave: function () {
            if (body.hasClass("pc") || body.hasClass("tb")) {
                if(!$(".wj-hd-container").hasClass("wide")) {
                    $(this).find(".depth2").stop().fadeOut(slideSpeed);
                }
            }
        }
    });
    
    //Mobile GNB 작동
    mdepth1.on({
        mouseenter: function () {
            if (body.hasClass("mo") || body.hasClass("tb")) {
                $(this).find(".m-depth2").stop().slideDown(slideSpeed);
            }
        },
        mouseleave: function () {
            if (body.hasClass("mo") || body.hasClass("tb")) {
                $(this).find(".m-depth2").stop().slideUp(slideSpeed);
            }
        }
    });
 

    //푸터 브랜드 펼침메뉴
    $(".family-label").click(function () {
        $(".family-list").toggleClass("active");
        //앵커의 기능 실행을 금지
        return false;
    });

    
});
