$(function () {
    let wW = window.innerWidth;
    let wH = window.innerHeight;
    let scTop = 0; //fullpage에서는 사용안함
    const body = $("body");
    const hd = $("#pinkfong-hd");
    let hdH = hd.height();
    const hamBtn = $(".m-gnb-btn");
    const gnbWrap = $(".gnb-util-wrap");
    const gnb = $("#pinkfong-gnb")
    const d1 = $(".depth1");
    const d1a = $(".depth1 > a");
    const d2 = $(".depth2");
    const slideSpeed = 300;
    rwd(); //반응형 함수 

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
        if ($("body").hasClass("pc")) {
            pcGnb();
        }//mobile에서는 마우스가 없어서 사실 필요없는 소스
        else {
            hd.off("mouseenter mouseleave");
            gnb.off("mouseenter mouseleave");
            d1.off("mouseenter mouseleave");
        }
    }

    function reset() {
        hdH = hd.height();
        body.removeClass("hidden"); //서브페이지용
        // gnbReset();
    }

    function pcGnb() {
        //헤더디자인 바꾸기
        hd.mouseenter(function () {
            $(this).addClass("active");
        })
        hd.mouseleave(function () {
            $(this).removeClass("active");
        })
        //pc gnb
        gnb.mouseenter(function () {
            d2.fadeIn(slideSpeed);
        });
        gnb.mouseleave(function () {
            d2.fadeOut(slideSpeed);
        });
        d1.mouseenter(function () {
            $(this).addClass("active");
        });
        d1.mouseleave(function () {
            $(this).removeClass("active");
        });

    }

    //모바일GNB
    let chk = 0;

    hamBtn.click(function () {
        chk++;
        if (chk %= 2) {
            $(this).text("close");
        } else {
            $(this).text("menu");
        }

        gnbWrap.toggleClass("active");
    });
    d1a.click(function () {
        if (body.hasClass("mo")) {
            $(this).parent().siblings().removeClass("active");
            $(this).parent().toggleClass("active");
            $(this).parent().siblings().find(".depth2").slideUp(slideSpeed);
            $(this).next().slideToggle(slideSpeed);
        }
    });
    //위로가기
    $(".top-btn").click(function () {
        //(서브페이지)
        $("html, body").stop().animate({ scrollTop: 0 }, 500);
        // 문서끝까지 animation하면서 올라감

    });
});