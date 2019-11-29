<?php
namespace App\Controller;
class Databases extends Controller
{
    private $db; // 변수, 
    // 생성자 처리
    public function __construct($db)
    {
        // echo __CLASS__;
        // 외부에서 넘겨오는 DB정보 내부에 저장한다.
        // 내부에 저장한다 의미 ==> 객체 상태값 지정한다. 
        $this->db = $db; 
    }

    /**
     * 컨트롤러 시작점
     */
    public function main()
    {
        // 추상클래스의 메소드 호출
        $this->hello();

        $uri = new \Module\Http\Uri;
        if($uri->second() == "new") {
            print_r($_POST);
            if($_POST) {
                // 새로운 데이터베이스를 추가
                $query = "CREATE DATABASE ".$_POST['database'];
                echo $query;

                $result = $this->db->queryExecute($query);

                // 페이지 이동
                header("location:"."/databases");

            } else {
                // 데이터베이스 입력해 주세요
                // 새로운 데이터베이스 추가
                // echo "데이터베이스를 생성해 주세요.";
                $htmlForm = file_get_contents("../Resource/database_new.html");
                echo $htmlForm;
            }

            
        } else {
            // 데이터베이스 목록
            $this->list();
        }        
    }

    public function list()
    {
        $html = new \Module\Html\HtmlTable;

        $query = "SHOW DATABASES";
        $result = $this->db->queryExecute($query);

        $count = mysqli_num_rows($result);
        $content = ""; // 초기화
        $rows = []; // 배열 초기화
        for ($i=0;$i<$count;$i++) {
            $row = mysqli_fetch_object($result);
            // $rows []= $row; // 배열 추가
            // 배열 * 배열 = 2차원 배열.
            // 키, 값 연상배열
            $rows []= [
                'num'=>$i,
                'name'=>"<a href='/Tables/".$row->Database."'>".$row->Database."</a>"
            ];
        }
        $content = $html->table($rows);

        $body = file_get_contents("../Resource/database.html");
        $body = str_replace("{{content}}",$content, $body); // 데이터 치환
        echo $body;
    }

}