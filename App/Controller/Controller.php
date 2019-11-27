<?php
namespace App\Controller;

// 추상클래스
abstract class Controller
{
    // 인터페이스와 유사하게 선언을 할 수 있어요.
    abstract public function main();

    // 추상화는 메소드 설정할 수 있어요.
    public function hello()
    {
        echo "안녕 대림대학교 PHP 강좌";
    }

}