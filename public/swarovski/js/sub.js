$(function(){
    //페이지 구분
    let page = $("body").attr("id");
    let mainNum = page.slice(3,4); //대메뉴 번호
    let subNum = page.slice(5,6); //서브메뉴 번호
    let lnbActiveNum = page.slice(-1); //현재 메뉴 표시
    
    //메인메뉴 레이블
    let main = ["New In","Jewelry","Watches","Decoration","Accessories","Giftes","World of Swarovski"];
    
    //서브메뉴 레이블
    let sub = [];
    sub[0] = "sub1-1|sub1-2|sub1-3";
    sub[1] = "sub2-1|sub2-2|sub2-3";
    sub[2] = "sub3-1|sub3-2|sub3-3";
    sub[3] = "sub4-1|sub4-2|sub4-3";
    sub[4] = "sub5-1|sub5-2|sub5-3";
    sub[5] = "sub6-1|sub6-2|sub6-3";
    sub[6] = "브랜드소개|창업안내|안전보건경영방침|거래희망사전등록|윤리신고센터|채용";
    sub[7] = "고객칭찬|자주 하는 질문|1:1문의";
    for(let i = 0; i < sub.length; i++){
        sub[i] = sub[i].split("|");
    }

    //서브메뉴 URL
    let subUrl = [];
    subUrl[6] = ["/swsk/info_intro.html", "#", "#", "#", "#", "#"];
    subUrl[7] = ["/g5/bbs/board.php?bo_table=swsk_good", "/g5/bbs/board.php?bo_table=swsk_fqa", "/g5/bbs/board.php?bo_table=swsk_qa"];

    $(".sub-hd-wrap > h2, .swsk-container > h1").text(sub[mainNum][subNum]);
    $(".world-main").text(main[mainNum]);
    $(".world-sub").text(sub[mainNum][subNum]);
    //서브메뉴(lnb) 리스트 생성
    for(let j = 0; j < sub[mainNum].length; j++){
        $("#swsk-lnb").addClass("col-" + sub[mainNum].length);
        $("#swsk-lnb").append("<li><a href='" + subUrl[mainNum][j] + "'>" + sub[mainNum][j] + "</a></li>");
    }
    $("#swsk-lnb > li:eq("+lnbActiveNum+")").addClass("active");
    
    let scTop = 0;
    const hd = $("#swsk-hd");
    let hdH = hd.height();
    const lnb = $(".lnb-container");
    let lnbPos = lnb.offset().top - hdH;

    $(window).resize(function(){
        init();
    });

    function init() {
        hdH = hd.height();
        lnbPos = lnb.offset().top - hdH;
    }

    $(document).scroll(function(){
        scTop = $(document).scrollTop();
        if(scTop > lnbPos){
            lnb.addClass("fixed").css("top", hdH);
        } else {
            lnb.removeClass("fixed").css("top", "inherit");
        }
    });
});