<?php

/**
 * 職業実践2 - 掲示板アプリ
 */

session_start();
new thread();

function setToken()
{
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

function checkToken()
{
    if (empty($_SESSION['token'])) {
        echo "Sessionが空です";
        exit;
    }

    if (($_SESSION['token']) !== $_POST['token']) {
        echo "不正な投稿です。";
        exit;
    }

    $_SESSION['token'] = null;
}

if (empty($_SESSION['token'])) {
    setToken();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<title>掲示板</title>
</head>
<body>
<h1>掲示板App</h1>

<h2>投稿フォーム</h2>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
    <input type="text" name="personal_name" placeholder="名前" required><br><br>
    <textarea name="contents" rows="8" cols="40" placeholder="内容" required>
</textarea><br><br>
    <input type="submit" name="btn" value="投稿する">
</form>

<form action="index.php" method="post">
    <input type="hidden" name = "method" value = "DELETE" />
    <button type = "submit">投稿を全削除する</button>
</form>

<h2>スレッド</h2>











<?php
//外部変数
const THREAD_FILE = 'thread.txt';
require_once './Thread.php';
$thread = new Thread('掲示板App');


// function readData() {
//     // ファイルが存在しなければデフォルト空文字のファイルを作成する
//     if (! file_exists(THREAD_FILE)) {
//         $fp = fopen(THREAD_FILE, 'w');
//         fwrite($fp, '');
//         fclose($fp);
//     }

//     $thread_text = file_get_contents(THREAD_FILE);
//     echo $thread_text;
// }

function writeData() {
    date_default_timezone_get('Asia/Tokyo');
    $personal_name = $_POST['personal_name'];
    $contents = $_POST['contents'];
    $contents = nl2br($contents);

    //$data = "<hr>\n";
    //$data = $data."<p>時間：".date("Y/m/d H:i:s")."<p>";
    //$data = $data."<p>投稿者:".$personal_name."</p>\n";
    //$data = $data."<p>内容:</p>\n";
    //$data = $data."<p>".$contents."</p>\n";
    



    $fp = fopen(THREAD_FILE, 'ab');

    if ($fp){


        $save = fgets($fp);
                    
        fwrite($fp,'<p>時間：'.date("Y/m/d H:i:s").'<br>');
        //echo date("Y/m/d H:i:s")."/n";
        fwrite($fp,'<p>投稿者:'.$personal_name."<br>");
        fwrite($fp,'<p>内　容:'.$contents."<br>");


        if (flock($fp, LOCK_EX)){
            if (fwrite($fp,  $data) === FALSE){
                print('ファイル書き込みに失敗しました');
            }

            flock($fp, LOCK_UN);
        }else{
            print('ファイルロックに失敗しました');
        }
    }

    fclose($fp);

    // ブラウザのリロード対策
    $redirect_url = $_SERVER['HTTP_REFERER'];
    header("Location: $redirect_url");
    exit;
}


function deleteData(){
    file_put_contents(THREAD_FILE,"");
}



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST['method']) && $_POST['method']=== "DELETE"){
        deleteData();
    }
    else{
        writeData();
    }
    //デプロイ
    //git pull origin master
}

//readData();

$thread_data = $thread->getList();
echo $thread_data;
?>


<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>


</body>
</html>