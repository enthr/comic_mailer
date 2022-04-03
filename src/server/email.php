<?php

    require __DIR__.'/config.php';

    //Fetch Comic Data From xkcd
    $randomURL = get_headers('https://c.xkcd.com/random/comic/', true);
    
    $URL = $randomURL['Location'][0];
    $URL_JSON = $URL.'info.0.json';
    $URL_JSON_Content = file_get_contents($URL_JSON);

    $comicData = json_decode($URL_JSON_Content);

    $comicData = (array)$comicData;

    //Connect To The Database
    $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DB);

    if($conn) {
        
        $sql = "SELECT `email` FROM `sublist` WHERE `sub`='1'";

        $result = mysqli_query($conn, $sql);
    
        $emailArr = mysqli_fetch_all($result);

        foreach($emailArr as $toEmail) {
            
            $email = $toEmail[0];
            $eemail = rawurlencode($email);
        
            // Recipient 
            $to = "$email";
            
            // Sender 
            $from = FROMEMAIL; 
            $fromName = FROMNAME; 
            
            // Email subject 
            $subject = 'Comic: '.$comicData["safe_title"];  
            
            // Attachment file 
            $file = basename($comicData["img"]); 
            file_put_contents($file, file_get_contents($comicData["img"]));
            
            // Email body content 
            $htmlContent = ' 
                <h3>Title: '.$comicData["title"].'</h3> 
                <img src='.$comicData["img"].' alt='.$comicData["alt"].'>
                <h4>Deactivation Link: <a href="'.DOMAIN_URL.'deactivation.php?link='.$eemail.'">Click Here</a></h4>
            '; 
            
            // Header for sender info 
            $headers = "From: $fromName"." <".$from.">"; 
            
            // Boundary  
            $semi_rand = md5(time());  
            $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
            
            // Headers for attachment  
            $headers .= "\nMIME-Version: 1.0\n"."Content-Type: multipart/mixed;\n"." boundary=\"{$mime_boundary}\""; 
            
            // Multipart boundary  
            $message = "--{$mime_boundary}\n"."Content-Type: text/html; charset=\"UTF-8\"\n"."Content-Transfer-Encoding: 7bit\n\n".$htmlContent."\n\n";  
            
            // Preparing attachment 
            if(!empty($file) > 0){ 
                if(is_file($file)){ 
                    $message .= "--{$mime_boundary}\n"; 
                    $fp =    @fopen($file,"rb"); 
                    $data =  @fread($fp,filesize($file)); 
            
                    @fclose($fp); 
                    $data = chunk_split(base64_encode($data)); 
                    $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
                    "Content-Description: ".basename($file)."\n" . 
                    "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
                    "Content-Transfer-Encoding: base64\n\n".$data."\n\n"; 
                } 
            } 
            $message .= "--{$mime_boundary}--"; 
            $returnpath = "-f" . $from; 
            
            // Send email 
            $mail = @mail($to, $subject, $message, $headers, $returnpath);
            
        }
        
        mysqli_close($conn);
        unlink($file);
        
    } else {
        echo '<script>alert("Some Error Has Ocurred Please Try Again.")</script>';
    }
    
?>