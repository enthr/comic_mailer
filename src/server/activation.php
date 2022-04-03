<?php

    require __DIR__.'/config.php';

    if(isset($_SERVER['REQUEST_METHOD']) && !empty($_GET['link'])) {
        $elink = $_GET['link'];
        $dlink = rawurldecode($elink);
        $check_dlink = filter_var($dlink, FILTER_SANITIZE_EMAIL);

        if(!$dlink) {
            exit('Invalid Activation Link');
        }
        

        //Connect To The Database

        $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DB);

        if($conn) {
            $sql = "UPDATE `sublist` SET `sub`='1' WHERE `email`='$dlink';";
            $result = mysqli_query($conn, $sql);

            if($result) {
                echo '<script>alert("Email Verfication Succesfull")</script>';
            } else {
                echo '<script>alert("Email Verfication Failed Try Again, O\r Try Contacting Website Support")</script>';             
            }
            
        } else {
            echo '<script>alert("Some Error Has Ocurred Try Later.")</script>';            
        }

        mysqli_close($conn);
        
    }

?>