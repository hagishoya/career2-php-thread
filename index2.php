<!DOCTYPE html>
<head>
    <title>掲示板App</title>
</head>


<body>

    <h1>掲示板App</h1>

    <h2>投稿フォーム</h2>


    <form action="index.php" method="post">
        <input type="text" name="name"  placeholder="名前" required/>
        <br><br>
        <textarea type="text" name="info" rows="4" cols="40"  placeholder="内容" required></textarea>
        <br><br>
        <input type="submit" name = "buttan" value = "投稿する" />
    </form>


    <h2>スレッド</h2>

    <?php




    function writedata()
    {
        date_default_timezone_get('Asia/Tokyo');
        $personal_name = $_POST['name'];
        $contents = $_POST['info'];
        $contents = nl2br($contents);

            //echo '<p>投稿者:'.$personal_name.'<br>';
            //echo '<p>内　容:'.$contents.'<br><br>';
            
        $save_file = 'thread.txt';

            if(!file_exists($save_file)){
                $fp = fopen($save_file,'w');
                fwrite($fp,"");
                fclose($fp);
            }
    
            $fp = fopen($save_file,'ab');
    
            if($fp){
                if(flock($fp,LOCK_EX)){

                    $save = fgets($fp);
                    
                    echo date("Y/m/d H:i:s")."/n";
                    fwrite($fp,'<p>投稿者:'.$personal_name."<br>");
                    fwrite($fp,'<p>内　容:'.$contents."<br>");
    
                    rewind($fp);
    
                    if(fwrite($fp,$save)===FALSE){
                        print("ふぁいるかきこみにしっぱいしました");
                    }
    
                flock($fp,LOCK_UN);
                }
            }
    
            fclose($fp);

         // $fp = fopen($save_file,'r');
         // $file = fread($fp,filesize($save_file));
         // fclose($fp);

            if($fp=fopen($save_file,'r')){
             while($data = fgets($fp)){
                echo $data."<br>";
                }
            }
            
            fclose($fp);
            $redirect_url = $_SERVER['HTTP_REFERER'];
            header("Location: $redirect_url");
            exit;
        }



        



    if($_SERVER['REQUEST_METHOD']==="POST"){
        writedata();
        //delete_data();
    }
    ?>

    

    



</body>

</html>
