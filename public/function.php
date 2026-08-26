<?
    ################################################################################
    # 파일      : function.php 
    # 설명      : 함수 정의
    # 만든이    : 신정훈
    # 날짜      : 2018-11-14
    ################################################################################

    // ------------------------------------------------------------------------------
    // 페이지 이동 함수(액션이후 처리)
    // ------------------------------------------------------------------------------
    function JHMoveURL($url='', $msg='', $target='', $action='', $charset='utf-8') {
        $script="";

        if($msg) {
            $script.="alert('$msg');";
        }

        if($url) {
            if($target) {
                $script.="{$target}.location.href = '$url';";
                $target = "";
            } else {
                $script.="location.href = '$url';";
            }
        }

        switch($action) {
            case 'back' :
                $script.="history.back();";
                break;

            case 'close' :
                $script.="self.close();";
                break;
            
            case 'refresh';
                if($target)
                    $script.="$target.Action_Refresh();";
                else
                    $script.="Action_Refresh();";
                break;
            
            case 'refresh,close';
                if($target)
                    $script.="$target.Action_Refresh();self.close();";
                else
                    $script.="Action_Refresh();self.close();";
                break;

            case 'reload'; 
                $script.="location.reload();";

                break;

            case 'reload,close'; 
                if($target)
                    $script.="$target.location.reload();self.close();";
                else
                    $script.="location.reload();self.close();";
                break;

        }

        echo "
            <!DOCTYPE html>
            <HTML lang=ko><HEAD>
            <META HTTP-EQUIV=Content-Type CONTENT=text/html;charset=$charset>
            <SCRIPT LANGUAGE=JavaScript>
            <!--
            $script
            //-->
            </SCRIPT>
            <TITLE>ALERT PAGE</TITLE></HEAD><BODY></BODY>
            </HTML>
        ";

    }

    // ------------------------------------------------------------------------------
    // 파일 삭제
    //     리턴값 : true | false
    //     인자   : 파일 패스
    // ------------------------------------------------------------------------------
    function JHDeleteFile($file_path) {
        @chmod($file_path, 0777);

        $retValue = @unlink($file_path);
        if(file_exists($file_path)) {
            @chmod($file_path,0775);
            $retValue = @unlink($file_path);
        }

        return $retValue;
    }
    
    // ------------------------------------------------------------------------------
    // 글자단위로 구분자 넣기 (ABCDE 에 | 넣어서 A|B|C}D|E)
    // ------------------------------------------------------------------------------
    function JHExplodeString($str, $sap) {
        for ($i=0;$i<strlen($str);$i++) {
            if(ord($str[$i])<=127) {
                $tmp.= $str[$i].$sap;
            } else {
                $tmp.=$str[$i].$str[++$i].$sap;
            }
        }

        return substr($tmp, 0, strlen($tmp)-1);
    }

    // ------------------------------------------------------------------------------
    // 해당 권한 체크
    // $strMemLevel = 회원 권한
    // $strLevels   = 가능한 권한
    // ------------------------------------------------------------------------------
    function JHCheckLevel($strMemLevel, $strLevels) {
        $strLevel = explode("|", $strLevels);
        
        $retValue = false;
        foreach($strLevel as $forLevel) {
            if(is_array($strMemLevel)) {
                foreach($strMemLevel as $lvl) {
                    if($lvl==trim($forLevel)) {
                        return true;
                    }
                }
            } else {
                if($strMemLevel==trim($forLevel)) {
                    return true;
                }
            }
        }

        return false;
    }

    //-------------------------------------------------------------------
    // 넘어오는 데이터에 대한 SQL INJECTION 및 데이터 변환 및 데이터 크기 자르기
    //-------------------------------------------------------------------
    function JHRequestCheck($data, $cnt, $sql_check=true, $tag_check=true) {
        if($sql_check) $data = JHSQLDeny($data);
        if($tag_check) $data = JHRemoveEvilTags($data);
        $returnVal = JHHanCutString($data, $cnt, false);
        return $returnVal;
    }

    // ------------------------------------------------------------------------------
    // DB에 처리하기 위해서 SQL Injection 을 처리한다.
    // ------------------------------------------------------------------------------
    function JHSQLDeny($data) {
        $data = str_replace("'", "&#039;", $data);
        $returnVal = preg_replace("/( select | union | insert | update | delete | drop | AND 1=1|\" AND \"1\"=\"1| AND | OR |\/\*|\*\/|\\\)/i", "", $data);
        $returnVal = addslashes($returnVal);
        return $returnVal;
    }

    // ------------------------------------------------------------------------------
    // 자료 등록시 필요없는 태그 삭제하기
    // ------------------------------------------------------------------------------
    function JHRemoveEvilTags($data) {
        // 허용할 테그
        $allowedTags = '<b><i><br />';

        // 제거할 속성
        $stripAttrib = 'javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|';
        $stripAttrib = $stripAttrib . 'onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|';
        $stripAttrib = $stripAttrib . 'onchange|onblur|onfocus|';
        $stripAttrib = $stripAttrib . 'h1|a|ul|ol|li|hr|img|font|span|table|tr|td|p|script';

        $str = preg_replace("/<(\/?)(?![\/a-z])([^>]*)>/i", "&lt;\\1\\2\\3&gt;", $data);

        return $str;
    }

    //-------------------------------------------------------------------
    // 한글 짜르기 
    //-------------------------------------------------------------------
    function JHHanCutString($str, $length=20, $isTail=true) {


        /* utf-8 경우     */
        $checkmb = true;
        $tail = $isTail ? "..." : "";

        preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match); 
        $m    = $match[0]; 
        $slen = strlen($str);  // length of source string 
        $tlen = strlen($tail); // length of tail string 
        $mlen = count($m);    // length of matched characters 

        if ($slen <= $length) return $str; 
        if (!$checkmb && $mlen <= $length) return $str; 

        $ret  = array(); 
        $count = 0; 

        for ($i=0; $i < $length; $i++) { 
            $count += ($checkmb && strlen($m[$i]) > 1)?2:1; 
            if ($count + $tlen > $length) break; 
            $ret[] = $m[$i]; 
        } 
        return join('', $ret).$tail;
        
    }

    //-------------------------------------------------------------------
    // 이미지 크기 조절
    //-------------------------------------------------------------------
    function JHResizeImage($file_src, $max_w, $max_h) {
        if(!file_exists($file_src)) return array("0","0");

        $size = getimagesize("$file_src");
        $width = $size[0];
        $height = $size[1];
        $rate_h = $height/$max_h; // 이미지 height 비율 
        $rate_w = $width/$max_w; // 이미지 width 비율 

        if($height > $max_h || $width > $max_w) {
            if(($height > $max_h && $width > $max_w && $rate_w > $rate_h) || ($height < $max_h && $width > $max_w)) {
                $rate = $rate_w; 
            // } elseif(($height >= $max_h && $width >= $max_w && $rate_w < $rate_h) || ($height >= $max_h && $width < $max_w)) {
            } else {
                $rate = $rate_h;
            } 
        } else {
            $rate = 1;
        }

        $resize_h = $height/$rate; 
        $resize_w = $width/$rate; 
        $newsize = array("$resize_w", "$resize_h");

        return $newsize;
    }

    //-------------------------------------------------------------------
    // GD 라이브러리를 이용한 섬네일 생성
    //-------------------------------------------------------------------
    function JHImgThumnail($cur_path, $cur_file, $cw, $ch, $ext) {
		$format = $ext;
        $part		= explode(".", $cur_file);
        $ext		= $part[sizeof($part)-1];
        $pre		= $part[sizeof($part)-2];

        $filename  = $cur_path."/" . $cur_file;
        $sfilename  = $cur_path."/" . $cur_file;
        // $sfilename = $cur_path."/" . $pre . "_thumbnail." . $format;


        list($width, $height) = getimagesize($filename);
        switch($format) {
            case 'jpg':
                $source = imagecreatefromjpeg($filename);
                break;

            case 'jpeg':
                $source = imagecreatefromjpeg($filename);
                break;

            case 'JPG':
                $source = imagecreatefromjpeg($filename);
                break;

            case 'gif';
                $source = imagecreatefromgif($filename);
                break;
            
            case 'png':
                $source = imagecreatefrompng($filename);
                break;
            
            case 'PNG':
                $source = imagecreatefrompng($filename);
                break;
            
            default:
                return;
        }

        $cw = $cw ? $cw : $width;           // 생성할 이미지의 가로길이
        $ch = $ch ? $ch : $heigh;           // 생성할 이미지의 세로길이
        $w  = $w ? $w : $width;             // 원본이미지에서 자를부분의 가로길이
        $h  = $h ? $h : $height;            // 원본이미지에서 자를 부분의 세로길이
        $sX = $sX ? $sX : ($width-$w)/2;	// 원본이미지의 Start Point X
        $sY = $sY ? $sY : ($height-$h)/2;	// 원본이미지의 Start Point Y

        $thumb = imagecreatetruecolor($cw, $ch);

        imagealphablending($thumb, false);
        imagecopyresampled($thumb, $source, 0, 0, $sX, $sY, $cw, $ch, $w, $h);

        imagejpeg($thumb, $sfilename, 80);

        //로드한 메모리를 비워줍니다. gd는 꼭 이걸 해주어야 합니다. 
        ImageDestroy($thumb);
        ImageDestroy($source); 
    }

    function JHErrorMsg($RESULT, $MSG = "쿼리 오류가 발생하였습니다. 관리자에게 문의하시기 바랍니다.") {
        global $ETC_INFO, $_SERVER;

        if(!$RESULT) { 
            if($ETC_INFO['is_log']) {
                $logData = "[" . date("Y-m-d H:i:s") . "] - [" . $_SERVER['REQUEST_URI'] . "] - [" . mysqli_error() . "]";
                JHWriteLog($ETC_INFO['log_file'], $logData);
            }
            JHMoveURL("", $MSG, "", "back");
            JHExit(); 
        }
    }

    // ------------------------------------------------------------------------------
    // 로그 파일에 데이터 쓰기
    // ------------------------------------------------------------------------------
    function JHWriteLog($file_path, $filedata) {
        $fp = @fopen($file_path, 'a+');
        @flock($fp, 2);
        $filedata = stripslashes($filedata);
        @fwrite($fp, $filedata . "\n");
        @flock($fp, 3);
        @fclose($fp);
    }

    function JHExit() {
        global $CONNECT;

        if($CONNECT) mysqli_close($CONNECT);

        exit;
    }

    // 이미지 회전시키기
    function JHRotateImage( $filename )
    {
        if( empty($filename) || (FALSE == is_file( $filename )) ) return;
        
        if( function_exists('exif_read_data') && function_exists('imagecreatefromjpeg') && function_exists('imagerotate') )
        {
            //이미지 정보 가져오기.(정보가 없는 이미지는 패스)
            $exifData = exif_read_data( $filename );
            
            // 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨
            if( isset($exifData['Orientation']) )
            {
                if($exifData['Orientation'] == 6)
                {
                    $degree = 270;
                }
                // 반시계방향으로 90도 돌려줘야 정상
                else if ($exifData['Orientation'] == 8)
                {
                    $degree = 90;
                }
                else if ($exifData['Orientation'] == 3)
                {
                    $degree = 180;
                }
                if($degree)
                {
                    if($exifData['FileType'] == 1)
                    {
                        $source = imagecreatefromgif( $filename );
                        $source = imagerotate ($source , $degree, 0);
                        imagegif($source, $filename);
                    }
                    else if($exifData['FileType'] == 2)
                    {
                        $source = imagecreatefromjpeg( $filename );
                        $source = imagerotate ($source , $degree, 0);
                        imagejpeg($source, $filename);
                    }
                    else if($exifData['FileType'] == 3)
                    {
                        $source = imagecreatefrompng( $filename );
                        $source = imagerotate ($source , $degree, 0);
                        imagepng($source, $filename);
                    }
                    
                    imagedestroy($source);
                }
            }
        }
    }

	function paging($paging_url ,$LineCount, $PageCount, $TotalCount, $Page){

		$page_list = array();

		$sqaul_page = $TotalCount / $LineCount;

		if($sqaul_page < 0){
			$sqaul_page = 1;
		} else {
			$sqaul_page = ceil($sqaul_page);
		}
		$block = $Page / $PageCount;
		if($block <= 1){
			$block = 0;
		} else {
			$block = floor($block) * $PageCount;
		}

		if(($sqaul_page - $block) / $PageCount >= 1){
			$block_count = $PageCount;
		} else {
			$block_count = $sqaul_page;
		}

		$start = ($Page - 1) * $LineCount;
		$limit = $Page * $LineCount;

		// echo   "block_count : " . $block_count . "<br />" ."start : " . $start . "<br />" ."Page : " . $Page . "<br />" ."block : " . $block . "<br />" ."LineCount : " . $LineCount . "<br />" ."sqaul_page : " . $sqaul_page . "<br />" ."PageCount : " . $PageCount . "<br />";

		for($p=0; $p<=$PageCount; $p++){
			$chk = "";
			$num = $block + $p;
			if($Page == $num){
				$chk = "cur_page";
			}
            if($num <  1){
                continue;
            }
            if($num >  $sqaul_page){
                break;
            }

			$page_list[] = array(
				"chk"		    => $chk ,
				"page"	        => $num ,
				"sqaul_page"	=> $sqaul_page ,
				"url"		    => "{$paging_url}&Page={$num}" 
			);
		}

		$start_num = 1 ;
        if($start_num <= 0){
            $start_url = "#";
        } else {
		    $start_url = "{$paging_url}&Page=1";
        }
		
		$pre_num = $Page - 1 ;
        if($pre_num <= 0){
            $pre_url = "#";
        } else {
		    $pre_url = "{$paging_url}&Page={$pre_num}";
        }
		
		$after_num = $Page + 1 ;
        if($after_num > $sqaul_page){
		    $after_url = "#";
        } else {
		    $after_url = "{$paging_url}&Page={$after_num}";
        }

		$end_num = $Page + 1 ;
        if($end_num > $sqaul_page){
		    $end_url = "#";
        } else {
		    $end_url = "{$paging_url}&Page={$sqaul_page}";
        }

		$paging = array(
			"start"	        => $start ,
			"page_list"	    => $page_list ,
			"pre_url"		=> $pre_url ,
			"after_url"	    => $after_url ,	
			"start_url"		=> $start_url ,
			"end_url"		=> $end_url ,
		);

		return $paging;
	}

    function JHAgeCal($birth_year,$birth_month,$brith_day){
        $birth_year = (int)$birth_year;
        $birth_month = (int)$birth_month;
        $brith_day = (int)$brith_day;

        $now_year = date("Y");
        $now_month = date("m"); 
        $now_day = date("d");

        if($birth_month < $now_month){
           $age = $now_year - $birth_year;
        }else if($birth_month == $now_month){
         if($brith_day <= $now_day)
          $age = $now_year - $birth_year;
         else
          $age = $now_year - $birth_year -1;
        }else{
           $age = $now_year - $birth_year-1;
        }

        return $age;
    }
  
    // 특수 문자 제거 2019.01.02 key
    function JHSpecialremove($data){
        $data = preg_replace("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "", $data);
        $data = preg_replace('/[^\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}\w\s\[\]]/u','',$data);

		return $data;
    }
    
    // 알림톡 및 문자 함수
    // (alimtalk/sms/LMS),(알림톡:템플릿코드,그외:sid번호),수신번호,(알림톡:TRAN_SUBJECT,그외:테이블명 등),내용,발신번호,(알림톡:버튼json형태로,그외:''),(인슈어런스면 true)
    function JHMessageSend($send_mode, $temp_code, $send_num, $send_title, $send_contents, $callback_num, $send_button, $is_insurance = false){
        
        #--------------------------------------------------------------------
        # 알림톡 관련
        #--------------------------------------------------------------------
        //$is_insurance = false;
        switch ($temp_code) {
            case 'chabot-insu-mph-002':
            case 'chabot-insu-crm-001':
            case 'chabot-insu-mp-001':
            case 'chabot-insu-mp-002':
            case 'chabot-insu-mp-005':
            case 'chabot-insu-mp-006': 
            case 'chabot-service-package-001': 
            case 'chabot-service-package-003':     
            case 'chabot-service-package-004': 
            case 'chabot-service-package-005': 
            case 'chabot-service-package-006': 
            case 'chabot-service-package-007': 
            case 'chabot-service-package-008': 
            case 'chabot-service-package-009': 
            case 'chabot-service-package-010': 
                $SITE_INFO['message_key']   = "952d11614ea9c34fb7abfeb89d8bffbf41a0ca44";
                $is_insurance = true;
                break;
            default:
                $SITE_INFO['message_key']   = "e2bab540ea34d5f243b93f8f4b08f18d7fdc4327";
                break;
        }
        if(!$callback_num){
            $SITE_INFO['call_num']   = "18008765";
        } else {
            $SITE_INFO['call_num']   = $callback_num;
        }

        $SEND_SQL = "";
        switch($send_mode){
            case "alimtalk" : 
                $SEND_SQL = "
                    INSERT INTO MTS_ATALK_MSG
                       ( TRAN_SENDER_KEY, TRAN_TMPL_CD, TRAN_CALLBACK, TRAN_PHONE, TRAN_SUBJECT, TRAN_MSG, TRAN_DATE, TRAN_TYPE, TRAN_STATUS, TRAN_REPLACE_TYPE, TRAN_REPLACE_MSG )
                       VALUES
                       ( '{$SITE_INFO['message_key']}', '{$temp_code}', '{$SITE_INFO['call_num']}', '{$send_num}', '{$send_title}', '{$send_contents}', now(), 5, '1', 'S', '{$send_title}')
                ";
                if($is_insurance){
                    $SEND_SQL = "
                        INSERT INTO CI_MTS_ATALK_MSG
                            ( TRAN_SENDER_KEY, TRAN_TMPL_CD, TRAN_CALLBACK, TRAN_PHONE, TRAN_SUBJECT, TRAN_MSG, TRAN_DATE, TRAN_TYPE, TRAN_STATUS, TRAN_REPLACE_TYPE, TRAN_ETC1, TRAN_ETC2, TRAN_REPLACE_MSG, TRAN_BUTTON )
                        VALUES
                            ( '{$SITE_INFO['message_key']}', '{$temp_code}', '{$SITE_INFO['call_num']}', '{$send_num}', '{$send_title}', '{$send_contents}', now(), 5, '1', 'S', '', 'CRM', '{$send_contents}', '{$send_button}' )";    
                }

                break;

            case "sms" : 
                $SEND_SQL = "
                    INSERT INTO MTS_SMS_MSG(TRAN_PHONE,TRAN_CALLBACK,TRAN_MSG,TRAN_ETC1,TRAN_ETC2,TRAN_DATE)
                    VALUES ('{$send_num}','{$SITE_INFO['call_num']}','{$send_contents}','{$temp_code}','{$send_title}',now())
                ";
                if($is_insurance){
                    $SEND_SQL = "
                        INSERT INTO CI_MTS_SMS_MSG(TRAN_PHONE,TRAN_CALLBACK,TRAN_MSG,TRAN_ETC1,TRAN_ETC2,TRAN_DATE)
                        VALUES ('{$send_num}','{$SITE_INFO['call_num']}','{$send_contents}','{$temp_code}','{$send_title}',now())
                    ";
                }
                break;
            case "LMS" : 
                $SEND_SQL = "
                INSERT INTO
                    MTS_MMS_MSG
                (TRAN_PHONE,TRAN_CALLBACK,TRAN_MSG,TRAN_DATE,TRAN_ETC1,TRAN_ETC2)
                    VALUES 
                ('{$send_num}','{$SITE_INFO['call_num']}','{$send_contents}',now(),'{$temp_code}','{$send_title}')
                ";
                if($is_insurance){
                    $SEND_SQL = "
                        INSERT INTO
                            CI_MTS_MMS_MSG
                        (TRAN_PHONE,TRAN_CALLBACK,TRAN_MSG,TRAN_DATE,TRAN_ETC1,TRAN_ETC2)
                            VALUES 
                        ('{$send_num}','{$SITE_INFO['call_num']}','{$send_contents}',now(),'{$temp_code}','{$send_title}')
                    ";
                }
                break;
        }

		return $SEND_SQL;
    }

	function arr_sort($array, $key, $sort='asc') //정렬대상 array, 정렬 기준 key, 오름/내림차순
	{
		 $keys = array();
		 $vals = array();

		 foreach ($array as $k=>$v)	 {
			  $i = $v[$key].'.'.$k;
			  $vals[$i] = $v;
			  array_push($keys, $k);
		 }
		 unset($array);

		 if ($sort=='asc') {
		  ksort($vals);
		 } else {
		  krsort($vals);
		 }

		 $ret = array_combine($keys, $vals);
		 unset($keys);
		 unset($vals);
		 return $ret;
	}

    function JHDbEncode($str){
        $iv = "ZPsJc6(2W2*^F12k";
        $key = "p(079*Tt9I21eQrJ3gn^jLV*@u(dBc!6";

        $enc = openssl_encrypt($str, "AES-256-CBC", $key, true, $iv);
        $endata = base64_encode($enc);
        $endata = rawurlencode($endata);

        return $endata;
    }
    
    function JHDbDecode($str){
        $iv = "ZPsJc6(2W2*^F12k";
        $key = "p(079*Tt9I21eQrJ3gn^jLV*@u(dBc!6";

        $endata = rawurldecode($str);
        $dec = base64_decode($endata);
        $dedata = openssl_decrypt($dec, "AES-256-CBC", $key, true, $iv);

        return $dedata;
    }

    function JHTKEncode($str){
        $iv = "004DB1B5439CC36E96035BC6D371C3C5";
        $key = "62F638B5F55C08D460110C9F3C3AF854";
        
        $enc = openssl_encrypt($str, "AES-256-CBC", $key, true, $iv);
        $endata = base64_encode($enc);
        $endata = rawurlencode($endata);

        return $endata;
    }
    
    function JHTKDecode($str){
        $iv = "004DB1B5439CC36E96035BC6D371C3C5";
        $key = "62F638B5F55C08D460110C9F3C3AF854";

        $endata = rawurldecode($str);
        $dec = base64_decode($endata);
        $dedata = openssl_decrypt($dec, "AES-256-CBC", $key, true, $iv);

        return $dedata;
    }

	// HN TM 암호화
	function JHHnTmEncoding($str){
        //개발 : 암호화 방식 : AES256,  secretKey = "hhXUZvgVi0DjrlKxQvIgOg==", IV = "MjAyM0hBTkFJTlMxOTk2QQ==";
        //운영 : 암호화 방식 : AES256,  secretKey = "Tvble9lsViYUgslrMUszol==", IV = "MjAyM0hBTkFESldJREhBSw==";
        if( $_SERVER['SERVER_NAME'] == "dev.chabot.kr" ) {
            $key = "hhXUZvgVi0DjrIKxQvIgOg==";
            $iv = "MjAyM0hBTkFJTlMxOTk2QQ==";       //24자리
        } else {
            $key = "Tvble9IsViYUgsIrMUszol==";
            $iv = "MjAyM0hBTkFESldJREhBSw==";
        }

        $enc = openssl_encrypt($str, "AES-256-CTR", bin2hex(base64_decode($key)), true, base64_decode($iv));
        $endata = base64_encode($enc);  //urlencode 는 빼달라고 함.

        return $endata;

	}

	// HN TM 복호화
	function JHHnTmDecoding($str){
        //개발 : 암호화 방식 : AES256,  secretKey = "hhXUZvgVi0DjrlKxQvIgOg==", IV = "MjAyM0hBTkFJTlMxOTk2QQ==";
        //운영 : 암호화 방식 : AES256,  secretKey = "Tvble9lsViYUgslrMUszol==", IV = "MjAyM0hBTkFESldJREhBSw==";
        if( $_SERVER['SERVER_NAME'] == "dev.chabot.kr" ) {
            $key = "hhXUZvgVi0DjrIKxQvIgOg==";
            $iv = "MjAyM0hBTkFJTlMxOTk2QQ==";       //24자리
        } else {
            $key = "Tvble9IsViYUgsIrMUszol==";
            $iv = "MjAyM0hBTkFESldJREhBSw==";
        }

		$return_val = openssl_decrypt(base64_decode($str), 'AES-256-CTR', bin2hex(base64_decode($key)), true, base64_decode($iv));

		return $return_val;

	}

    function JBDbAcountCode($str){
        $return = "";
        switch($str){
            case 0 : $return = "미가입";
                    break;
            case 1 : $return = "가입";
                    break;
        }
        return $return;
    }

    function JBDbRelCode($str){
        $return = "";
        switch($str){
            case "A" : $return = "본인";
                    break;
            case "B" : $return = "배우자";
                    break;
            case "C" : $return = "부모";
                    break;
            case "D" : $return = "자녀";
                    break;
            case "E" : $return = "사위/며느리";
                    break;
            case "F" : $return = "형제/자매";
                    break;
            case "G" : $return = "친족(친척)";
                    break;
            case "H" : $return = "친구";
                    break;
            case "I" : $return = "손자손녀";
                    break;
            case "J" : $return = "임직원(기사)";
                    break;
            case "K" : $return = "배우자의 부모";
                    break;
            case "Z" : $return = "기타";
                    break;
        }
        return $return;
    }

    function JBDbSexCode($str){
        $return = "";
        switch($str){
            case "M" : $return = "남자";
                    break;
            case "F" : $return = "여자";
                    break;
            case "X" : $return = "태아";
                    break;
        }
        return $return;
    }

    function JHHdAmtCode($str){
        switch($str){
            case "15" : $return = "014";
                    break;
            case "30" : $return = "016";
                    break;
            case "50" : $return = "017";
                    break;
            case "100" : $return = "019";
                    break;
            case "200" : $return = "020";
                    break;
            case "300" : $return = "021";
                    break;
            case "500" : $return = "026";
                    break;
        }
        return $return;
    }

    function JHHdAcountCode($str){
        $return = "";
        switch($str){
            case 0 : $return = "025";
                    break;
            case 1 : $return = "024";
                    break;
        }
        return $return;
    }

    function JHInsureAcountCode($str, $insure_name){
        $return = "";
        switch($insure_name){
            case "DB" : 
                switch($str){
                    case "미가입" : $return = "0";
                            break;
                    case "가입" : $return = "1";
                            break;
                }
            break;

            case "HD" : 
                switch($str){
                    case "미가입" : $return = "025";
                            break;
                    case "가입" : $return = "024";
                            break;
                }
            break;
        }
        return $return;
    }
    
    function JHInsureAcountDeCode($str, $insure_name){
        $return = "";
        switch($insure_name){
            case "DB" : 
                switch($str){
                    case "0" : $return = "미가입";
                            break;
                    case "1" : $return = "가입";
                            break;
                }
            break;

            case "HD" : 
                switch($str){
                    case "025" : $return = "미가입";
                            break;
                    case "024" : $return = "가입";
                            break;
                }
            break;
            
            case "TK" : 
                switch($str){
                    case "0" : $return = "미가입";
                            break;
                    case "1" : $return = "가입";
                            break;
                }
            break;
        }
        return $return;
    }

    function JHDualArrayList($ori_array){
        $array_list = array();
        $key_array = array_keys($ori_array);
        for($ar=0; $ar < count($key_array); $ar++){
            $array_list[] = array(
                "key" => $key_array[$ar] ,
                "value" => $ori_array[$key_array[$ar]] ,
            );
        }

        return $array_list;
    }
    
    function JHArrayList($ori_array){

        for($ar=0; $ar < count($ori_array); $ar++){
            $array_list[] = array(
                "value" => $ori_array[$ar] ,
            );
        }

        return $array_list;
    }

    # $str = star처리할 변수 값 , $first_num = 첫자리부터 보이는 부분까지 자릿수 ,  $last_num = 마지막자리부터 보이는 부분까지 자릿수
    # ex. start => st**t
    # JHBlindStar(start, 2, 1)
    # ex. start => st***
    # JHBlindStar(start, 2) OR JHBlindStar(start, 2, 0)
    # 관리자의 경우에 *처리하지 않고 return
    function JHBlindStar($str, $first_num, $last_num) {
        // if($_SESSION['lvl'] != "HM") {
            $last_num = $last_num ? $last_num : 0;

            $return = mb_substr($str,0,$first_num);

            for($s = $first_num; $s < mb_strlen($str) - $last_num ; $s++){
                $return .= "*";
            }

            if(mb_strlen($str) > 2){
                if( $last_num != 0 ){
                    $return .= mb_substr($str, (int)$last_num * -1);
                }
            } else {    
                $return .= "*";
            }
            return $return;
        // }
        // else {
            // return $str;
        // }
    }

    // nice_auth_ret_url => 인증 후 페이지
    // is_mobile => 모바일 여부
    function JHNiceAuth($nice_auth_ret_url, $nice_auth_error_url){

        #--------------------------------------------------------------------
        # NICE 인증
        #--------------------------------------------------------------------
        $nice_auth_site_code = "BT554";			                            // NICE로부터 부여받은 사이트 코드
        $nice_auth_site_passwd = "c4fcHq1dPump";			                // NICE로부터 부여받은 사이트 패스워드
        // Linux = /절대경로/ , Window = D:\\절대경로\\ , D:\절대경로\
        $nice_auth_cb_encode_path = "/var/www/nice/CPClient_64bit";

        // 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드 (1가지만 사용 가능)
        $nice_auth_authtype = "M";      		
            
        //Y : 취소버튼 있음 / N : 취소버튼 없음
        $nice_auth_popgubun 	= "N";		

        //없으면 기본 웹페이지 / Mobile : 모바일페이지 (default값은 빈값, 환경에 맞는 화면 제공)
        $nice_auth_customize 	= "";		

        // 없으면 기본 선택화면, 0: 여자, 1: 남자
        $nice_auth_gender = "";      		

        // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
        $nice_auth_reqseq = "REQ_0123456789";

                                        
        // 실행방법은 싱글쿼터(`) 외에도, 'exec(), system(), shell_exec()' 등등 귀사 정책에 맞게 처리하시기 바랍니다.
        $nice_auth_reqseq = `$nice_auth_cb_encode_path SEQ $nice_auth_site_code`;

        $_SESSION['nice_info']["REQ_SEQ"] = $nice_auth_reqseq;

        // 입력될 plain 데이타를 만든다.
        $plaindata = "7:REQ_SEQ" . strlen($nice_auth_reqseq) . ":" . $nice_auth_reqseq .
                     "8:SITECODE" . strlen($nice_auth_site_code) . ":" . $nice_auth_site_code .
                     "9:AUTH_TYPE" . strlen($nice_auth_authtype) . ":". $nice_auth_authtype .
                     "7:RTN_URL" . strlen($nice_auth_ret_url) . ":" . $nice_auth_ret_url .
                     "7:ERR_URL" . strlen($nice_auth_error_url) . ":" . $nice_auth_error_url .
                     "11:POPUP_GUBUN" . strlen($nice_auth_popgubun) . ":" . $nice_auth_popgubun .
                     "9:CUSTOMIZE" . strlen($nice_auth_customize) . ":" . $nice_auth_customize .
                     "6:GENDER" . strlen($nice_auth_gender) . ":" . $nice_auth_gender ;
        
        $enc_data = `$nice_auth_cb_encode_path ENC $nice_auth_site_code $nice_auth_site_passwd $plaindata`;

        $returnMsg = "";

        if( $enc_data == -1 )
        {
            $returnMsg = "암/복호화 시스템 오류입니다.";
            $enc_data = "";
        }
        else if( $enc_data== -2 )
        {
            $returnMsg = "암호화 처리 오류입니다.";
            $enc_data = "";
        }
        else if( $enc_data== -3 )
        {
            $returnMsg = "암호화 데이터 오류 입니다.";
            $enc_data = "";
        }
        else if( $enc_data== -9 )
        {
            $returnMsg = "입력값 오류 입니다.";
            $enc_data = "";
        }

        return $enc_data;

    }
    
    //********************************************************************************************
    // 해당 함수에서 에러 발생 시 $len => (int)$len 로 수정 후 사용하시기 바랍니다. (하기소스 참고)
    // NICE 전용 함수
    //********************************************************************************************
	
    function JHGetValue($str , $name) 
    {
        $pos1 = 0;  //length의 시작 위치
        $pos2 = 0;  //:의 위치

        while( $pos1 <= strlen($str) )
        {
            $pos2 = strpos( $str , ":" , $pos1);
            $len = substr($str , $pos1 , $pos2 - $pos1);
            $key = substr($str , $pos2 + 1 , $len);
            $pos1 = $pos2 + $len + 1;
            if( $key == $name )
            {
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $value = substr($str , $pos2 + 1 , $len);
                return $value;
            }
            else
            {
                // 다르면 스킵한다.
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $pos1 = $pos2 + $len + 1;
            }            
        }
    }

    //********************************************************************************************
    // 시간 관련 함수
    //********************************************************************************************
    function JHConsultingTime(){
        $hour_start = 7;
        $hour_end = 20;
        $min_term = 10;
        $check_index = 0;
        $for_index = 0;

        $consulting_time_array = array();
        for($hs = $hour_start; $hs <= $hour_end; $hs++){
            $consulting_time_unit = "";
            for($ms = 0; $ms < 6; $ms++ ){
                $hour_unit = sprintf('%02d',$hs);
                $min_unit = sprintf('%02d',$ms * $min_term);
                $consulting_time_unit = $hour_unit . ":" . $min_unit;

                $is_checked = false;
                if( ( strtotime(date("H").":".substr(date("i"),0,1)."0") < strtotime($consulting_time_unit) ) && $is_checked == false){
                    $is_checked = true;
                    if($check_index == 0){
                        $check_index = $for_index;
                    }
                }

                $consulting_time_array[] = array(
                    "consulting_time"   => $consulting_time_unit            ,
                    "is_checked"        => $is_checked                      ,
                );

                $for_index++;
            }
        }

        $return_array = array(
            "check_index"               => $check_index                     ,
            "consulting_time_array"     => $consulting_time_array           ,
        );

        return $return_array;
        
    }
    function JHShortUrl($origin_url){
        GLOBAL $SITE_INFO;
        if($SITE_INFO['is_short_url']){
    
            $enc_origin_url = urlencode($origin_url);

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.chabot.kr/short_url/index.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "mode=short_url_enc&ori_url={$enc_origin_url}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $data = json_decode($response, true);

            $return_url  = $data['data']['return_result']['real_short_url'];
        } else {
            $return_url  = $origin_url;            
        }
        
        return $return_url;

    }

    // 1. 금액, 2. dealer 수수료율 ,3. 내부/외부 ( Y: 내부, N 외부), 4. chabot 수수료율, 5. chabot 추가 수수료율, 6. chabot 개인 수수료율, 7. chabot 개인 추가 수수료율, 8. chabot add promotion
    function capital_sales_cal($funding_value ,$dealer_commission_rate_value, $is_internal_payment_value, $chabot_commission_rate_value, $chabot_add_commission_rate_value, $chabot_personal_commission_rate_value, $chabot_personal_add_commission_rate_value, $partner_commission_rate_value, $chabot_add_promotion){

        $chabot_add_promotion = $chabot_add_promotion ? $chabot_add_promotion : 0;

        // 매출액 계산 법
        $dealer_sales_value = 0;
        $partner_sales_value = 0;
        $chabot_sales_value = 0;
        $chabot_real_sales_value = 0;
        switch($is_internal_payment_value){
            case "Y" : 
                // 딜러 수수료가 내부에서 계산 시 ( 딜러 수수료 * 1.1 ) + 차봇 수수료( 법인, 개인 )
                // step 1 : ( 펀딩 금액 * 딜러 수수료율 ) * 1.1 ( 외부 지급 시 부가세 부분 때문에 1.1 가산 )
                $dealer_sales_value = ( $funding_value * $dealer_commission_rate_value * 0.01 ) * 1.1;
                // step 2 : ( 펀딩 금액 * 제휴사 수수료율 )
                $partner_sales_value = ( $funding_value * $partner_commission_rate_value * 0.01 );
                // step 3 : ( 펀딩 금액 * 차봇 법인 수수료 ) + ( 펀딩 금액 * 차봇 개인인 수수료 )
                $chabot_sales_value = ( $funding_value * $chabot_commission_rate_value * 0.01 ) + ( $funding_value * $chabot_personal_commission_rate_value * 0.01 );
                // step 4 : ( 펀딩 금액 * 차봇 법인 수수료 ) +  ( 펀딩 금액 * 차봇 개인 수수료 ) + ( 펀딩 금액 * 차봇 법인 추가 수수료 ) + ( 펀딩 금액 * 차봇 개인 추가 수수료 )
                $chabot_real_sales_value = ( $funding_value * $chabot_commission_rate_value * 0.01 ) + ( $funding_value * $chabot_add_commission_rate_value * 0.01 ) + ( $funding_value * $chabot_personal_commission_rate_value * 0.01 ) + ( $funding_value * $chabot_personal_add_commission_rate_value * 0.01 );
                break;
            case "N" : 
                // 딜러 수수료가 외부에서 계산 시 ( 딜러 수수료 ) + 차봇 수수료( 법인, 개인 )
                // step 1 : 펀딩 금액 * 딜러 수수료율 ( 내부 지급 시 부가세 부분 없음 )
                $dealer_sales_value = ( $funding_value * $dealer_commission_rate_value * 0.01 );
                // step 2 : ( 펀딩 금액 * 제휴사 수수료율 )
                $partner_sales_value = ( $funding_value * $partner_commission_rate_value * 0.01 );
                // step 3 : 펀딩 금액 * 차봇 법인 수수료 
                $chabot_sales_value = ( $funding_value * $chabot_commission_rate_value * 0.01 ) + ( $funding_value * $chabot_personal_commission_rate_value * 0.01 );
                // step 4 : ( 펀딩 금액 * 차봇 법인 수수료 ) +  ( 펀딩 금액 * 차봇 개인 수수료 ) + ( 펀딩 금액 * 차봇 법인 추가 수수료 ) + ( 펀딩 금액 * 차봇 개인 추가 수수료 )
                $chabot_real_sales_value = ( $funding_value * $chabot_commission_rate_value * 0.01 ) + ( $funding_value * $chabot_add_commission_rate_value * 0.01 ) + ( $funding_value * $chabot_personal_commission_rate_value * 0.01 ) + ( $funding_value * $chabot_personal_add_commission_rate_value * 0.01 );
                break;
        }

        $chabot_sales_value = $chabot_sales_value + $chabot_add_promotion;
        $chabot_real_sales_value = $chabot_real_sales_value + $chabot_add_promotion;

        $return_array = array(
            "is_internal_payment_value"     => $is_internal_payment_value           , /* 내부, 외부 지급 */
            "dealer_sales_value"            => $dealer_sales_value                  , /* 딜러 영업이익 */
            "partner_sales_value"           => $partner_sales_value                 , /* 제휴사 영업이익 */
            "chabot_sales_value"            => $chabot_sales_value                  , /* 차봇 영업이익 */
            "chabot_real_sales_value"       => $chabot_real_sales_value             , /* 차봇 실제 영업이익 */
        );
        
        return $return_array;
    }

    function mb_str_split($str, $encoding){

        $ret = array();
        for ($i=0; $i<mb_strlen($str, $encoding); $i++){
           array_push($ret, mb_substr($str, $i, 1, $encoding));
        }
        return $ret;
    }

    
    /*
    * MJCustomerSid(customer_sid 생성 여부, etc_sid, 고객명, 주민번호앞자리, 주민번호뒷자리, 핸드폰1, 핸드폰2, 핸드폰3, 고객동의일) : 암호화 안 된 상태로 넘기기
    * 파라미터는 암호화 안 된 상태로 전달 받음
    * 고객의 기본 정보 전달 받은 후 CI생성, customer_sid 조회, 생성, 리턴 하는 함수
    * 리턴 : customer_sid
    */
    function MJCustomerSid($is_create = FALSE, $service, $etc_sid, $customer_name, $ssn_prefix, $ssn_suffix, $hp1, $hp2, $hp3, $agreedate, $marketing_agree_date = "0000-00-00 00:00:00", $agree_ip = ""){
        global $CONNECT;
        global $TABLE;

        //$agreedate = ($agreedate != "0000-00-00 00:00:00")?$agreedate: date("Y-m-d H:i:s") ;
        switch ($service){
            case "car_insure" :
                $add_query = " is_car = 'Y'            , ";
                $title = "[자동차보험 계약 - {$etc_sid}(견적)]\n";       //운전자 보험 -> 자동차 보험 순으로 고객 등록이 되어서 주민번호 뒷자리가 채워질때
                break;
            case "driver_insure" :
                $add_query = " is_driver = 'Y'            , ";
                $title = "[운전자보험 - {$etc_sid}]\n";
                break;
            case "capital" :
                $add_query = " is_capital = 'Y'            , ";
                $title = "[핀테크 - {$etc_sid}(erp_id)]\n";
                break;
        }

        if(0){
            echo "<pre>";
            print_r("is_create : " . $is_create . "\n");
            print_r("etc_sid : " . $etc_sid . "\n");
            print_r("customer_name : " . $customer_name . "\n");
            print_r("ssn_prefix : " . $ssn_prefix . "\n");
            print_r("ssn_suffix : " . $ssn_suffix . "\n");
            print_r("hp1 : " . $hp1 . "\n");
            print_r("hp2 : " . $hp2 . "\n");
            print_r("hp3 : " . $hp3 . "\n");
            print_r("agreedate : " . $agreedate . "\n");
            print_r("marketing_agree_date : " . $marketing_agree_date . "\n");
            print_r("agree_ip : " . $agree_ip . "\n");
            print_r("service : " . $service . "\n");
            echo "</pre>";
            exit;
        }

        #------------------------------------------------------------------------------
        # 고객 일련번호 가져오기    2023-02-15
        #------------------------------------------------------------------------------
        /*
        1. 주민번호 앞, 뒷자리가 있다면 개인 P -> 주민번호로 SSN
        2. 주민번호 앞자리만 있고 10자리 라면 법인 C -> 사업자번호로 SSN
        3. 주민번호가 잘 못 되었다면 연락처로 CI 만들기
        */
        $enc_customer_hp1 = JHSecretStart($hp1);
        $enc_customer_hp2 = JHSecretStart($hp2);
        $enc_customer_hp3 = JHSecretStart($hp3);
        $dec_ssn_prefix = preg_replace("/[^0-9]*/s", "", $ssn_prefix);
        $enc_ssn_prefix = JHSecretStart($dec_ssn_prefix);
        $enc_ssn_suffix = JHSecretStart($ssn_suffix);
        $companyArr = array("(주)","㈜","(유)","(우)","(수)","(사)","(명)","회사","법인");
        $re_customer_name = str_replace(" ","",$customer_name);
        if(strlen($dec_ssn_prefix) == 10 || (str_replace($companyArr, '', $re_customer_name) != $re_customer_name) ) {    //앞자리가 10자리라면 사업자등록번호 
            $customer_type = 'C';
        }else if(strlen($dec_ssn_prefix) == 8){      //앞자리가 8자리라면 생년월일
            $dec_ssn_prefix = substr($dec_ssn_prefix,-6);
            $customer_type = 'P';
        }else{
            $customer_type = 'P';
        }

        $ci_type  = "SSN";
        if($customer_type == 'C'){
            $ci_type = "SSN";
            $CI = JHSecretStart($dec_ssn_prefix);
        }else if($customer_type == 'P'){
            if(substr($ssn_suffix,-6) != '000000' && strlen($ssn_suffix) == 7){       //뒷자리가 비어있지 않다면
                $ci_type = "SSN";
                $CI = JHSecretStart($dec_ssn_prefix.$ssn_suffix);
            }else{     //뒷자리가 없다면
                if($hp1 == '010' && strlen($hp2) > 3 && strlen($hp3) > 3 && $hp2 > 0 && $hp3 > 0){       //연락처가 완벽하다면
                    $ci_type = "PHONE";
                    $CI = JHSecretStart($hp1.$hp2.$hp3);
                }else{
                    $ci_type = "ETC";
                    //$CI = JHSecretStart('000000'.sprintf('%07d',$etc_sid));   //etc_sid도 중복이 생길 수 있고 형식을 잡는게 의미가 없을 것 같음.     13자리
                    $CI = JHSecretStart( date('ymdhis') . rand(0,9) );   //13자리. ex) 2305161037038   
                }
            }
        }
        
        $customer_sid = "";

        //중복검사
        //중복 CI 있는지 확인.
        if( $CI ){        
            $SQL = "
                SELECT
                    sid, ci_type, name
                FROM
                    {$TABLE['customer']}
                WHERE
                    CI = '{$CI}'
            ";
            $dup_chk_ci = JHGetRow($CONNECT, $SQL);
            if($dup_chk_ci){
                if($dup_chk_ci['ci_type'] == "SSN"){
                    $customer_info = $dup_chk_ci;       //주민등록번호가 같다면 이건 같은 사람으로 보는게 맞지.
                    $customer_sid = $customer_info['sid'];
                }else if(str_replace(" ","",$dup_chk_ci['name']) == $re_customer_name){ //운전자보험은 같은 연락처로 다른 이름인 경우가 많음.
                    $customer_info = $dup_chk_ci;       //CI랑 이름까지 같다면 이건 같은 사람으로 보는게 맞지.
                    $customer_sid = $customer_info['sid'];
                }else{
                    //SSN이 아닌 PHONE, ETC의 CI와 중복일 경우 
                    $ci_type = "ETC";
                    $CI = JHSecretStart( date('ymdhis') . rand(0,9) );   //13자리. ex) 2305161037038   
                    $customer_sid = "";
                }
            }
        }


        //이름, 생년월일, 핸드폰 번호까지 일치하면
        //캐피탈로 먼저 들어온 고객은 생년월일이 없으므로 비어 있을 경우에는 이름, 연락처만 검색 할 수 있도록.
        //법인은 (주) 표시가 다를 경우가 있어서 name 을 like로 조회해야 함.
        if(!$customer_sid && strlen($dec_ssn_prefix) > 5 && $re_customer_name != '' && strlen($hp2) > 3 && strlen($hp3) > 3 && $hp2 > 0 && $hp3 > 0 ){
            $SQL = "
                SELECT
                    sid
                FROM
                    {$TABLE['customer']}
                WHERE
                    name LIKE '%{$re_customer_name}%'
                AND
                    ( ssn_prefix = 'z3Iw5+ezPj+WoB2cHjbXcw==' OR ssn_prefix is null OR ssn_prefix = '{$enc_ssn_prefix}' )
                AND
                    hp2 = '{$enc_customer_hp2}' AND hp3 = '{$enc_customer_hp3}'
                ORDER BY
                    FIELD(ci_type, 'NICE','SSN','PHONE','ETC'), sid desc
            ";
            $customer_info = JHGetRow($CONNECT, $SQL);
            $customer_sid = $customer_info['sid'];
        }

        //핀테크는 생년월일은 전혀 없기 때문에 이름, 연락처로만 검색. 하지만 주민번호가 다른 고객이 있을 수 있어서 우선순위 매칭.
        //캐피탈은 개인 고객만 존재. 법인 없음.
        //ci_type : NICE > SSN > PHONE > ETC 순으로 먼저 매칭하고, 같을 경우에는 최신 sid에 매칭하기.
        if(!$customer_sid && $service == "capital" && $re_customer_name != '' && strlen($hp2) > 3 && strlen($hp3) > 3 && $hp2 > 0 && $hp3 > 0 ){
            $SQL = "
                SELECT
                    sid
                FROM
                    {$TABLE['customer']}
                WHERE
                    name = '{$re_customer_name}'
                AND
                    hp2 = '{$enc_customer_hp2}' AND hp3 = '{$enc_customer_hp3}'
                ORDER BY
                    FIELD(ci_type, 'NICE','SSN','PHONE','ETC'), sid desc
            ";
            $customer_info = JHGetRow($CONNECT, $SQL);
            $customer_sid = $customer_info['sid'];
        }

        if($is_create){

            //필수 약관 동의 일시
            $agree_start_date = substr($agreedate,0,10);
            $agree_end_date = date('Y-m-d',strtotime($agreedate."+5 year"));

            if($marketing_agree_date != '0000-00-00 00:00:00'){    //선택적 마케팅 약관 동의 일시
                $marketing_agree_start_date = substr($marketing_agree_date,0,10);
                $marketing_agree_end_date = date('Y-m-d',strtotime($marketing_agree_date."+5 year"));
            } else {
                $marketing_agree_start_date = "0000-00-00";
                $marketing_agree_end_date = "0000-00-00";
            }

            if(!$customer_sid){ 
                //최초 생성이니까 동의 그대로 넣으면 됨.
                $SUB_SQL = "
                    INSERT INTO 
                        {$TABLE['customer']}
                    SET
                        ci			                = '{$CI}'			,
                        ci_type			        = '{$ci_type}'			,
                        customer_type			= '{$customer_type}'			,
                        name			            = '{$re_customer_name}'			,
                        ssn_prefix			    = '{$enc_ssn_prefix}'		,
                        ssn_suffix			    = '{$enc_ssn_suffix}'		,
                        hp1		                = '{$enc_customer_hp1}'	    ,
                        hp2		                = '{$enc_customer_hp2}'	    ,
                        hp3		                = '{$enc_customer_hp3}'	    ,
                        agreedate	            = '{$agreedate}'        ,
                        agree_start_date	    = '{$agree_start_date}'	        ,
                        agree_end_date	    = '{$agree_end_date}'	        ,
                        chabot_marketing_agree_start_date	    = '{$marketing_agree_start_date}'	        ,
                        chabot_marketing_agree_end_date	    = '{$marketing_agree_end_date}'	        ,
                        chabot_marketing_agree_ip	                = '{$agree_ip}'       ,
                        service_last_used_date	= SYSDATE()	        ,
                        {$add_query}
                        regdate		    		    = SYSDATE()
                ";
                $RESULT = JHExecSQL($CONNECT, $SUB_SQL);
                if(!$RESULT) {
                    JHTransactionRollback($CONNECT);

                    $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                    JHMoveURL("", $_MESSAGE, "", "back");
                    JHExit(); 
                }


                #------------------------------------------------------------------------------
                # 일련번호
                #------------------------------------------------------------------------------
                $SUB_SQL = "SELECT last_insert_id() sid";
                $ROW = JHGetRow($CONNECT, $SUB_SQL);
                $customer_sid = $ROW['sid'];
            }else{
                //$customer_sid = $customer_sid['sid'];
                    
                $SQL = "
                    SELECT
                        ci_type, agreedate, ssn_prefix, ssn_suffix, agree_start_date, agree_end_date, chabot_marketing_agree_start_date, chabot_marketing_agree_end_date
                    FROM
                        {$TABLE['customer']}
                    WHERE
                        sid = '{$customer_sid}'
                ";
                $ori_customer_info = JHGetRow($CONNECT, $SQL);


                //운전자보험에서 입력한 생년월일을 믿지말자. 자동차보험 계약이 제일 완벽하다고 생각을 하잣! 어차피 생년월일 앞자리만 바꾸는건 의미 없음.
                if( ($ori_customer_info['ci_type'] == 'PHONE' || $ori_customer_info['ci_type'] == 'ETC') && $ci_type == 'SSN'){      //기존 customer의 ci_type이 PHONE, ETC 였고, 이번에 SSN이 정확하게 존재한다면 수정하기.                    
                    //MJCustomerModify(서비스구분, 서비스 sid, 서비스 등록 시간, 고객 sid, 고객명, 주민번호앞자리, 주민번호뒷자리, 핸드폰1, 핸드폰2, 핸드폰3)
                    //MJCustomerModify($service, 0, $service_regdate, $customer_sid, $customer_name, $ori_customer_ssn_prefix, $ori_customer_ssn_suffix, $ori_customer_hp1, $ori_customer_hp2, $ori_customer_hp3);
                    //꼭 함수를 통해야 할까? 여기서 간단하게 처리해도 될 것 같은데..........
                    $customer_memo = $title;
                    $ori_ssn = JHSecretEnd($ori_customer_info['ssn_prefix'])."-".JHSecretEnd($ori_customer_info['ssn_suffix']);
                    $customer_ssn = $ssn_prefix."-".$ssn_suffix;

                    if(str_replace("-","",$customer_ssn) != str_replace("-","",$ori_ssn)){
                        if($ori_ssn == ''){
                            $ori_ssn = "값 없음";
                        }
                        $customer_memo .= "고객주민번호 변경으로 인한 CI값 수정\n";
                        $customer_memo .= "고객CI 타입 변경 : " . $ori_customer_info['ci_type'] . " -> " . $ci_type ."\n";
                        $customer_memo .= "고객주민번호 변경 : " . $ori_ssn . " -> " . $customer_ssn;

                        $SUB_SQL = "
                            UPDATE
                                {$TABLE['customer']}
                            SET
                                ci_type			        = '{$ci_type}'			,
                                ci			                = '{$CI}'			,
                                ssn_prefix	                = '{$enc_ssn_prefix}'			,
                                ssn_suffix	                = '{$enc_ssn_suffix}'			,
                                uptdate                  = SYSDATE()
                            WHERE
                                sid = '{$customer_sid}'
                        ";
                        $RESULT = JHExecSQL($CONNECT, $SUB_SQL);
                        if(!$RESULT) {
                            JHTransactionRollback($CONNECT);

                            $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                            JHMoveURL("", $_MESSAGE, "", "back");
                            JHExit(); 
                        }

                        // 고객 정보 수정 이력 등록
                        $consulting_date = date('Y-m-d');
                        $SQL = "
                            INSERT INTO
                                {$TABLE['customer_consulting']}
                            SET
                                member_sid          = '{$_SESSION['sid']}'                                  ,
                                customer_sid        = '{$customer_sid}'                                              ,
                                category            = '정보변경'                                             ,
                                memo                = '{$customer_memo}'                                             ,
                                consulting_date     = '{$consulting_date}'                                             ,
                                status              = 'Y'                                                   ,
                                uptdate             = '0000-00-00 00:00:00'                                 ,
                                regdate             = SYSDATE()
                        ";
                        $RESULT = JHExecSQL($CONNECT, $SQL);
                        if(!$RESULT) {
                            $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                            JHMoveURL("", $_MESSAGE, "", "back");
                            JHExit(); 
                        }
                    }
                }

                //agree_start_date는 업데이트 하지 않음.
                //녹취 동의 같은 경우는 마케팅 동의가 없고, URL일 경우에는 웹으로 이미 업데이트 되었기 때문에 여기서 중복으로 해 줄 필요 없음.
                $customer_agreedate = ( $agreedate > $ori_customer_info['agreedate'] ) ? $agreedate : $ori_customer_info['agreedate'] ;
                $agree_end_date = ( $agree_end_date > $ori_customer_info['agree_end_date'] ) ? $agree_end_date : $ori_customer_info['agree_end_date'] ;

                $SUB_SQL = "
                    UPDATE
                        {$TABLE['customer']}
                    SET
                        agreedate	                = '{$customer_agreedate}'        ,
                        agree_end_date	        = '{$agree_end_date}'	        ,
                        service_last_used_date	= SYSDATE()           ,
                        status                        = 'Y'	            ,
                        chkdel                       = 'N'	            ,
                        {$add_query}
                        uptdate                     = SYSDATE()
                    WHERE
                        sid = '{$customer_sid}'
                ";
                $RESULT = JHExecSQL($CONNECT, $SUB_SQL);
                if(!$RESULT) {
                    JHTransactionRollback($CONNECT);

                    $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                    JHMoveURL("", $_MESSAGE, "", "back");
                    JHExit(); 
                }
                
                if($marketing_agree_end_date != '0000-00-00' && $marketing_agree_end_date > $ori_customer_info['chabot_marketing_agree_end_date']){
                    //개인정보동의시 CI가 일치하지 않아서 선택적 마케팅 동의 업데이트 되지 않은 경우가 있음. 마케팅 동의 있는지 확인해서 업데이트 해줘야 함.
                    $chabot_marketing_agree_start_date = ( $ori_customer_info['chabot_marketing_agree_start_date'] != '0000-00-00' ) ? $ori_customer_info['chabot_marketing_agree_start_date'] : $marketing_agree_start_date;
                    $SQL = "
                        UPDATE
                            {$TABLE['customer']}
                        SET
                            chabot_marketing_agree_start_date	    = '{$chabot_marketing_agree_start_date}'	        ,
                            chabot_marketing_agree_end_date	    = '{$marketing_agree_end_date}'	        ,
                            chabot_marketing_refuse_date	        = '0000-00-00'	        ,
                            status                    = 'Y'	            ,
                            chkdel                    = 'N'	            ,
                            uptdate                  = SYSDATE()
                        WHERE
                            sid = '{$customer_sid}'
                    ";
                    $RESULT = JHExecSQL($CONNECT, $SQL);
                    if(!$RESULT) {
                        JHTransactionRollback($CONNECT);

                        $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                        JHMoveURL("", $_MESSAGE, "", "back");
                        JHExit(); 
                    }
                }
            }
            
            $customer_memo = "";
            if($agree_end_date > $ori_customer_info['agree_end_date']){
                $customer_memo .= "필수 동의 : " . $agree_start_date ." ~ " . $agree_end_date . "\n";
            }
            if($marketing_agree_end_date != '0000-00-00' && $marketing_agree_end_date > $ori_customer_info['chabot_marketing_agree_end_date']){
                $customer_memo .= "선택적 마케팅 동의 : " . $marketing_agree_start_date ." ~ " . $marketing_agree_end_date . "\n";
            }

            if($customer_memo != ""){           
                $customer_memo = $title . $customer_memo;

                // 고객 정보 수정 이력 등록
                $consulting_date = date('Y-m-d');
                $SQL = "
                    INSERT INTO
                        {$TABLE['customer_consulting']}
                    SET
                        member_sid          = '0'                                  ,
                        customer_sid        = '{$customer_sid}'                                              ,
                        category            = '고객동의'                                             ,
                        memo                = '{$customer_memo}'                                             ,
                        consulting_date     = '{$consulting_date}'                                             ,
                        status              = 'Y'                                                   ,
                        uptdate             = '0000-00-00 00:00:00'                                 ,
                        regdate             = SYSDATE()
                ";
                $RESULT = JHExecSQL($CONNECT, $SQL);
                if(!$RESULT) {
                    $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                    JHMoveURL("", $_MESSAGE, "", "back");
                    JHExit(); 
                }
            }
        }/*else{
            $customer_sid = $customer_sid['sid'];
        }    */    

        $ci_info = array(
            "customer_sid" => $customer_sid     ,
            "ci_type" => $ci_type     ,
            "CI"       => $CI     ,
        );

        return $ci_info;
    }

    /*
    * MJCustomerModify(서비스구분, 서비스 sid, 서비스 등록 시간, 고객 sid, 고객명, 주민번호앞자리, 주민번호뒷자리, 핸드폰1, 핸드폰2, 핸드폰3)
    * 파라미터는 암호화 안 된 상태로 전달 받음
    * 이용자 사용중 서비스 체크. 
    * 리턴 : customer_sid
    */
    function MJCustomerModify($service, $sid, $service_regdate, $customer_sid, $customer_name, $ssn_prefix, $ssn_suffix, $hp1, $hp2, $hp3){
        global $CONNECT;
        global $TABLE;
            
        $is_modify = FALSE;        

        if(0){
            echo "<pre>";
            print_r("service : " . $service . "\n");
            print_r("sid : " . $sid . "\n");
            print_r("service_regdate : " . $service_regdate . "\n");
            print_r("customer_sid : " . $customer_sid . "\n");
            print_r("customer_name : " . $customer_name . "\n");
            print_r("ssn_prefix : " . $ssn_prefix . "\n");
            print_r("ssn_suffix : " . $ssn_suffix . "\n");
            print_r("hp1 : " . $hp1 . "\n");
            print_r("hp2 : " . $hp2 . "\n");
            print_r("hp3 : " . $hp3 . "\n");
            echo "</pre>";
            exit;
        }

        
        $enc_customer_hp1 = JHSecretStart($hp1);
        $enc_customer_hp2 = JHSecretStart($hp2);
        $enc_customer_hp3 = JHSecretStart($hp3);

        #--------------------------------------------------------------------
        # 고객 정보 변경 내역 저장
        #--------------------------------------------------------------------
        $SQL = "
            SELECT
                *
            FROM
                {$TABLE['customer']}
            WHERE
                sid = '{$customer_sid}'
        ";
        $ori_customer_info = JHGetRow($CONNECT, $SQL);
        if(date("Y-m-d H:i:s", strtotime($ori_customer_info['service_last_used_date']." -10 seconds")) <= $service_regdate){      //서비스 등록 시간이 고객 마지막 사용일보다 크다면 고객 데이터 적극적으로 수정하기
            $is_modify = TRUE;
        }
        $ci_type = $ori_customer_info['ci_type'];
        $CI = $ori_customer_info['ci'];
        //$customer_type = $ori_customer_info['customer_type'];

        $customer_modi_chk = FALSE;
        $customer_ci_modi_chk = FALSE;
        switch ($service){
            case "car_insure" :
                $title = "[자동차보험 계약 - {$sid}]\n";
                break;
            case "driver_insure" :
                $title = "[운전자보험 - {$sid}]\n";
                break;
            case "capital" :
                $title = "[핀테크 - {$sid}]\n";
                break;
        }
        $customer_memo = $title;
 
        $customer_name = str_replace(" ","",$customer_name);    //혹시 있을지 모를 공백 제거.
        $ori_customer_info['name'] = str_replace(" ","",$ori_customer_info['name']);    //혹시 있을지 모를 공백 제거.
                
        // 고객명 체크
        if($customer_name != $ori_customer_info['name']){
            if(!$ori_customer_info['name']){
                $ori_customer_info['name'] = "값 없음";
            }
            $customer_memo .= "고객명 변경 : " . $ori_customer_info['name'] . " -> " . $customer_name . "\n";
            $customer_modi_chk = TRUE;
        
        }

        $dec_ssn_prefix = preg_replace("/[^0-9]*/s", "", $ssn_prefix);
        $companyArr = array("(주)","㈜","(유)","(우)","(수)","(사)","(명)","회사","법인");
        $re_customer_name = str_replace(" ","",$customer_name);
        if(strlen($dec_ssn_prefix) == 10 || (str_replace($companyArr, '', $re_customer_name) != $re_customer_name) ) {    //앞자리가 10자리라면 사업자등록번호 
            $customer_type = 'C';
        }else if(strlen($dec_ssn_prefix) == 8){      //앞자리가 8자리라면 생년월일
            $ssn_prefix = substr($dec_ssn_prefix,-6);
            $customer_type = 'P';
        }else{
            $customer_type = 'P';
        }
        // 고객타입 체크
        if($customer_type != $ori_customer_info['customer_type']){
            if(!$ori_customer_info['customer_type']){
                $ori_customer_info['customer_type'] = "값 없음";
            }
            $customer_memo .= "고객타입 변경 : " . $ori_customer_info['customer_type'] . " -> " . $customer_type . "\n";
            $customer_modi_chk = TRUE;
        
        }

        // 고객연락처 체크
        $ori_customer_info['hp1'] = JHSecretEnd($ori_customer_info['hp1']);
        $ori_customer_info['hp2'] = JHSecretEnd($ori_customer_info['hp2']);
        $ori_customer_info['hp3'] = JHSecretEnd($ori_customer_info['hp3']);
        $ori_customer_hp = $ori_customer_info['hp1'].'-'.$ori_customer_info['hp2'].'-'.$ori_customer_info['hp3'];
        $customer_hp = $hp1."-".$hp2."-".$hp3;
        if($customer_hp != $ori_customer_hp){
            if($ori_customer_hp == '--'){
                $ori_customer_hp = "값 없음";
            }
            $customer_memo .= "고객연락처 변경 : " . $ori_customer_hp . " -> " . $customer_hp . "\n";
            $customer_modi_chk = TRUE;
        
        }
        // 고객주민번호 체크
        $enc_ssn_prefix = JHSecretStart($dec_ssn_prefix);
        $enc_ssn_suffix = JHSecretStart($ssn_suffix);
        $ori_ssn = JHSecretEnd($ori_customer_info['ssn_prefix'])."-".JHSecretEnd($ori_customer_info['ssn_suffix']);
        $customer_ssn = $ssn_prefix."-".$ssn_suffix;
        if(str_replace("-","",$customer_ssn) != str_replace("-","",$ori_ssn) && $ori_customer_info['ci_type'] != 'NICE' && $service == 'car_insure'){
            if($ori_ssn == ''){
                $ori_ssn = "값 없음";
            }
            $customer_memo .= "고객주민번호 변경 : " . $ori_ssn . " -> " . $customer_ssn . "\n";
            $customer_modi_chk = TRUE;
        }

        $consulting_date = date('Y-m-d');
        $search_customer_sid = $customer_sid;

        if($customer_modi_chk == TRUE){     //CI변경
            //주민등록번호, 연락처 변경으로 인해 CI가 달라졌을 경우            
            //1. 변경된 CI로 존재하는 고객이 있는지 확인 (CI 중복여부 체크)
            //$make_ci_info = MJMakeCI($customer_name, $ssn_prefix, $ssn_suffix, $hp1, $hp2, $hp3);
            //MJCustomerSid(customer_sid 생성 여부, etc_sid, 고객명, 주민번호앞자리, 주민번호뒷자리, 핸드폰1, 핸드폰2, 핸드폰3, 고객동의일) : 암호화 안 된 상태로 넘기기
            $ci_info = MJCustomerSid(FALSE, $service, $sid, $customer_name, $ssn_prefix, $ssn_suffix, $hp1, $hp2, $hp3, $agreedate);
            $search_customer_sid = ($ci_info['customer_sid']) ? $ci_info['customer_sid'] : "" ;

            if($ci_info['CI'] != $CI && ($ci_info['ci_type'] == 'SSN' || $ci_info['ci_type'] == 'PHONE')){        //기존 CI와 새로 조회 한 CI가 다르다면
                $customer_ci_modi_chk = TRUE;
                $ci_type = $ci_info['ci_type'];
                $CI = $ci_info['CI'];
            }

            //2. 변경된 CI로 존재하는 고객이 있다면 그 고객으로 재매칭. $customer_ssn_modi_chk == true 이기 때문에 현재 customer_sid의 CI랑 다른건 확실.
            if($search_customer_sid && $search_customer_sid != $customer_sid){
                switch ($service){
                    case "car_insure" :
                        //검색한 customer_sid로 재연결.
                        $SQL = "
                            UPDATE
                                {$TABLE['contract']}
                            SET
                                customer_sid	= '{$search_customer_sid}'
                            WHERE
                                sid			        = '{$sid}'
                        ";
                        $RESULT = JHExecSQL($CONNECT, $SQL);
                        if(!$RESULT) {
                            $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                            JHMoveURL("", $_MESSAGE, "", "back");
                            JHExit(); 
                        }

                        //자동차보험 계약의 customer_sid가 변경되었을 경우 request_info의 customer_sid도 업데이트 해주기.
                        $SQL = "
                            SELECT
                                request_sid
                            FROM
                                {$TABLE['contract']}
                            WHERE
                                sid = '{$sid}'
                        ";
                        $contract_info = JHGetRow($CONNECT, $SQL);
                        $SQL = "
                            UPDATE
                                {$TABLE['request']}
                            SET
                                customer_sid	= '{$search_customer_sid}'
                            WHERE
                                sid			        = '{$contract_info['request_sid']}'
                        ";
                        $RESULT = JHExecSQL($CONNECT, $SQL);
                        if(!$RESULT) {
                            $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                            JHMoveURL("", $_MESSAGE, "", "back");
                            JHExit(); 
                        }
                        break;
                    case "driver_insure" :
                        //검색한 customer_sid로 재연결. 운전자보험은 driver_contract 계약쪽도 같이 변경 필요. 검수 필요.
                        $SQL = "
                            UPDATE
                                {$TABLE['driver_insure']}
                            SET
                                customer_sid	= '{$search_customer_sid}'
                            WHERE
                                sid			        = '{$sid}'
                        ";
                        $RESULT = JHExecSQL($CONNECT, $SQL);
                        if(!$RESULT) {
                            $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                            JHMoveURL("", $_MESSAGE, "", "back");
                            JHExit(); 
                        }
                        break;
                    case "capital" :
                        //검색한 customer_sid로 재연결.
                        $SQL = "
                            UPDATE
                                {$TABLE['capital_contract_temp']}
                            SET
                                customer_sid	= '{$search_customer_sid}'
                            WHERE
                                sid			        = '{$sid}'
                        ";
                        $RESULT = JHExecSQL($CONNECT, $SQL);
                        if(!$RESULT) {
                            $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                            JHMoveURL("", $_MESSAGE, "", "back");
                            JHExit(); 
                        }
                        break;
                }

                // 기존 고객 sid에 고객 정보 수정 이력 등록
                $SQL = "
                    INSERT INTO
                        {$TABLE['customer_consulting']}
                    SET
                        member_sid          = '{$_SESSION['sid']}'                                  ,
                        customer_sid        = '{$customer_sid}'                                              ,
                        category            = '정보변경'                                             ,
                        memo                = '{$title}고객 식별값 변경으로 인한 재매칭 {$customer_sid} -> {$search_customer_sid}'                                             ,
                        consulting_date     = '{$consulting_date}'                                             ,
                        status              = 'Y'                                                   ,
                        uptdate             = '0000-00-00 00:00:00'                                 ,
                        regdate             = SYSDATE()
                ";
                $RESULT = JHExecSQL($CONNECT, $SQL);
                if(!$RESULT) {
                    $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                    JHMoveURL("", $_MESSAGE, "", "back");
                    JHExit(); 
                }

                // 재매칭한 고객 sid에 고객 정보 수정 이력 등록
                $SQL = "
                    INSERT INTO
                        {$TABLE['customer_consulting']}
                    SET
                        member_sid          = '{$_SESSION['sid']}'                                  ,
                        customer_sid        = '{$search_customer_sid}'                                              ,
                        category            = '정보변경'                                             ,
                        memo                = '{$title}고객 식별값 변경으로 인한 재매칭 {$customer_sid} -> {$search_customer_sid}'                                             ,
                        consulting_date     = '{$consulting_date}'                                             ,
                        status              = 'Y'                                                   ,
                        uptdate             = '0000-00-00 00:00:00'                                 ,
                        regdate             = SYSDATE()
                ";
                $RESULT = JHExecSQL($CONNECT, $SQL);
                if(!$RESULT) {
                    $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                    JHMoveURL("", $_MESSAGE, "", "back");
                    JHExit(); 
                }
            }else{ //바뀐 주민번호로 존재하는 고객이 없다면 현재 customer_sid의 고객 정보를 업데이트.
                $search_customer_sid = $customer_sid;   //기존것을 수정했을 경우
                //서비스 등록 시간이 고객 마지막 사용일보다 크고 CI 정확도가 높다면 고객 정보(CI)를 적극적으로 변경하고, 그 외의 경우에는 계약 변경 이력만 남기기. 
                if( $is_modify ){
                    if( $ori_customer_info['ci_type'] != 'NICE' && $ci_type == 'SSN' ){
                        if($customer_ci_modi_chk){
                            $SUB_SQL = "
                                UPDATE
                                    {$TABLE['customer']}
                                SET
                                    ci_type			        = '{$ci_type}'			,
                                    ci			                = '{$CI}'			,
                                    customer_type			= '{$customer_type}'  ,
                                    name			            = '{$customer_name}'  ,
                                    ssn_prefix	                = '{$enc_ssn_prefix}'			,
                                    ssn_suffix	                = '{$enc_ssn_suffix}'			,
                                    hp1		                = '{$enc_customer_hp1}'	    ,
                                    hp2		                = '{$enc_customer_hp2}'	    ,
                                    hp3		                = '{$enc_customer_hp3}'	    ,
                                    uptdate                  = SYSDATE()
                                WHERE
                                    sid = '{$customer_sid}'
                            ";
                            $RESULT = JHExecSQL($CONNECT, $SUB_SQL);
                            if(!$RESULT) {
                                JHTransactionRollback($CONNECT);

                                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                                JHMoveURL("", $_MESSAGE, "", "back");
                                JHExit(); 
                            }

                            // 고객 정보 수정 이력 등록
                            $SQL = "
                                INSERT INTO
                                    {$TABLE['customer_consulting']}
                                SET
                                    member_sid          = '{$_SESSION['sid']}'                                  ,
                                    customer_sid        = '{$customer_sid}'                                              ,
                                    category            = '정보변경'                                             ,
                                    memo                = '고객식별값 변경으로 인한 CI값 수정. {$ori_customer_info['ci_type']} => {$ci_type}'                                             ,
                                    consulting_date     = '{$consulting_date}'                                             ,
                                    status              = 'Y'                                                   ,
                                    uptdate             = '0000-00-00 00:00:00'                                 ,
                                    regdate             = SYSDATE()
                            ";
                            $RESULT = JHExecSQL($CONNECT, $SQL);
                            if(!$RESULT) {
                                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                                JHMoveURL("", $_MESSAGE, "", "back");
                                JHExit(); 
                            }
                        }else{
                            $SUB_SQL = "
                                UPDATE
                                    {$TABLE['customer']}
                                SET
                                    customer_type			= '{$customer_type}'  ,
                                    name			            = '{$customer_name}'  ,
                                    hp1		                = '{$enc_customer_hp1}'	    ,
                                    hp2		                = '{$enc_customer_hp2}'	    ,
                                    hp3		                = '{$enc_customer_hp3}'	    ,
                                    uptdate                  = SYSDATE()
                                WHERE
                                    sid = '{$customer_sid}'
                            ";
                            $RESULT = JHExecSQL($CONNECT, $SUB_SQL);
                            if(!$RESULT) {
                                JHTransactionRollback($CONNECT);

                                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                                JHMoveURL("", $_MESSAGE, "", "back");
                                JHExit(); 
                            }
                        }
                    }else if( ($ori_customer_info['ci_type'] == 'PHONE' || $ori_customer_info['ci_type'] == 'ETC') && $ci_type == 'PHONE' ){
                        if($customer_ci_modi_chk){
                            $SUB_SQL = "
                                UPDATE
                                    {$TABLE['customer']}
                                SET
                                    ci_type			        = '{$ci_type}'			,
                                    ci			                = '{$CI}'			,
                                    customer_type			= '{$customer_type}'  ,
                                    name			            = '{$customer_name}'  ,
                                    hp1		                = '{$enc_customer_hp1}'	    ,
                                    hp2		                = '{$enc_customer_hp2}'	    ,
                                    hp3		                = '{$enc_customer_hp3}'	    ,
                                    uptdate                  = SYSDATE()
                                WHERE
                                    sid = '{$customer_sid}'
                            ";
                            $RESULT = JHExecSQL($CONNECT, $SUB_SQL);
                            if(!$RESULT) {
                                JHTransactionRollback($CONNECT);

                                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                                JHMoveURL("", $_MESSAGE, "", "back");
                                JHExit(); 
                            }

                            // 고객 정보 수정 이력 등록
                            $SQL = "
                                INSERT INTO
                                    {$TABLE['customer_consulting']}
                                SET
                                    member_sid          = '{$_SESSION['sid']}'                                  ,
                                    customer_sid        = '{$customer_sid}'                                              ,
                                    category            = '정보변경'                                             ,
                                    memo                = '고객식별값 변경으로 인한 CI값 수정. {$ori_customer_info['ci_type']} => {$ci_type}'                                             ,
                                    consulting_date     = '{$consulting_date}'                                             ,
                                    status              = 'Y'                                                   ,
                                    uptdate             = '0000-00-00 00:00:00'                                 ,
                                    regdate             = SYSDATE()
                            ";
                            $RESULT = JHExecSQL($CONNECT, $SQL);
                            if(!$RESULT) {
                                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                                JHMoveURL("", $_MESSAGE, "", "back");
                                JHExit(); 
                            }
                        }else{
                            $SUB_SQL = "
                                UPDATE
                                    {$TABLE['customer']}
                                SET
                                    name			            = '{$customer_name}',
                                    uptdate                  = SYSDATE()
                                WHERE
                                    sid = '{$customer_sid}'
                            ";
                            $RESULT = JHExecSQL($CONNECT, $SUB_SQL);
                            if(!$RESULT) {
                                JHTransactionRollback($CONNECT);

                                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                                JHMoveURL("", $_MESSAGE, "", "back");
                                JHExit(); 
                            }
                        }
                    }
                }
            }

            // 고객 정보 수정 이력은 모두 남기기.
            $SQL = "
                INSERT INTO
                    {$TABLE['customer_consulting']}
                SET
                    member_sid          = '{$_SESSION['sid']}'                                  ,
                    customer_sid        = '{$customer_sid}'                                              ,
                    category            = '정보변경'                                             ,
                    memo                = '{$customer_memo}'                                             ,
                    consulting_date     = '{$consulting_date}'                                             ,
                    status              = 'Y'                                                   ,
                    uptdate             = '0000-00-00 00:00:00'                                 ,
                    regdate             = SYSDATE()
            ";
            $RESULT = JHExecSQL($CONNECT, $SQL);
            if(!$RESULT) {
                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                JHMoveURL("", $_MESSAGE, "", "back");
                JHExit(); 
            }

            if($search_customer_sid && $search_customer_sid != $customer_sid){     //재매칭 한 customer_sid가 있다면 그곳에도
                // 고객 정보 수정 이력 등록
                $SQL = "
                    INSERT INTO
                        {$TABLE['customer_consulting']}
                    SET
                        member_sid          = '{$_SESSION['sid']}'                                  ,
                        customer_sid        = '{$search_customer_sid}'                                              ,
                        category            = '정보변경'                                             ,
                        memo                = '{$customer_memo}'                                             ,
                        consulting_date     = '{$consulting_date}'                                             ,
                        status              = 'Y'                                                   ,
                        uptdate             = '0000-00-00 00:00:00'                                 ,
                        regdate             = SYSDATE()
                ";
                $RESULT = JHExecSQL($CONNECT, $SQL);
                if(!$RESULT) {
                    $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                    JHMoveURL("", $_MESSAGE, "", "back");
                    JHExit(); 
                }
            }            

        }


        return $search_customer_sid;
    }
    

    
    /*
    * MJVipCustomerSid(고객 생성 여부, 이용서비스, 서비스 sid, ori_vip_customer_sid, cm_sid, 고객타입, customer_sid, 고객명, 핸드폰1, 핸드폰2, 핸드폰3)
    * 파라미터는 암호화 안 된 상태로 전달 받음
    * 고객의 기본 정보 전달 받은 후 CI생성, customer_sid 조회, 생성, 리턴 하는 함수
    * 리턴 : customer_sid
    */
    function MJVipCustomerSid($is_create = FALSE, $service, $service_sid, $ori_vip_customer_sid, $cm_sid, $customer_type, $customer_sid, $customer_name, $hp1, $hp2, $hp3){
        global $CONNECT;
        global $TABLE;

        switch ($service){
            case "car_insure" :
                $add_query = " is_car = 'Y'            , ";
                $title = "[자동차보험 계약 - {$service_sid} 에서 vip_customer_sid 매칭]\n";
                break;
            case "driver_insure" :
                $add_query = " is_driver = 'Y'            , ";
                $title = "[운전자보험 - {$service_sid} 에서 vip_customer_sid 매칭]\n";
                break;
            case "offline" :
                $add_query = " is_car = 'Y'            , ";
                $title = "[자동차보험 견적 - {$service_sid} 에서 vip_customer_sid 매칭]\n";
                break;
            case "request" :
                $add_query = " is_car = 'Y'            , ";
                $title = "[자동차보험 견적 - {$service_sid} 에서 vip_customer_sid 매칭]\n";
                break;
        }

        if(0){
            echo "<pre>";
            print_r("is_create : " . $is_create . "\n");
            print_r("service : " . $service . "\n");
            print_r("service_sid : " . $service_sid . "\n");
            print_r("ori_vip_customer_sid : " . $ori_vip_customer_sid . "\n");
            print_r("cm_sid : " . $cm_sid . "\n");
            print_r("customer_type : " . $customer_type . "\n");
            print_r("customer_sid : " . $customer_sid . "\n");
            print_r("customer_name : " . $customer_name . "\n");
            print_r("hp1 : " . $hp1 . "\n");
            print_r("hp2 : " . $hp2 . "\n");
            print_r("hp3 : " . $hp3 . "\n");
            echo "</pre>";
            exit;
        }
        
        $ori_customer_hp1 = $hp1;
        $ori_customer_hp2 = $hp2;
        $ori_customer_hp3 = $hp3;
        $hp1 = JHSecretStart($hp1);
        $hp2 = JHSecretStart($hp2);
        $hp3 = JHSecretStart($hp3);

        //견적때는 임혁 이었다가 계약때는 임혁재로 된 경우 다른 사람이 같은 번호인 경우일 수도 있기 때문에 기존 정보를 바꾸지 않고 새로 등록하는 방향으로 처리
        //핸드폰 본인 인증을 하는게 아니라서 이름을 보수적으로 체크함. name을 like로 할 경우 임혁이 임혁재에 포함되어 버리기 때문에 '=' 기호로 이름 조회 수정.
        $customer_name = str_replace(" ","",$customer_name);    //혹시 있을지 모를 공백 제거.
        $SQL = "
            SELECT
                sid
            FROM
                {$TABLE['vip_customer']} 
            WHERE
                cm_sid = '{$cm_sid}'               
            AND
                name = '{$customer_name}'    
            AND
                hp2 = '{$hp2}'       
            AND
                hp3 = '{$hp3}'       
        ";
        $vip_customer_chk = JHGetRow($CONNECT, $SQL);
        if(!$vip_customer_chk){    //해당 고객의 정보가 없다면 등록
            $SQL  = "
                INSERT 
                    {$TABLE['vip_customer']}
                SET
                    cm_sid                  = '{$cm_sid}'            ,
                    customer_type           = '{$customer_type}'     ,
                    customer_sid		    = '{$customer_sid}'      ,
                    name                    = '{$customer_name}' ,
                    hp1                     = '{$hp1}'      ,
                    hp2                     = '{$hp2}'      ,
                    hp3                     = '{$hp3}'      ,
                    status                  = 'Y'                    ,
                    chk_del                 = 'N'                    ,
                    service_last_used_date  = SYSDATE()              ,
                    {$add_query}
                    regdate                 = SYSDATE()
            ";
            $RESULT = JHExecSQL($CONNECT, $SQL);
            if(!$RESULT) {
                JHTransactionRollback($CONNECT);

                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                JHMoveURL("", $_MESSAGE, "", "back");
                JHExit(); 
            }

            #------------------------------------------------------------------------------
            # 일련번호
            #------------------------------------------------------------------------------
            $SUB_SQL = "SELECT last_insert_id() sid";
            $ROW = JHGetRow($CONNECT, $SUB_SQL);
            $vip_customer_sid = $ROW['sid'];
        }else{     //정보가 있다면 update
            $vip_customer_sid = $vip_customer_chk['sid'];
            $SQL = "
                UPDATE
                    {$TABLE['vip_customer']}
                SET
                    customer_sid	= '{$customer_sid}'  ,
                    {$add_query}
                    uptdate         = SYSDATE()
                WHERE
                    sid = '{$vip_customer_sid}'
            ";
            $RESULT = JHExecSQL($CONNECT, $SQL);
            if(!$RESULT) {
                JHTransactionRollback($CONNECT);

                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                JHMoveURL("", $_MESSAGE, "", "back");
                JHExit(); 
            }
        }

        if( $service_sid != 0 && $ori_vip_customer_sid != $vip_customer_sid ){  //vip_customer_sid가 변경되었다면 혹시나 모를 변경 이력 남겨놓기. 
            #--------------------------------------------------------------------
            # 고객 정보 변경 내역 저장
            #--------------------------------------------------------------------
            $SQL = "
                SELECT
                    *
                FROM
                    {$TABLE['request']}
                WHERE
                    sid = '{$service_sid}'
            ";
            $log_request_info = JHGetRow($CONNECT, $SQL);
            $log_request_info['customer_hp1']          = JHSecretEnd($log_request_info['customer_hp1']);
            $log_request_info['customer_hp2']          = JHSecretEnd($log_request_info['customer_hp2']);
            $log_request_info['customer_hp3']          = JHSecretEnd($log_request_info['customer_hp3']);
            $vip_customer_sid_memo = $title . $ori_vip_customer_sid . " -> " . $vip_customer_sid. "\n";
            $modi_chk = false;
            // 요청CM 체크
            if($cm_sid != $log_request_info['cm_sid']){
                $SQL = "
                    SELECT
                        name
                    FROM
                        {$TABLE['cm']}
                    WHERE
                        member_sid = '{$cm_sid}'
                ";
                $cm_info = JHGetRow($CONNECT, $SQL);

                if(!$log_request_info['cm_sid']){
                    $log_request_info['cm_sid'] = "값 없음";
                }
                $vip_customer_sid_memo .= "요청CM 변경 : " . $log_request_info['cm_name'] . " -> " . $cm_info['name'] . "\n";
                $modi_chk = true;
            
            }
            // 고객명 체크
            if($customer_name != $log_request_info['customer_name']){
                if(!$log_request_info['customer_name']){
                    $log_request_info['customer_name'] = "값 없음";
                }
                $vip_customer_sid_memo .= "고객명 변경 : " . $log_request_info['customer_name'] . " -> " . $customer_name . "\n";
                $modi_chk = true;
            
            }
            // 고객연락처 체크
            $dec_customer_hp = $ori_customer_hp1."-".$ori_customer_hp2."-".$ori_customer_hp3;
            $log_ori_customer_hp = $log_request_info['customer_hp1']."-".$log_request_info['customer_hp2']."-".$log_request_info['customer_hp3'];
            if($dec_customer_hp != $log_ori_customer_hp){
                if(!$log_ori_customer_hp == '--'){
                    $log_ori_customer_hp = "값 없음";
                }
                $vip_customer_sid_memo .= "고객연락처 변경 : " . $log_ori_customer_hp . " -> " . $dec_customer_hp . "\n";
                $modi_chk = true;
            
            }

            $consulting_date = date('Y-m-d');
            $consulting_time = date("H");
            
            $SQL = "
                INSERT INTO
                    {$TABLE['request_consulting']}
                SET
                    request_sid         = '{$service_sid}'            ,
                    member_sid          = '{$_SESSION['sid']}'        ,
                    category            = 'etc'                       ,
                    memo                = '{$vip_customer_sid_memo}'  ,
                    consulting_date     = '{$consulting_date}'        ,
                    consulting_time     = '{$consulting_time}'        ,
                    status              = 'N'                         ,
                    regdate             = SYSDATE()
            ";
            $RESULT = JHExecSQL($CONNECT, $SQL);
            if(!$RESULT) {
                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                JHMoveURL("", $_MESSAGE, "", "back");
                JHExit(); 
            }

            $SQL = "
                UPDATE
                    {$TABLE['request']}
                SET
                    vip_customer_sid	= '{$vip_customer_sid}'  ,
                    uptdate         = SYSDATE()
                WHERE
                    sid = '{$service_sid}'
            ";
            $RESULT = JHExecSQL($CONNECT, $SQL);
            if(!$RESULT) {
                JHTransactionRollback($CONNECT);

                $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                JHMoveURL("", $_MESSAGE, "", "back");
                JHExit(); 
            }
        }

        return $vip_customer_sid;
    }

    // 파트너앱 푸시
    function JSPartnersGroupPush($sid_array, $title, $message, $url='https://partners.chabot.kr/index.php?gubun=home&sub_gubun=intro'){
        /*
        // sid는 반드시 배열이여야함(배열이 아니면 배열화)
        if (is_array($sid_array)) {
            $sid_array = $sid_array;
        } else {
            $sid_array = array($sid_array);
        }

        if(array_search('14482',$sid_array) !== false || array_search('4409',$sid_array) !== false || array_search('14708',$sid_array) !== false){
        
            //if( $_SERVER['SERVER_NAME'] == "dev.chabot.kr" ) { 
                //$sid_array = ['14482','4409','14708'];
            //}
            $curl = curl_init();

            $curl_url = "https://partners.chabot.kr/json/app_json.php";

            $post_data = array(
                'mode'      => 'group_push',
                'sid'       => $sid_array,
                'title'     => $title,
                'message'   => $message,
                'url'       => $url
            );

            curl_setopt_array($curl, array(
                CURLOPT_URL => $curl_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => http_build_query($post_data),
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
        }
        */
        /*
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        }
        */
    }

    function JHGetWorkCondition($work_condition){

        $return_data = "";
        switch($work_condition){
            case "work" :
                    $return_data = "업무중";
                break;
            case "lunch" :
                    $return_data = "점심시간";
                break;
            case "break_time" :
                    $return_data = "휴식";
                break;
            case "dinner" :
                    $return_data = "저녁시간";
                break;
        }

        return $return_data;

    }

    function JHDiffTime($sdate, $edate){
            
        $sdate_str = strtotime($sdate);
        $today_str = strtotime($edate);
        $total_time = $today_str - $sdate_str;

        $diff_years = floor($total_time/31536000);
        $diff_days = floor($total_time/86400);
        $diff_date = ($diff_days - (365*$diff_years))-1;
        $diff_time = $total_time - ($diff_days * 86400);
        $diff_hours = floor($diff_time/3600);
        $diff_time = $diff_time - ($diff_hours/3600);
        $diff_min = floor($diff_time/60);
        $diff_sec = $diff_time - ($diff_min*60);

        $return_data = array(
            "hour"          => $diff_hours      ,
            "min"           => $diff_min      ,
            "sec"           => $diff_sec      ,
        );

        return $return_data;

    }

    function JHTimeModi($sec){
        $hour = floor($sec / 3600);
        $min = floor(($sec / 60) % 60);
        $sec = $sec % 60;

        $return_val = array(
            "hour"      => $hour        ,
            "min"       => $min         ,
            "sec"       => $sec         ,
        );

		return $return_val;
    }

    // 날짜 비교
    function MJWorkDateDiff($start_date, $end_date, $weekend=FALSE){
        $end_date = $end_date ? $end_date : date("Y-m-d");
        
        $date_term = 0;
        if($weekend){
            $sdate     =  new DateTime($start_date);
            $edate       =  new DateTime($end_date);

            $date_term = date_diff($edate, $sdate)->days;
        }else{
            $sdate = strtotime($start_date);
            $edate = strtotime($end_date);

            while(date("Y-m-d", $sdate) < date("Y-m-d", $edate)) {
                if(date("N", $sdate) < 6){
                    $date_term++;
                }
                $sdate = strtotime("+1 day", $sdate);
            }
        }
        return $date_term;
    }

    //절사
    function MJPriceCutting($aprice, $stype, $n){  // 금액, 타입, 절삭금액 단위
        // stype : 원단위처리(R:반올림, C:올림, F:버림)
        $remove_price = 0;
        $stype = $stype ? $stype : "R";
        $remove_price = $aprice / $n;
     
        if($stype == "F") {
            $remove_price = floor($remove_price);
        } else if ($stype == "R") {
            $remove_price = round($remove_price);
        } else if ($stype == "C") {
            $remove_price = ceil($remove_price);
        }
         
        $remove_price = $remove_price * $n;
        return $remove_price;
    }

    function JHRepairPackageStatus($str_status){
        global $global_repair_package_status;

        $hg_status = "";
        for($ts=0; $ts<count($global_repair_package_status); $ts++){
            if($str_status == $global_repair_package_status[$ts]['status']){
                $hg_status = $global_repair_package_status[$ts]['hg_status'];
            }
        }

        return $hg_status;
    }

    function MJChanneltalkSend($channeltalk_id, $channeltalk_text, $channeltalk_button){
        //https://desk.channel.io/chabot/groups/알림톡_테스트_다운로그-415776
        if($channeltalk_id == 415776){
            $channeltalk_text .= "
▶ 서버 : {$_SERVER['SERVER_NAME']}
▶ IP : {$_SERVER['REMOTE_ADDR']}"; 
        }

        // 채널톡 url
        $channeltalk_url = "https://api.channel.io/open/v5/groups/{$channeltalk_id}/messages?botName=channelBot";

        $send_blocks_array = array(
            "blocks" => array(
                            array(
                                "type" => "text",
                                "value" => $channeltalk_text
                            )
            ),
        );
        if($channeltalk_button){
            $send_blocks_array["buttons"] = array(
                                array(
                                    "title" => "해당 건으로 이동",
                                    "url" => $channeltalk_button
                                )
            );
        }

        $send_blocks_text_json = json_encode($send_blocks_array,JSON_UNESCAPED_UNICODE);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $channeltalk_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $send_blocks_text_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'x-access-key: 5eb3bfa432ec66953193',
                'x-access-secret: 48c9ce85cf08ed03b7bb13242e752fe6',
                'Cookie: AWSALB=p6Msaibtd/l8krFp/ekoNJg0YXEi/5EMfwt7fzgBdD5+FahclgNiP+KU05eOuDme8UzkRTzcrH4boCXrp0hs8wr3XOD5My/fRfD/qEfiX+AfEsr1f/cf1eH+TtfC; AWSALBCORS=p6Msaibtd/l8krFp/ekoNJg0YXEi/5EMfwt7fzgBdD5+FahclgNiP+KU05eOuDme8UzkRTzcrH4boCXrp0hs8wr3XOD5My/fRfD/qEfiX+AfEsr1f/cf1eH+TtfC'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }

    /*
    * MJInsureName(보험사 일련번호)
    * 보험사 일련번호를 받으면 보험사명, 짧은 보험사명, 영문명, 짧은 영문명...? 을 리턴  
    * 리턴 : return_type별로 다름. name, short_name, eng_name, short_eng_name
    */
    function MJInsureName($insure_sid, $return_type){
        global $global_insure_name_array;
        $insure_index = array_search($insure_sid, array_column($global_insure_name_array, 'sid'));

        $name = $global_insure_name_array[$insure_index]['name'];
        $short_name = $global_insure_name_array[$insure_index]['short_name'];
        $eng_name = $global_insure_name_array[$insure_index]['eng_name'];
        $short_eng_name = $global_insure_name_array[$insure_index]['short_eng_name'];

        switch($return_type){
            case "name":
                return $name;
            case "short_name":
                return $short_name;
            case "eng_name":
                return $eng_name;
            case "short_eng_name":
                return $short_eng_name;
        }
    }

    /*
    * MJCarinsureCmCommissionCal(자동차보험 딜러 수수료 계산)
    * 계약타입(신규/갱신), 계약채널, 계약보험사, 영수일자
    * 리턴 : 수수료율
    */
    function MJCarinsureCmCommissionCal($contract_type, $contract_channel, $contract_insure, $receipt_date, $commission_gubun = "account"){
        if(0){
            echo "<pre>";
            print_r("commission_gubun : " . $commission_gubun . "\n");
            print_r("contract_type : " . $contract_type . "\n");
            print_r("contract_channel : " . $contract_channel . "\n");
            print_r("contract_insure : " . $contract_insure . "\n");
            print_r("receipt_date : " . $receipt_date . "\n");
            echo "</pre>";
            exit;
        }
        // 신규, 갱신 나누고 피 계산
        $commission_rate = 0;
        if($commission_gubun == "prime_cash"){      //프라임캐시
            switch($contract_type){
                case "new" : 
                    switch($contract_channel){
                        case "CM" : 
                        case "TM" : 
                            $commission_rate = 0.10;
                            break;
                        case "오프라인" : 
                            $commission_rate = 0.11;
                            break;
                        default : 
                            $commission_rate = 0;
                            break;
                    }

                break;
                case "re_new" : 
                    switch($contract_channel){
                        case "CM" : 
                        case "TM" : 
                            if($contract_insure == "삼성"){
                                $commission_rate = 0.04;
                            } else { 
                                $commission_rate = 0.05;
                            }
                            break;
                        case "오프라인" : 
                            $commission_rate = 0.07;
                            break;
                        case "영업용" : 
                            $commission_rate = 0.02;
                            break;
                        default : 
                            $commission_rate = 0;
                            break;
                    }

                break;
            }
        } else {
            switch($contract_type){
                case "new" : 
                    //$contract_type = "신규고객";

                    switch($contract_channel){
                        case "CM" : 
                            if($contract_insure == "삼성"){                            //삼성화재해상보험 딜러피 4% 2024-04-25
                                $commission_rate = 0.04;
                            } else { 
                                $commission_rate = 0.05;
                            }
                            break;
                        case "TM" : 
                            if($contract_insure == "삼성"){                            //삼성화재해상보험 딜러피 4% 2024-04-25
                                $commission_rate = 0.04;
                            } else { 
                                $commission_rate = 0.05;
                            }
                            break;
                        case "오프라인" : 
                            $commission_rate = 0.11;
                            break;
                        case "영업용" : 
                            $commission_rate = 0.02;
                            break;
                        default : 
                            $commission_rate = 0;
                            break;
                    }

                break;
                case "re_new" : 
                    //$contract_type = "기존고객";
                
                    switch($contract_channel){
                        case "CM" : 
                            if($contract_insure == "삼성"){                            //삼성화재해상보험 딜러피 4% 2024-04-25
                                $commission_rate = 0.03;
                            } else { 
                                $commission_rate = 0.04;
                            }
                            break;
                        case "TM" : 
                            if($contract_insure == "삼성"){                            //삼성화재해상보험 딜러피 4% 2024-04-25
                                $commission_rate = 0.03;
                            } else { 
                                $commission_rate = 0.04;
                            }
                            break;
                        case "오프라인" : 
                            //영수일자 2024-02-01 이전 0.08, 2024-02-01 부터는 0.06 / 오프라인 갱신 수수료 변경.  2024-03-20
                            if($ROW['receipt_date'] < '2024-02-01'){
                                $commission_rate = 0.08;
                            } else {
                                $commission_rate = 0.06;
                            }
                            break;
                        case "영업용" : 
                            $commission_rate = 0.02;
                            break;
                        default : 
                            $commission_rate = 0;
                            break;
                    }

                break;
            }
        }

        return $commission_rate;
    }

    /* event_api */
    function MJEventApi($event_name, $mode, $mp_sid, $cm_sid, $path_data_gubun, $path_data_sid, $path_data_date, $memo = ""){
        if(0){
            echo "<pre>";
            print_r("event_name : " . $event_name . "\n");
            print_r("mode : " . $mode . "\n");
            print_r("mp_sid : " . $mp_sid . "\n");
            print_r("cm_sid : " . $cm_sid . "\n");
            print_r("path_data_gubun : " . $path_data_gubun . "\n");
            print_r("path_data_sid : " . $path_data_sid . "\n");
            print_r("path_data_date : " . $path_data_date . "\n");
            echo "</pre>";
            exit;
        }

        if($path_data_date >= '2024-09-01 00:00:00' && $path_data_date <= '2024-09-31 23:59:59'){    //견적 등록일, 계약의 영수일자가 9월 일경우 9월 이벤트
            //견적은 이벤트 끝.
            if($path_data_gubun == "CONTRACT"){     //9월에 계약한 9월 영수일건은 9월 이벤트로 쌓아주기.
                $event_name = "event_202409";
            }
        }
        //event_202410 이벤트는 10월, 11월, 12월 까지
        if( $event_name == "event_202410" && $path_data_date < '2024-10-01 00:00:00' && $path_data_date > '2024-12-31 23:59:59' ){  
            //REQUEST 견적은 등록일 기준, CONTRACT 계약은 영수일자 기준.
            return false;
        }

        if( $_SERVER['SERVER_NAME'] == "dev.chabot.kr" ) { 
            $curl_url = "https://dev-api.chabot.kr:9499/chabot/event/{$event_name}.php";
        } else {
            $curl_url = "https://api.chabot.kr/chabot/event/{$event_name}.php";
        }

        //모두 있어야 함.
        if($event_name && $mode && $cm_sid && $path_data_gubun && $path_data_sid){
            $curl = curl_init();

            $post_data = array(
                'mode'              => $mode,
                'mp_sid'            => $mp_sid,
                'cm_sid'            => $cm_sid,
                'path_data_gubun'   => $path_data_gubun,
                'path_data_sid'     => $path_data_sid,
                'contract_date'     => $path_data_date,
                'memo'              => $memo
            );
            $post_data = json_encode($post_data);

            curl_setopt_array($curl, array(
                CURLOPT_URL => $curl_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $post_data,
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded",
                    "Authorization: 5RcpAtDo7w66iSt1gdNiaMpzocgVuGKUtlppgzqPXbY%3D"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
        }
    }

    function MJDateFormat($date, $date_default = "-",$date_type){
        if($date_type == 'short'){
            $re_date = substr($date,0,10);
        }else{
            $re_date = $date;
            // $short_date = str_replace('-','',$date);
        }
        
        if( $date == "0000-00-00 00:00:00" || $date == "0000-00-00" ){
            $re_date = $date_default;
        }
       
        return $re_date;
    }

    function MJCashCommission($commission_path, $commission_gubun, $mode, $type, $sid){
        global $CONNECT;
        global $TABLE;

        if(0){
            echo "<pre>";
            print_r("commission_path : " . $commission_path . "\n");
            print_r("commission_gubun : " . $commission_gubun . "\n");
            print_r("mode : " . $mode . "\n");
            print_r("type : " . $type . "\n");
            print_r("sid : " . $sid . "\n");
            echo "</pre>";
            exit;
        }
        
        $prime_cash_success_cnt = 0;
        //contract_sid만 들어있는 리스트 받아서 
        if($commission_path == "carinsure"){
            $consulting_chk = true;
            $is_dealer_mycar = "N";
            //본인차 해제되거나 하면 캐시 지급 취소 해야해서 전부 다 검사해야 함.... 흠...
            //commission_info 조회해서 이미 지급 된거라면 프라임 캐시 지급 보내지 않음.
            $SQL = "
                SELECT
                    *
                FROM
                    {$TABLE['commission']}
                WHERE
                    commission_path = 'carinsure'
                AND
                    contract_sid = '{$sid}'
            ";
            $commission_info = JHGetRow($CONNECT, $SQL);
            //기존에 등록된 이력이 있고 지급 완료 상태라면
            if($commission_info && $commission_info['commission_gubun'] == "account"){
                //계좌지급은 완료된것만 올라오기 때문에 여기서 처리할거 없음.
                return true;        //일단 그냥 넘기기
            } else if($commission_info && $commission_info['commission_gubun'] == "prime_cash" && $commission_info['status'] == "Y"){
                //프라임캐시 지급 완료된건 API로 보내지 말고 채널톡으로 전송해서 체크하기.
                $memo = "프라임 캐시 지급 완료 건 계약 수정";
                $modi_chk = true;       
                $consulting_chk = false;        //상담이력은 등록 안 함.       
            } else if ($type == "RENEW_CAR" && ( $commission_info['commission_date'] <= date("Y-m-d") || !$commission_info) ) {
                //갱신인데 수수료 지급일이 지났거나 오늘이라면 프라임캐시에 쏘지 않고 우리쪽에서 채널톡으로 확인 먼저 하기. ( 수수료 지급일이 미래라면 기존에 NEW_CAR 였을수도 있으니까 프라임에 0원으로라도 쏠 수 있게 보내기. )
                //갱신인데 수수료 지급이 안된거라면 여기서 처리할거 없음. 갱신 프라임으로 쏘는건 별도로 처리 함.
                if($commission_info){
                    //갱신인데 프라임캐시 지급 대기 상태라면 일단 모빌리티에서 처리해야 할 부분이므로 여기서는 처리 안 함.
                    //근데 신규 딜러 본인차라서 프라임 지급 요청 나갔는데 이후에 갱신으로 변경 된거면 기존꺼 회수해야 함.
                    $memo = "프라임 캐시 갱신 지급 요청 건 계약 수정";
                    $modi_chk = true;
                    $consulting_chk = false;        //상담이력은 등록 안 함.
                } else {
                    //여기서 처리할거 없음
                    return true;        //일단 그냥 넘기기
                }

                //1. 처음부터 쪽 갱신 : 지급내역이 있다면 익월말에 처리 완료 된거라 건들면 안 됨. / 지급요청상태라면 오늘 요청한거 그쪽에서도 확인중일거라 일단 중지 / 지급내역이 없다면 익월말에 내보낼거니까 지금은 아무것도 안하면 됨
                //2. NEW_CAR 지급 -> RENEW_CAR로 변경 : 지급완료라면 위에서 걸려서 채널톡만 보내고/지급요청 상태라면 기존에 보낸거 일단 0원 처리. / 지급 내역이 없다면 익월말에 내보낼거니까 지금은 아무것도 안하면 됨
                //3. RENEW_CAR -> NEW_CAR : 지급완료라면 위에서 걸려서 채널톡만 보내고 / 지급요청상태라면 else로 넘어가서 딜러 본인차 아닌거 확인하고 0원 지급 상태 될거고 / 지급 내역이 없다면 아래 else에서 한 번더 딜러 본인차 인지 검사할거라.
            } else {    //기존 지급 내역이 없거나, 캐시 지급 대기상태라면
                //contract_info 조회. 나중에는 type 별로 나눠야 할 것 같은데 일단은 자동차 보험쪽 MY_CAR, RENEW_CAR만 처리
                $SQL = "
                    SELECT
                        c.*, r.type as request_type, r.car_code, r.is_dealer_mycar
                    FROM
                        {$TABLE['contract']} c
                    LEFT JOIN
                        {$TABLE['request']} r
                    ON
                        c.request_sid = r.sid
                    WHERE
                        c.sid = '{$sid}'
                ";
                $contract_info = JHGetRow($CONNECT, $SQL);

                //cm_info 조회
                $SQL = "
                    SELECT
                        *
                    FROM
                        {$TABLE['cm']}
                    WHERE
                        member_sid = '{$contract_info['cm_sid']}'
                ";
                $cm_info = JHGetRow($CONNECT, $SQL);

                if($cm_info['my_car_num'] == $contract_info['car_serial_num'] && $cm_info['is_my_car_cash_commission'] != "0000-00-00 00:00:00" ){
                    $is_dealer_mycar = "Y";
                }
                if( $cm_info['is_renew_cash_commission'] != "0000-00-00 00:00:00" && $contract_info['contract_type'] == "re_new" ){
                    //딜러 본인 차량 때문에 프라임캐시 지급된게 아니라 갱신피 신청해서 넘어가 있는거라면
                    //갱신이어야 하는건데 commission_info에 지급일자가 오늘까지라면 위에서 걸렸고, 넘어간건 지급 완료로 처리되었을거라 여기까지 넘어왔을 상황이 없어야 함.
                    $is_renew_cash_commission = "Y";
                    //여기서 아무것도 처리 안하는걸로 넘기기. 여기서 넘기는것도 이상한 지점임. 
                    return true;        //일단 그냥 넘기기
                }
                //본다이렉트는 프라임캐시 지급 안 함
                if($contract_info['office_branch'] == "본다이렉트"){
                    $is_dealer_mycar = "N";
                }
                //딜러 본인 차거나(지급요청/지급요청내용변경) 프라임캐시 지급 대기(신차요청내용변경/신차->갱신넘어간거0원처리.갱신때 해당되면 다시 보낼거임) 상태
                if( $is_dealer_mycar == "Y" || ($commission_info && $commission_info['commission_gubun'] == "prime_cash" && $commission_info['status'] == "N") ){    
                    //딜러 본인 차거나 프라임 캐시 지급 대기중인건이 있다면
                    $cm_sid = $cm_info['member_sid'];
                    $cm_name = ($cm_info['real_name']) ? $cm_info['real_name'] : $cm_info['name'];
                    $sid = $contract_info['sid'];
                    $request_type = $contract_info['request_type'];
                    $receipt_date = $contract_info['receipt_date'];
                    $customer_name = $contract_info['customer_name'];
                    $customer_hp = JHSecretEnd($contract_info['customer_hp1']).'-'. JHSecretEnd($contract_info['customer_hp2']).'-'. JHSecretEnd($contract_info['customer_hp3']);
                    $car_serial_num = $contract_info['car_serial_num'];
                    $car_name = $contract_info['car_name'];
                    $detail_name = $contract_info['detail_name'];
                    $contract_channel = $contract_info['contract_channel'];
                    $contract_insure = $contract_info['contract_insure'];
                    $regist_date = $contract_info['regist_date'];
                    $start_date = $contract_info['start_date'];
                    $end_date = $contract_info['end_date'];
                    $ori_contract_num = JHSecretEnd($contract_info['contract_num']);
                    $int_contract_sum   = str_replace(",","",$contract_info['new_contract_sum']);

                    //정산제외사유. 환수 내역 확인
                    $SQL = "
                        SELECT
                            *
                        FROM
                            {$TABLE['unapproved_contract']}
                        WHERE
                            contract_sid = '{$sid}'
                        AND
                            insure_gubun = 'carinsure'
                    ";
                    $unapproved_contract_info = JHGetRow($CONNECT, $SQL);
                    $is_retake = FALSE;
                    if($unapproved_contract_info){
                        if($unapproved_contract_info['is_retake'] == 'Y'){  //환수필요 -> 0원. 본인차이긴한데 환수 때문에 0원처리라 이건 구분이 필요한 것 같은데.
                            $is_retake = TRUE;
                        }   //정산 제외 사유는 있지만 환수가 아니라면 처리할 필요 없음.

                        if($unapproved_contract_info['contract_status'] == "CENCEL&RECONTRACT"){        //취소후 재가입
                            $int_contract_sum = $unapproved_contract_info['real_contract_sum'];         //취소후 재가입 금액으로 처리
                        }                        
                    }
                    if($is_dealer_mycar == "N" || $is_retake || $type == "RENEW_CAR" ){            // 기존에는 본인차였으나, 딜러 본인차가 더 이상 아니라면 기존에 보낸거 0원 처리. 딜러 본인차 N에서 걸릴거긴 한데 그래서 갱신일 경우도 여기서는 0으로 보내야 하니까 이렇게 설정.
                        //갱신인데 수수료 지급일이 미래라서 여기까지 온 건 NEW로 이미 들어간게 있을거라는 뜻. 갱신은 commission_info 지급 당일에만 추가 됨.
                        //캐시 지급 취소
                        $commission_rate = "0";
                    } else {
                        //갱신은 따로 [프라임캐시 갱신 광고비 데이터 전송]시 계산하므로 여기서는 신규 광고비만 계산.
                        //TM, CM, 오프라인 수수료 계산. 그 외 채널은 0원으로 전달
                        if($contract_channel == "TM" || $contract_channel == "CM"){
                            $commission_rate = "0.10";      //10%
                        } else if($contract_channel == "오프라인"){
                            $commission_rate = "0.11";      //11%
                        } else {
                            $commission_rate = "0";
                        }
                    }
                    $commission = floor( ($commission_rate * $int_contract_sum ) / 10) * 10;
                    $commission_car_info = "{$car_name} / {$detail_name}";

                    switch($request_type){
                        case "new_car" : $request_type = "신차";
                                    break;
                        case "used_car" : $request_type = "중고차";
                                    break;
                        case "re_new_car" : $request_type = "갱신";
                                    break;
                    }

                    //프라임 캐시 지급 대상이라면 지급일자는 익월말
                    if($type == "RENEW_CAR"){   //여기까지 넘어온건 
                        //계좌지급 갱신은 검수 단계에서 지급일자 넣을거고, 캐시지급 갱신은 버튼 전송시 할거고, contract_info에 들어있는거 보냄
                        $commission_request_date = $contract_info['commission_request_date'];          
                    } else if($is_retake){  //지급 완료 안 된 환수처리면 수수료 지급 일자 초기화 되는거 맞음.
                        $commission_request_date = "0000-00-00";
                    } else if( $is_dealer_mycar == "Y") {      //딜러 본인 차가 맞다면 수수료 지급 일자 익월말
                        $receipt_next_month = date("Y-m-d", strtotime("+1 months",strtotime(substr($contract_info['receipt_date'],0,7).'-01')));
                        $receipt_last_month = date("m", strtotime($receipt_next_month));
                        $receipt_last_date = date("t", strtotime($receipt_next_month));

                        $commission_request_date = date("Y", strtotime("+1 months",strtotime($contract_info['receipt_date']))) . "-" . $receipt_last_month . "-" . $receipt_last_date;

                        $chk_last_date = date("w",strtotime($commission_request_date));

                        if($chk_last_date == 6){
                            $commission_request_date = date("Y-m-d",strtotime("-1 days", strtotime($commission_request_date)));
                        } else if( $chk_last_date == 0){
                            $commission_request_date = date("Y-m-d",strtotime("-2 days", strtotime($commission_request_date)));
                        }
                    } else {
                        //신규인데 딜러 본인 차가 아니라면 익일 계좌지급인데 이걸 여기서 강제로 넣어주면 안 될 것 같은데. 일단 수수료 지급일 초기화 하고 채널톡으로 긴급 확인 보내기!
                        $commission_request_date = "0000-00-00";
                    
                        $memo = "딜러 본인차 프라임 캐시 지급 취소. 수수료 지급 예상일이 초기화 되었습니다. \n";
                        $modi_chk = true;
                    }

                    //딜러, 금액, 지급요청일에 변경이 없다면 API 보내지 않음.
                    if($contract_info['cm_sid'] == $cm_sid && $commission_info['commission'] == $commission && $contract_info['commission_request_date'] == $commission_request_date){
                        return true;        //일단 그냥 넘기기
                    }
                    
                    $commission_memo = "[딜러 본인차 프라임 캐시 지급 요청] 딜러명 : {$cm_name} / 가입금액 : {$int_contract_sum} / 캐시 지급율 : {$commission_rate} / 지급금액 : {$commission}";
                    //type은 MY_CAR로 고정. 진짜 캐시지급 대상인 RENEW_CAR는 여기에서 보내지 않음.
                    $commission_list[] = array(
                        "contract_sid"          => $sid,
                        "cm_sid"                => $cm_sid,
                        "cm_name"               => $cm_name,
                        "type"                  => $type,
                        "request_type"          => $request_type,
                        "receipt_date"          => $receipt_date,
                        "customer_name"         => $customer_name,
                        "customer_hp"           => $customer_hp,
                        "car_serial_num"        => $car_serial_num,
                        "commission_car_info"   => $commission_car_info,
                        "contract_channel"      => $contract_channel,
                        "contract_insure"       => $contract_insure,
                        "contract_date"         => $regist_date,
                        "start_date"            => $start_date,
                        "end_date"              => $end_date,
                        "contract_num"          => $ori_contract_num,
                        "contract_sum"          => $int_contract_sum,
                        "commission_rate"       => $commission_rate,
                        "commission"            => $commission,
                        "commission_date"       => $commission_request_date             ,
                    );
                    
                    $api_post = array(
                        "mode"                  => "cash_payment_request"               ,
                        "type"                  => $type                             ,
                        "commission_cnt"        => "1"                                  ,
                        "list"                  => $commission_list
                    );

                    $global_api_url = "https://api.chabot.kr";
                    if( $_SERVER['SERVER_NAME'] == "dev.chabot.kr" ) {
                        $global_api_url = "https://dev-api.chabot.kr:9499";
                    }

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $global_api_url."/chabot/new_prime/cash_commission/",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode($api_post),
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                            "Authorization: 5RcpAtDo7w66iSt1gdNiaMpzocgVuGKUtlppgzqPXbY%3D"
                        ),
                    ));
                    $response = curl_exec($curl);

                    curl_close($curl);
                    
                    $result = json_decode($response,JSON_UNESCAPED_UNICODE);
                    $insurance_request_result_id = $result['success_list'][$sid];

                    $commission_memo .= " / 프라임 캐시 지급 일련번호 : {$insurance_request_result_id}";
                    
                    $SQL  = "
                        INSERT INTO 
                            {$TABLE['car_insure_contract_consulting']} 
                        SET
                            contract_sid			= '{$sid}'              ,
                            member_sid				= '{$_SESSION['sid']}'  ,
                            category				= 'U'                   ,
                            memo					= '{$commission_memo}'  ,
                            consulting_date		    = SYSDATE()             ,
                            status					= 'Y'                   ,
                            regdate = SYSDATE()    
                    ";
                    $RESULT = JHExecSQL($CONNECT, $SQL);
                    if(!$RESULT) {
                        JHTransactionRollback($CONNECT);

                        $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                        JHMoveURL("", $_MESSAGE, "", "back");
                        JHExit(); 
                    }

                    if($commission_request_date && $commission_request_date != $contract_info['commission_request_date']){
                        $SQL = "
                            UPDATE
                                {$TABLE['contract']}
                            SET
                                commission_request_date	= '{$commission_request_date}'			,
                                uptdate		            = SYSDATE()   
                            WHERE
                                sid = '{$sid}'
                        ";
                        $RESULT = JHExecSQL($CONNECT, $SQL);
                        if(!$RESULT) {
                            JHTransactionRollback($CONNECT);

                            $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                            JHMoveURL("", $_MESSAGE, "", "back");
                            JHExit(); 
                        }
                        $memo .= "수수료 지급 일자 변경 : " . $contract_info['commission_request_date'] . " -> " . $commission_request_date . "\n";
                        $modi_chk = true;       
                    }

                    $prime_cash_success_cnt++;
                }

                // 딜러 본인차 프라임 캐시 지급 여부 변경 체크
                if($is_dealer_mycar != $contract_info['is_dealer_mycar']){
                    $memo .= "딜러 본인차 프라임 캐시 지급 여부 변경 : " . $contract_info['is_dealer_mycar'] . " -> " . $is_dealer_mycar . "\n";
                    $modi_chk = true;       
                    
                    //견적에도 변경
                    $request_modi_memo = "딜러 본인차 프라임 캐시 지급 여부 변경 : " . $contract_info['is_dealer_mycar'] . " -> " . $is_dealer_mycar;
                    $request_modi_chk = true;        
                }

                $consulting_date = date('Y-m-d');
                $consulting_time = date("H");

                if($request_modi_chk){            
                    $SQL  = "
                        UPDATE
                            {$TABLE['request']}
                        SET
                            is_dealer_mycar		    = '{$is_dealer_mycar}'     
                        WHERE
                            sid = '{$contract_info['request_sid']}'
                    ";
                    $RESULT = JHExecSQL($CONNECT, $SQL);
                    if(!$RESULT) {
                        JHTransactionRollback($CONNECT);

                        $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                        JHMoveURL("", $_MESSAGE, "", "back");
                        JHExit(); 
                    }

                    $SQL = "
                        INSERT INTO
                            {$TABLE['request_consulting']}
                        SET
                            request_sid          = '{$contract_info['request_sid']}'            ,
                            member_sid         = '{$_SESSION['sid']}'        ,
                            category             = 'etc'                            ,
                            memo                = '{$request_modi_memo}'  ,
                            consulting_date    = '{$consulting_date}'        ,
                            consulting_time    = '{$consulting_time}'        ,
                            status                = 'Y'                         ,
                            regdate              = SYSDATE()
                    ";
                    $RESULT = JHExecSQL($CONNECT, $SQL);
                    if(!$RESULT) {
                        $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                        JHMoveURL("", $_MESSAGE, "", "back");
                        JHExit(); 
                    }
                }
            }

            if($modi_chk){
                if($consulting_chk){
                    $SQL  = "
                        INSERT INTO 
                            {$TABLE['car_insure_contract_consulting']} 
                        SET
                            contract_sid			= '{$sid}'              ,
                            member_sid				= '{$_SESSION['sid']}'  ,
                            category				= 'U'                   ,
                            memo					= '{$memo}'             ,
                            consulting_date		    = '{$consulting_date}'  ,
                            status					= 'Y'                   ,
                            regdate = SYSDATE()    
                    ";
                    $RESULT = JHExecSQL($CONNECT, $SQL);
                    if(!$RESULT) {
                        JHTransactionRollback($CONNECT);

                        $_MESSAGE = "쿼리에 오류가 발생하였습니다.";
                        JHMoveURL("", $_MESSAGE, "", "back");
                        JHExit(); 
                    }
                }
                //채널톡 보내기                    
                // 개발계 / 운영계 구분
                if( $_SERVER['SERVER_NAME'] == "dev.chabot.kr" ) {
                    $channeltalk_id = "63772";
                } else {
                    $channeltalk_id = 426187;
                }
                $channeltalk_text = "
프라임캐시 관련 수정사항이 발생하여 확인이 필요합니다.

▶ 계약 일련번호 : {$sid}
▶ 수정 내용 : {$memo}
▶ 수정 상담원 : {$_SESSION['name']}
";
                $channeltalk_button = "https://www.chabot.kr/crmsys/index.php?grp={$_grp}&gubun=contract&act=update&sid={$sid}";

                MJChanneltalkSend($channeltalk_id, $channeltalk_text, $channeltalk_button);
            }

            $return_result[$sid] = array(
                "is_dealer_mycar"   =>  $is_dealer_mycar            ,
            );

        }
        /*
        if( $prime_cash_success_cnt > 0 ) {
            $result_message .= "\n프라임캐시지급 : {$prime_cash_success_cnt} 건";
        }
        */
        $result_message = array(
            "return_result"     =>  $return_result            ,
            "success_cnt"       =>  $prime_cash_success_cnt
        );

        return $result_message;
    }

    
    /*
    * MJCarinsurePenaltyChk(차팀장 패널티 계산)
    * 유입경로(차팀장), 견적일련번호, 견적등록일시
    * 리턴 : 패널티 여부, 첫 상담 시간
    */
    function MJCarinsurePenaltyChk($path, $request_sid, $request_regdate){
        if(0){
            echo "<pre>";
            print_r("path : " . $path . "\n");
            print_r("request_sid : " . $request_sid . "\n");
            print_r("request_regdate : " . $request_regdate . "\n");
            echo "</pre>";
            exit;
        }
        
        // 1. 상담 접수 이후 10분이 지났는지 확인
        // 첫 상담 시간 경과 여부 확인 ( 패널티 )
        // 등록 시간 : regdate
        // 첫 상담 시간 : first_touch_date
		$first_touch_date = date("Y-m-d H:i:s");

        $str_request_regdate = strtotime($request_regdate);
        $str_first_touch_date = strtotime($first_touch_date);
        $str_penalty_regdate = strtotime("+10 minute", strtotime($request_regdate)); // 접수 후 10분 경과

        $int_regdate_time = intval(substr(date("His",strtotime($request_regdate)),-6)); // 접수 시간( 단순 계산을 위해 숫자로만 표기 )
        $int_start_lunch_criteria_time = 114000; // 점심 시작 시간
        $int_end_lunch_criteria_time = 130000; // 점심 이후 시간 13:00:00
        $int_start_out_criteria_time = 180000; // 퇴근 이후 시간
        $int_end_out_criteria_time = 90000; // 출근 이전 시간 09:00:00

        $is_penalty = "N";
        // 첫 상담 등록 시간이 견적 등록 시간보다 10분이 넘은 경우 ( 상담 O )
        if($str_penalty_regdate < $str_first_touch_date){
            $is_penalty = "Y";
        }

        // 2. 등록일이 주말인지 확인 ( 사실 금요일 오후도 여기 주말 체크하는거에 해당되는거나 마찬가지인데 )
        if($is_penalty == "Y"){
            // 단, 주말일 경우 페널티 X
            // 주말 ( 0 or 6 )
            $str_week = date("w", strtotime($request_regdate)); // 견적 등록 요일
            if( ($str_week == 0 || $str_week == 6 ) ){
                // 접수 날짜가 주말이면 패널티 대상 아님
                $is_penalty = "N";
            }
        }

        // 3. 영업 시간 외 접수 건이라면. ( 점심시간은 같은 날짜에서만 비교. )
        // 단, 점심시간과 업무 외 시간에는 제외
        // 점심 시간 : 11:40 ~ 13:00
        // 영업 시간 외 : 18:00 ~ 09:00
        if($is_penalty == "Y" && date("Y-m-d",$str_first_touch_date) == date("Y-m-d",$str_request_regdate) ){
            // 단,점심시간일 경우 페널티 X
            if($int_end_lunch_criteria_time > $int_regdate_time && $int_start_lunch_criteria_time < $int_regdate_time){
                // 점심 시간
                $is_penalty = "N";
            } 
        }
        
        // 3. 영업 시간 외 접수 건이라면. ( 금요일 업무시간 이후 접수하고, 월요일에 처리하는 경우 ) ( 월요일 업무시간 이전에 접수하고, 월요일 업무시간 이후 처리 하는 경우 )
        // 단, 업무 외 시간에는 제외
        // 영업 시간 외 : 18:00 ~ 09:00
        if($is_penalty == "Y" ){
            //접수시간이 18:00 보다 크거나 접수시간이 09:00 보다 작다면
            if($int_start_out_criteria_time < $int_regdate_time || $int_end_out_criteria_time > $int_regdate_time){
                // 단,영업 시간 전일 경우 페널티 X 
                // 영업 시간 전
                $is_penalty = "N";
            }
        }
        
        $is_penalty_parm = "N";
        if($is_penalty == "Y"){
            $is_penalty_parm = "Y";	
        }

        $return_array = array(
            "is_penalty_parm"       =>  $is_penalty_parm            ,
            "first_touch_date"      =>  $first_touch_date
        );

        return $return_array;
    }
    
    //보험사 별로 체크가 필요한 경우
    function MJChkOCR($chk_gubun, $chk_value, $contract_insure = ""){
        switch($chk_gubun){
            case "contract_insure" :
                $chk_contract_insure = str_replace("해상보험","",$chk_value);
                if($chk_value == "동부화재"){
                    $chk_contract_insure = "DB손해보험";
                } else if($chk_value == "현대해상화재보험"){
                    $chk_contract_insure = $chk_contract_insure . "|Hyundai";
                } else if($chk_value == "KB손해보험"){
                    $chk_contract_insure = $chk_contract_insure . "|KB다이렉트";
                }
                return $chk_contract_insure;
            break;
            case "customer_name" :
                $chk_customer_name = preg_replace("/[^가-힣A-Za-z0-9]/", "", $chk_value); //고객명은 한글,영어,숫자만 가능
                // 주식회사 빼기
                $companyArr = array("(주)","㈜","(유)","(우)","(수)","(사)","(명)","유한회사","주식회사","회사","법인");
                $chk_customer_name = str_replace($companyArr,"",$chk_customer_name);
                return $chk_customer_name;
            break;
            case "customer_ssn_prefix" :
                $chk_customer_ssn_prefix = preg_replace("/[^0-9]/", "", $chk_value); //주민번호(사업자등록번호)는 숫자만
                return $chk_customer_ssn_prefix;
            break;
            case "contract_num" :
                $chk_contract_num = preg_replace('/000.*$/','',$chk_value);
                return $chk_contract_num;
            break;
            case "regist_date" :
                $chk_regist_date = str_replace("-","",$chk_value);  //가입일자
                return $chk_regist_date;
            break;
            case "contract_channel" :
            case "contract_real_channel" :
                $chk_contract_channel = $chk_value;

                if($chk_value == "오프라인"){
                    $chk_contract_channel = "더케이에셋보험대리점|차봇인슈어런스|01024616891|이나래";
                } else {
                    switch($contract_insure){
                        case "DB손해보험" :
                                if($chk_value == "CM"){
                                    $chk_contract_channel .= "|인터넷";
                                } else {
                                    $chk_contract_channel .= "|다이렉트사업";
                                }
                            break;
                        case "KB손해보험" :
                                // $ROW['contract_real_channel'] = substr($ROW['contract_num'],0,-3);
                            break;
                        case "한화손해보험" :
                                $chk_contract_channel .= "|센터"; // 한화는 TM 밖에 없긴 함..
                            break;
                        case "현대해상화재보험" :
                                if($chk_value == "CM"){
                                    $chk_contract_channel .= "|18996782";
                                } else {
                                    $chk_contract_channel .= "|15771001"; // TM 전화번호
                                }
                            break;
                        case "삼성화재해상보험" :
                                if($chk_value == "CM"){
                                    $chk_contract_channel .= "|15773339";
                                } else {
                                    $chk_contract_channel .= "|16005008"; // TM 전화번호
                                }
                            break;
                        case "캐롯손해보험" :
                                if($chk_value == "CM"){
                                    $chk_contract_channel .= "|15660300";
                                }
                            break;
                    }
                }
                return $chk_contract_channel;
            break;
        }
    }

    function YYPathToKr($path){
        $path_kr = $path;
        switch($path){
            case "CHABOT":
                $path_kr = "별내";
            break;
            case "MLABEL":
                $path_kr = "부천";
            break;
            case "CHATEAM":
                $path_kr = "차팀장";
            break;
            case "PRIME":
                $path_kr = "프라임";
            break;
        }

        return $path_kr;
    }

    function MJPriceChange($price){
        if( $price<0 || empty($price) ) $price = 0;
        
        $priceUnit = array('원', '만원', '억원', '조원');
        $expUnit = 10000;
        $resultArray = array();
        $result = "";

        foreach($priceUnit as $k => $v){
            $unitResult = ( $price % pow($expUnit,$k+1) ) / (pow($expUnit, $k));
            $unitResult = floor($unitResult);

            if($unitResult>0){
                $resultArray[$k] = $unitResult;
            }
        }

        if(count($resultArray)>0){
            foreach($resultArray as $k => $v){
                $result = $v.$priceUnit[$k].' '.$result;
            }
        }

        return $result;
    }

    // 제휴처 - 직원 정보 가져오기 ( 차팀장, 보켓, PARTNER 등 )
    function MJPartnerManagerInfo($cm_sid, $partner_belong_code, $request_sid, $path){
        global $CONNECT;
        global $TABLE;
        global $BOKET_CONNECT;
        global $BOKET_TABLE;

        if(0){
            echo "<pre>";
            print_r("cm_sid : " . $cm_sid . "\n");
            print_r("partner_belong_code : " . $partner_belong_code . "\n");
            print_r("request_sid : " . $request_sid . "\n");
            echo "</pre>";
            exit;
        }
        
        $partner_belong_info = array();

        //제휴처 - 직원명 확인
        $SQL = "
            SELECT
                pm.partner_sid
            FROM
                {$TABLE['partner_manager']} pm
            WHERE
                pm.cm_sid = '{$cm_sid}'
        ";
        $partner_manager_info = JHGetRow($CONNECT, $SQL);
        $partner_company = "";
        if($partner_manager_info['partner_sid']){
            //파트너사 확인
            $SQL = "
                SELECT
                    p.partner_company
                FROM
                    {$TABLE['partner']} p
                WHERE
                    p.sid = '{$partner_manager_info['partner_sid']}'
            ";
            $partner_info = JHGetRow($CONNECT, $SQL);
            $partner_belong_info['partner_company'] = $partner_info['partner_company'];

        } else if($partner_belong_code){
            if ($cm_sid == '15442'){     //차팀장

                $api_post = array(
                    "mode"    => "get_insu_member",
                    "mb_idx"  => $partner_belong_code
                );

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://ctj.kr/api/api.insure.php",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($api_post),
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Authorization: af55e89023abd7cb7fd5d5c6589f2f617805decd"
                    ),
                ));
                $response = curl_exec($curl);
                $response = json_decode($response);

                curl_close($curl);

                if($response->code == 200){
                    $phone1 = substr($response->data->mb_hp,0,3);
                    $phone2 = substr($response->data->mb_hp,3,4);
                    $phone3 = substr($response->data->mb_hp,7,4);

                    $partner_belong_info['hp'] = $phone1 . "-" . $phone2 . "-" . $phone3;

                    $partner_belong_info['sid'] = $request_info['partner_belong_code'];
                    $partner_belong_info['name'] = $response->data->mb_name;

                }else{
                    $partner_belong_info['sid'] = "";
                    $partner_belong_info['name'] = "조회불가";
                    $partner_belong_info['hp'] = "000-0000-0000";
                }

            } else if ($cm_sid == '9744'){      //boket
                //partner_request_temp_info 테이블에 있는지 확인
                $SQL = "
                    SELECT
                        *
                    FROM
                        {$TABLE['partner_request_temp']}
                    WHERE
                        request_sid = '{$request_sid}'
                ";
                $partner_request_info = JHGetRow($CONNECT, $SQL);
                if($partner_request_info){
                    $partner_belong_info['name'] = '자동차보험비교센터';
                    $partner_belong_info['hp'] = "-";
                } else {
                    $SQL = "
                        SELECT
                            *, r.sid request_sid, c.member_sid consultant_sid
                        FROM
                            {$BOKET_TABLE['request']} r
                        JOIN
                            {$BOKET_TABLE['consultant']} c
                        ON
                            r.consultant_sid = c.member_sid
                        WHERE
                            r.consultant_id = '{$partner_belong_code}'
                    ";
                    $partner_belong_info = JHGetRow($BOKET_CONNECT, $SQL);
                    $partner_belong_info['hp'] = JHSecretEnd($partner_belong_info['hp1']) . "-" .JHSecretEnd($partner_belong_info['hp2']) . "-" . JHSecretEnd($partner_belong_info['hp3']);
                    
                    /* 최수빈(1468), 김흥식(540)으로 접수 시 자동차보험비교센터로 표기 */
                    if($partner_belong_info['consultant_sid'] == 1468 || $partner_belong_info['consultant_sid'] == 540 ){
                        $partner_belong_info['name'] = '자동차보험비교센터(' . $partner_belong_info['name'] . ")";
                        $partner_belong_info['hp'] = "-";
                    }
                }
                
            } else if($path == 'PARTNER'){
                $SQL = "
                    SELECT
                        *
                    FROM
                        {$TABLE['partner_manager']}
                    WHERE
                        id = '{$partner_belong_code}'
                ";
                $partner_belong_info = JHGetRow($CONNECT, $SQL);
                if($partner_belong_info){
                    $partner_belong_info['hp'] = JHSecretEnd($partner_belong_info['hp']);

                    $phone1 = substr($partner_belong_info['hp'],0,3);
                    $phone2 = substr($partner_belong_info['hp'],3,4);
                    $phone3 = substr($partner_belong_info['hp'],7,4);

                    $partner_belong_info['hp'] = $phone1 . "-" . $phone2 . "-" . $phone3;
                } else {
                    $partner_belong_code = JHSecretEnd($partner_belong_code);
                    $partner_belong_code = explode("_", $partner_belong_code);

                    $partner_belong_info['name'] = $partner_belong_code['0'];

                    $phone1 = substr($partner_belong_code['1'],0,3);
                    $phone2 = substr($partner_belong_code['1'],3,4);
                    $phone3 = substr($partner_belong_code['1'],7,4);

                    $partner_belong_info['hp'] = $phone1 . "-" . $phone2 . "-" . $phone3;
                }
            }
        }

        return $partner_belong_info;
    }

?>