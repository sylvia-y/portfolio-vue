<?php
$apiKey = '7MuKIvlFfGXtqyshW0oTk0jHmOlKsO36ssBTkMMVbMEQVoET2bswcDtnc76JteZh1SixrXHPGl5VFzZH%2FXAGLw%3D%3D';
$apiUrl = "https://apis.data.go.kr/B552584/ArpltnStatsSvc/getCtprvnMesureLIst?serviceKey=$apiKey&returnType=json&numOfRows=10&pageNo=1&itemCode=PM10&dataGubun=HOUR&searchCondition=WEEK";

// API 호출
$response = file_get_contents($apiUrl);
if ($response === FALSE) {
    die('API 요청 실패');
}

// JSON 데이터 파싱
$data = json_decode($response, true);
if ($data === NULL) {
    die('JSON 파싱 실패');
}

// 결과 출력
if (isset($data['response']['body']['items'])) {
    echo "<h2>실시간 미세먼지 경보 지역</h2>";
    echo "<ul>";
    foreach ($data['response']['body']['items'] as $item) {
        echo "<li>지역: {$item['cityName']} - 미세먼지 농도: {$item['pm10Value']}㎍/㎥</li>";
    }
    echo "</ul>";
} else {
    echo "데이터가 없습니다.";
}
?>