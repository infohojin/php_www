<?php
namespace App\Controller;
class Goods extends Controller
{
    private $db;
    private $HttpUri;
    public function __construct($db)
    {
        $this->db = $db;
        $this->HttpUri = new \Module\Http\Uri(); // 객체생성
    }

    public function main()
    {
        $second = $this->HttpUri->second();
        if($second == "new") {
            // 데이터삽입
            $this->newInsert();
        } else {
            // 목록
            $this->goods();
        }        
    }

    private function newInsert()
    {
        if ($_POST) {
            \move_uploaded_file($_FILES['images']['tmp_name'], "images/".$_FILES['images']['name']);
            $query = "INSERT INTO goods (goodname,images,price) 
            VALUES ('".$_POST['goodname']."','".$_FILES['images']['name']."','".$_POST['price']."')";
            echo $query;

            $result = $this->db->queryExecute($query);

        } else {
            echo "데이터 삽입";
            $body = file_get_contents("../Resource/goods_new.html");
            $body = str_replace("{{content}}",$content, $body); // 데이터 치환
            echo $body;
        }
        
    }

    private function goods()
    {
        echo "쇼핑몰 상품목록";
        $query = "SELECT * from Goods";
        $result = $this->db->queryExecute($query);

        $count = mysqli_num_rows($result);
        $content = "<div class=\"container\">
        <div class=\"row\">"; //초기화
        for ($i=0,$j=1;$i<$count;$i++,$j++) {
            $row = mysqli_fetch_object($result);
            // print_r($row);

            if ($i%3 == 0) {
                $content .= "</div>
                <div class=\"row\">
                ";
            }
          
            $content .= "<div class=\"col-sm\">";
            $content .="<div>상품명:".$row->goodname."</div>";
            $content .="<div><img src='/images/".$row->images."' width='100%' /></div>";
            $content .="<div>가격:".$row->price."</div>";
            $content .= "</div>";
        }

        $content .= "</div>
        </div>";


        // MVC 패턴에서 view 화면 분리.
        $body = file_get_contents("../Resource/goods.html");
        $body = str_replace("{{content}}",$content, $body); // 데이터 치환

        // 테이블 별로 new 버튼 링크 생성
        $body = str_replace("{{new}}","/goods/new", $body);
        echo $body;
    }
}
