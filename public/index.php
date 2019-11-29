<?php
// 함수, 상수 지정
define("START", microtime(true));


$config = include "../dbconf.php";
require "../Loading.php";

// 세션 활성화
// 세션, 로그인등의 정보 저장하기 위해서.
session_start();

// 부트스트래핑.
$uri = $_SERVER['REQUEST_URI'];
$uris = explode("/",$uri); // 파란책
// print_r($uris);

// 데이터베이스 연결. 초기화...
$db = new \Module\Database\Database( $config );

/**
 * 컨트롤러
 */
// 도메인/ = 시작
// 도메인/databases <= 클래스 호출

if(isset($uris[1]) && $uris[1]) {
    // 컨트롤러 실행...
    // echo $uris[1]."컨트롤러 실행...";
    $controllerName = "\App\Controller\\" . ucfirst($uris[1]);
    // db정보를 인자값 전달. (생성자)
    $tables = new $controllerName ($db);
    
    // 클래스의 메인이 처음으로 동작하는 것로 정해요.
    // 호출. 
    $tables->main();

} else {
    // M(model:database) + V(화면: 파일 분리 처리) + C(기능: 객체지향)
    // 처음 페이지 에요.
    // echo "처음 페이지 에요.";
    $body = file_get_contents("../Resource/index.html");

    if($_SESSION["email"]) {
        // 로그 상태 입니다.
        $body = str_replace("{{Login}}","로그인 상태입니다. <a href='logout'>로그아웃</a>",$body);
    } else {
        // 로그인 해주세요.
        $loginForm = file_get_contents("../Resource/login.html");
        $body = str_replace("{{Login}}",$loginForm,$body);
    }
    echo $body;
}

// $desc = new \App\Controller\TableInfo;
// $desc->main();

function shutdown()
{
    echo "시작시간=".START;

    $endtime = microtime(ture);
    echo "종료시간=".$endtime;

    $running = $endtime - START;
    echo "실행시간=".$running;
}

// shutdown();
// 프로그램이 종료되면, 자동으로 shutdown 함수를 호출해 줍니다.
register_shutdown_function("shutdown");
