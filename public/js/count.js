$(function () {
    var isCounting = false; // 카운팅 중인지 체크
    $(".tech-inner").on("mouseenter", function () {
        if (!isCounting) {
            counting();
        }
    });
    function counting() {
        isCounting = true;
        // 애니메이션 실행
        animateCount(40, 90, 3000, ".count9");
        animateCount(30, 80, 2500, ".count8");
        animateCount(10, 65, 1500, ".count6");
        // 모든 애니메이션 완료 후 플래그 초기화
        setTimeout(function () {
            isCounting = false;
        }, Math.max(3000, 2500, 1500));
    }
    function animateCount(from, to, duration, selector) {
        $({ val: from }).stop(true, true).animate({ val: to }, {
            duration: duration,
            step: function (now, tween) {
                $(selector).text(numberWithCommas(Math.floor(now)));
            },
            complete: function () {
                $(selector).text(numberWithCommas(to));
            }
        });
    }
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});
