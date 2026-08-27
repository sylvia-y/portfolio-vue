
$(document).ready(function () {
    let wW = window.innerWidth;
    let wH = window.innerHeight;
    const hd = $("#wj-hd");
    let hdH = hd.height();
    let scTop = 0;
    let mbTb = $(".mb-text-box");
    let vidtt = $(".vid-title");
    let vidts = $(".vid-sub-title");
    let bizTi = $(".main-biz-title");
    let ciTi = $(".ci-title");
    let carTb = $(".main-career-text-box");

//ani-top
    $(window).scroll(function () {
        scTop = $(window).scrollTop();
        // console.log(scTop)
        if (scTop > hdH) {
            hd.addClass("fixed");
        } else {
            hd.removeClass("fixed");
        }
        $(".ani-top").each(function () {
            let offsetTop = $(this).offset().top - wH;
            if (scTop > offsetTop) {
                $(this).addClass("fade-in");
            } else {
                $(this).removeClass("fade-in");
            }
        });
        if (scTop > 250) {
            mbTb.css("animation","disappear 1s ease-out forwards");
        }else{
            mbTb.css("animation","slide 1s ease-out");
        }
        let controlBtn = $(".control-btn");
        
        if (scTop > 300) {
            controlBtn.css("animation","disappear 1s ease-out forwards");
        }else{
            controlBtn.css("animation","slide 1s ease-out");
        }
        if(scTop >1400 ){
            vidtt.css("animation","bizdis 1s ease-out forwards")
        }
        else{
            vidtt.css("animation","biz 1s ease-out");
        }
        if(scTop > 1550 ){
            vidts.css("animation","bizdis 1s ease-out forwards")
        }
        else{
            vidts.css("animation","biz 1s ease-out");
        }
    
        if (scTop > 2610) {
            bizTi.css("animation","bizdis 1s ease-out forwards");
        }else {
            bizTi.css("animation","biz 1s ease-out");
        }
        if (scTop > 3590) {
            ciTi.css("animation","disSl 1s ease-out forwards");
        }else {
            ciTi.css("animation","leftSl 1s ease-out");
        }
        if (scTop > 4200) {
            carTb.css("animation","disSl 1s ease-out forwards");
        }else {
            carTb.css("animation","leftSl 1s ease-out");
        }

    });
});
