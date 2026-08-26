"use strict";
$(function () {
    var wW = window.innerWidth;
    var wH = window.innerHeight;
    var scTop = 0;
    var $body = $("body");
    var $hd = $("#yy-hd");
    var hdH = $hd.height() || 0;
    // 반응형 실행
    rwd();
    $(window).on("resize", function () {
        wW = window.innerWidth;
        wH = window.innerHeight;
        rwd();
        reset();
    });
    function rwd() {
        if (wW < 768) {
            $body.addClass("mo").removeClass("tb pc");
        }
        else if (wW >= 768 && wW < 1024) {
            $body.addClass("tb").removeClass("mo pc");
        }
        else {
            $body.addClass("pc").removeClass("mo tb");
        }
    }
    function reset() {
        hdH = $hd.height() || 0;
        $body.removeClass("hidden"); // 서브페이지용
        scTop = $(window).scrollTop() || 0;
    }
    // 모바일 메뉴 버튼
    $(".m-button").on("click", function () {
        $("#yy-gnb").toggleClass("active");
        $(".m-button").toggleClass("active");
    });
    // 위로가기 버튼
    $(".top-btn").on("click", function () {
        $("html, body").stop().animate({ scrollTop: 0 }, 500);
    });
    // 스크롤 시 위로가기 버튼 표시
    $(window).on("scroll", function () {
        $(".top-btn").css("display", "block");
    });
    // 스무스 스크롤
    $("a.scroll-link").on("click", function (e) {
        e.preventDefault();
        var targetId = $(e.currentTarget).attr("href");
        if (targetId && $(targetId).length) {
            $("html, body").animate({
                scrollTop: $(targetId).offset().top - 20,
            }, 750);
        }
    });
});
