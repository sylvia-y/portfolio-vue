<?
    if($_SERVER['HTTP_HOST'] == "motors.chabot.kr"){
        header('Location: https://chabotmotors.com');
    }
    
    include "include/common.php";
	#------------------------------------------------------------------------------
	# 접근 홈페이지 지정 test
	#------------------------------------------------------------------------------
    $SITE_INFO['homepage'] = "customer";
    
	#------------------------------------------------------------------------------
	# agree_param 일 경우 암호 풀어서 파라미터를 변수로 바꾸기 2024-04-02
	#------------------------------------------------------------------------------
    if($_REQUEST['agree_param']){
        $secret_param = JHSecretEnd($_REQUEST['agree_param']);
        parse_str($secret_param, $_REQUEST);
    }

    // CONTENT
    #--------------------------------------------------------------------
    # 컨텐츠 분기를 위한 변수 가져오기
    #--------------------------------------------------------------------
    $gubun		= JHRequestCheck($_REQUEST['gubun']		    ,   50, true, true);
    $page_gubun	= JHRequestCheck($_REQUEST['page_gubun']	,   50, true, true);
    $sub_gubun	= JHRequestCheck($_REQUEST['sub_gubun']		,   50, true, true);
    $mode		= JHRequestCheck($_REQUEST['mode']          ,   50, true, true);
    $grp		= JHRequestCheck($_REQUEST['grp']           ,   50, true, true);

    // 임시 act로 나간 url 때문에 2019.03.14  KEY
    if($_REQUEST['act']){
        $sub_gubun = $_REQUEST['act'];
    }

    $grp1 = substr($grp, 0 ,2);
    $grp2 = substr($grp, 2 ,2);
    $grp3 = substr($grp, 4 ,2);

    // echo $gubun . "<br />" . $sub_gubun . "<br />" . $mode . "<br />";

	#------------------------------------------------------------------------------
	# 헤더 출력
	#------------------------------------------------------------------------------
	Header('P3P: CP=\"ALL IND DSP COR ADM CONo CUR CUSo IVAo IVDo PSA PSD TAI TELo OUR SAMo CNT COM INT NAV ONL PHY PRE PUR UNI\"');
	Header('Content-Type: text/html; charset=utf-8');

    // Template 언더바 환경설정
    include '_class/Template_.class.php'; 

    $kakaotalk_thumbnail_path = "";
	if($gubun){
	
		include "modules/{$gubun}/{$sub_gubun}.php";

        $exp_sub_gubun = explode("_",$sub_gubun);
        $chk_sub_gubun = $exp_sub_gubun[0];
        $end_sub_gubun = end($exp_sub_gubun);

        if($gubun == "member"){
        
			// 반응형
            if($chk_sub_gubun == "react"){

				#--------------------------------------------------------------------
				# 템플리트 클래스 불러오기
				#--------------------------------------------------------------------
				$index_tpl = new Template_; 
				$index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
				$index_tpl->define('page_tpl', "{$USER_TEMP['home']}/react.tpl"); 
				
				#--------------------------------------------------------------------
				# 템플리트 적용하기
				#--------------------------------------------------------------------
				$index_tpl->print_('page_tpl'); 
				
			} else if($chk_sub_gubun == "redirect"){

				#--------------------------------------------------------------------
				# 템플리트 클래스 불러오기
				#--------------------------------------------------------------------
				$index_tpl = new Template_; 
				$index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
			    $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/redirect.tpl");
				
				#--------------------------------------------------------------------
				# 템플리트 적용하기
				#--------------------------------------------------------------------
				$index_tpl->print_('page_tpl'); 
				
			} else {
				// 일반

				#--------------------------------------------------------------------
				# 템플리트 클래스 불러오기
				#--------------------------------------------------------------------
				$index_tpl = new Template_; 
				$index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
				$index_tpl->define('page_tpl', "{$USER_TEMP['home']}/member.tpl"); 
				
				#--------------------------------------------------------------------
				# 템플리트 적용하기
				#--------------------------------------------------------------------
				$index_tpl->print_('page_tpl'); 
			
			}

        } else if($gubun == "samsungcard"){

            // 삼성카드 폴더로 리다이렉트
            JHMoveURL("/samsungcard\/", "", "", "");
            JHExit();
            
        } else if($gubun == "service"){
            
            #--------------------------------------------------------------------
            # 템플리트 클래스 불러오기
            #--------------------------------------------------------------------
            $index_tpl = new Template_; 
            $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
            $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/service.tpl"); 
            
            #--------------------------------------------------------------------
            # 템플리트 적용하기
            #--------------------------------------------------------------------
            $index_tpl->print_('page_tpl'); 

        } else if($gubun == "usedcar"){

            // 중고차는 중고차 인트로를 거쳐 첫차로~
            JHMoveURL("/usedcar\/", "", "", "");
            JHExit();
            
        } else if($gubun == "insure"){

            // 서브구분 앞 값으로 meta image 분기 - key 220427
            switch($chk_sub_gubun){
                case "driver" :
                    $kakaotalk_thumbnail_path = "/images/driver_insure_banner.jpg";
                    break;

                case "tire" :
                    $kakaotalk_thumbnail_path = "/images/tire_insure_banner.jpg";
                    break;
            }

            #--------------------------------------------------------------------
            # 템플리트 클래스 불러오기
            #--------------------------------------------------------------------
            $index_tpl = new Template_; 
                $index_tpl = new Template_; 
            $index_tpl = new Template_; 
                $index_tpl = new Template_; 
            $index_tpl = new Template_; 
                $index_tpl = new Template_; 
            $index_tpl = new Template_; 
            $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
            $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
            $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
            $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
            $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/insure.tpl"); 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/insure.tpl"); 
            $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/insure.tpl"); 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/insure.tpl"); 
            $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/insure.tpl"); 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/insure.tpl"); 
            $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/insure.tpl"); 
            
            #--------------------------------------------------------------------
            # 템플리트 적용하기
            #--------------------------------------------------------------------
            $index_tpl->print_('page_tpl'); 
                $index_tpl->print_('page_tpl'); 
            $index_tpl->print_('page_tpl'); 
                $index_tpl->print_('page_tpl'); 
            $index_tpl->print_('page_tpl'); 
                $index_tpl->print_('page_tpl'); 
            $index_tpl->print_('page_tpl'); 

        } else if($gubun == "rent"){

            $kakaotalk_thumbnail_path="/images/capital_200601.png";

            if($chk_sub_gubun == "pop"){
                
                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/pop.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl');
            
            } else {

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/rent.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 

            }

        }else if($gubun == "document"){
            #--------------------------------------------------------------------
            # 템플리트 클래스 불러오기
            #--------------------------------------------------------------------
            $index_tpl = new Template_; 
            $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
            $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/document.tpl"); 
            
            #--------------------------------------------------------------------
            # 템플리트 적용하기
            #--------------------------------------------------------------------
            $index_tpl->print_('page_tpl'); 

        } else {
        
            if($chk_sub_gubun == "pop"){
                
                // 카카오톡 썸네일 분기 - 이진선 200518
                $bank_gubun = JHRequestCheck($_REQUEST['bank_gubun'], 100, true, true);
                if($gubun == "capital"){
                    switch($bank_gubun) {
                        case "samsungcard":
                                $kakaotalk_thumbnail_path="/images/capital_kakao_image.png";
                            break;
                        default:  
                                $kakaotalk_thumbnail_path="/images/capital_200601.png";
                            break;
                    }
                }

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/pop.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 

            } else if($chk_sub_gubun == "react"){

				#--------------------------------------------------------------------
				# 템플리트 클래스 불러오기
				#--------------------------------------------------------------------
				$index_tpl = new Template_; 
				$index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
				$index_tpl->define('page_tpl', "{$USER_TEMP['home']}/react.tpl"); 
				
				#--------------------------------------------------------------------
				# 템플리트 적용하기
				#--------------------------------------------------------------------
				$index_tpl->print_('page_tpl'); 
				
			} else if($chk_sub_gubun == "fin"){

                $chk_mobile = "";
                if($chkMobile == 1 && ($chkMobile_name == "iPhone" || $chkMobile_name == "iPad")) {
                    $chk_mobile = "apple";
                }

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/fin.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $tpl->assign('chk_mobile'			                , $chk_mobile);
                $tpl->assign('chkMobile'			                , $chkMobile);
                
                $index_tpl->print_('page_tpl'); 

            } else if($chk_sub_gubun == "kiosk"){

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/pop.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 

            } else if($chk_sub_gubun == "partner"){

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/pop.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 

            }  else if($chk_sub_gubun == "customer"){

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/pop.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 

            } else if($chk_sub_gubun == "capital"){

                include "modules/home/capital.php";

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/capital.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 

            } else if($chk_sub_gubun == "starauto"){

                include "modules/home/capital.php";

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['capital']}/starauto_intro.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 

            } else if($chk_sub_gubun == "prime"){

                $sub_gubun_str = substr($sub_gubun, -6);

                $gubun_main = "gubun=prime_event&sub_gubun=prime_main_202011";
                $HTTP_REFERER = $_SERVER['HTTP_REFERER'];

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/prime.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $tpl->assign('sub_gubun'            , $sub_gubun); 
                $tpl->assign('sub_gubun_str'        , $sub_gubun_str); 
                $tpl->assign('gubun_main'           , $gubun_main); 
                $tpl->assign('HTTP_REFERER'         , $HTTP_REFERER); 

                $index_tpl->print_('page_tpl'); 

            } else if($chk_sub_gubun == "boket"){

                $sub_gubun_str = substr($sub_gubun, -6);

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/boket.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 

            } else if($gubun == "mini_insure"){
           
                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/boket.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 

            } else if($chk_sub_gubun == "web"){

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/web.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 
    
            }
            else if($chk_sub_gubun == "event"){

                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/event.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 
    
            } else if($chk_sub_gubun == "motors"){
                // 차봇 motors 그레나디어 이벤트 - 20241010 yy
                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/motors.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 
    
            }else {
                #--------------------------------------------------------------------
                # 템플리트 클래스 불러오기
                #--------------------------------------------------------------------
                $index_tpl = new Template_; 
                $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
                $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/main.tpl"); 
                
                #--------------------------------------------------------------------
                # 템플리트 적용하기
                #--------------------------------------------------------------------
                $index_tpl->print_('page_tpl'); 

            }
        }

    } else {

    // 구분값이 없을 경우 일반 홈페이지로 리다이렉트 key 191010
    // header('Location: https://company.chabot.kr');
    // $chk_sub_gubun = substr($sub_gubun,0,3);

        // 구분값이 없을 경우 일반 홈페이지로 리다이렉트 key 191010
        header('Location: https://www.chabot.co.kr');
/*
		include "include/menu.php";
		include "modules/home/index.php";

        if($page_gubun){
            include "modules/{$page_gubun}/{$sub_gubun}.php";
      
			if($sub_gubun!="index_test"){
				#--------------------------------------------------------------------
				# 템플리트 클래스 불러오기
				#--------------------------------------------------------------------
				$index_tpl = new Template_; 
				$index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
				$index_tpl->define('page_tpl', "{$USER_TEMP['home']}/company_index.tpl"); 
				
				#--------------------------------------------------------------------
				# 템플리트 적용하기
				#--------------------------------------------------------------------
				$index_tpl->print_('page_tpl'); 
			} else {
				#--------------------------------------------------------------------
				# 템플리트 클래스 불러오기
				#--------------------------------------------------------------------
				$index_tpl = new Template_; 
				$index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
				$index_tpl->define('page_tpl', "{$USER_TEMP['home']}/index_test.tpl"); 
				
				#--------------------------------------------------------------------
				# 템플리트 적용하기
				#--------------------------------------------------------------------
				$index_tpl->print_('page_tpl'); 

			}

        } else {

            #--------------------------------------------------------------------
            # 템플리트 클래스 불러오기
            #--------------------------------------------------------------------
            $index_tpl = new Template_; 
            $index_tpl->prefilter = 'adjustPath & css,jpg,jpeg,png,gif,swf'; 
            $index_tpl->define('page_tpl', "{$USER_TEMP['home']}/index.tpl"); 
            
            #--------------------------------------------------------------------
            # 템플리트 적용하기
            #--------------------------------------------------------------------
            $index_tpl->print_('page_tpl'); 

        }
*/

    }

?>