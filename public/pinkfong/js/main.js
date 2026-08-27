$(function () {
    const hd = $("#pinkfong-hd");
    const logo = $(".pinkfong-logo");
    const gnb = $("#pinkfong-gnb");
    const util = $(".util-sns-wrap");
    const fpCon = $("#pinkfong-main-container");
    const body = $("body");
    const topBtn = $(".top-btn")

    //푸터 복제, 5번째 풀페이지 영역으로 추가
    let ftEl = $(".sec-ft").clone();
    fpCon.append(ftEl);
    fpCon.fullpage({
        navigation: true,
        navigationPosition: "left",
        navigationTooltips: ["핑크퐁", "ABOUT US", "사업소개", "YOUTUBE", "제휴문의", "하단정보"],
        anchors: ["Pinkfong", "info", "business", "youtube", "bbs", "ft"], //주소에 id값이 들어감, 섹션에 링크걸어야하기 때문
        afterLoad: function (origin, destination, direction) {
            if (destination.index == 2 || destination.index == 4) {
                gnb.addClass("dark");
                util.addClass("dark");
            }
            else if (destination.index == 3) {
                gnb.addClass("dark");
                util.addClass("dark");
                counting();
            }
            else {
                gnb.removeClass("dark");
                util.removeClass("dark");
            }

            if (destination.index == 5) { //푸터가 보일 때
                hd.css("background-color", "rgba(0,0,0,0.35)");
            } else {
                hd.css("background-color", "");
            }
            //위로가기 버튼 켜기
            if (destination.index > 0) {
                topBtn.fadeIn(300)
            } else {
                topBtn.fadeOut(300)
            }
        }
    })
    if (body.hasClass("mo")) {
        util.removeClass("dark");
    };
    //위로가기
    $(".top-btn").click(function () {
        $.fn.fullpage.moveTo(1);

    });
    //숫자올라가기
    function counting() {
        let memberCountConTxt = 111784874;
        $({ val: 0 }).animate({ val: memberCountConTxt }, {
            duration: 2000,
            step: function () {
                var num = numberWithCommas(Math.floor(this.val));
                $(".count1").text(num);
            },
            complete: function () {
                var num = numberWithCommas(Math.floor(this.val));
                $(".count1").text(num);
            }
        });

        memberCountConTxt = 59873384505;
        $({ val: 0 }).animate({ val: memberCountConTxt }, {
            duration: 2000,
            step: function () {
                var num = numberWithCommas(Math.floor(this.val));
                $(".count2").text(num);
            },
            complete: function () {
                var num = numberWithCommas(Math.floor(this.val));
                $(".count2").text(num);
            }
        });
    }

    //3자리마다 , 찍기
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    //탭 구현
    let tabNum = 0;

    $(".section-tab-menu").each(function () {
        let tabList = $(this).find("li");
        tabList.eq(tabNum).addClass("active");
        $(".tab" + tabNum).show();
        $(".arrow-tab" + tabNum).show();
        tabList.click(function () {
            tabNum = $(this).index();
            //화살표영역 제어
            $(this).closest(".section").find(".arrow-tab").hide();
            $(this).closest(".section").find(".arrow-tab" + tabNum).show();
            //탭메뉴 활성화
            $(this).addClass("active");
            $(this).siblings().removeClass("active");
            //탭내용 표시
            $(this).closest(".section").find(".tab-box").hide();
            $(this).closest(".section").find(".tab" + tabNum).show();
        });
    });

    //slick multi
    $(".contents-box").each(function () {
        $(this).find(".slick-container").slick({
            slidesToShow: 1,
            slideToScroll: 1,
            infinite: true,
            variableWidth: true,
            arrows: true,
            responsive: [
                {
                    breakpoint: 1024, //1024미만
                    settings: {
                        dots: true,
                        arrows: false,
                    }
                }
            ]

        });

    });

});