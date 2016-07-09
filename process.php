<?php
    session_start();
    include "settings.php";
    $function = $_POST['function'];
    
    $log = array();
    
    switch($function) {
    
    	case('getState'):
		/*
	       	if(file_exists('chat.txt')){
			$lines = file('chat.txt');
		}
		$log['state'] = count($lines); 
		*/
		$sql = "SELECT `id` FROM `messages` WHERE `appID` = '$_SESSION[appID]' AND `app_version` = '$_SESSION[app_version]' ORDER BY `id` ASC";
		$result = $chat->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			$lastID = $row['id'];
		}
		$log['state'] = $lastID;
		$_SESSION['lastID'] = $lastID;
	break;	
    	
    	case('update'):
        	$state = $_POST['state'];

			$sql = "
			SELECT
				`u`.`alias`,
				`m`.`message`,
				`m`.`id`

			FROM
				`messages` m, `users` u

			WHERE
				`m`.`userID` = `u`.`id`
				AND `m`.`appID` = '$_SESSION[appID]'
				AND `m`.`id` > '$_SESSION[lastID]'
				AND `m`.`id` > '$_SESSION[donotrepeat]'
				AND `m`.`app_version` = '$_SESSION[app_version]'
			";
			$result = $chat->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$text[] = "<span>$row[alias]</span> $row[message]\n";
				$_SESSION['donotrepeat'] = $row['id'];
				$log['text'] = $text;
			}

		/*
        	if(file_exists('chat.txt')){
        	   $lines = file('chat.txt');
        	 }
        	 $count =  count($lines);
        	 if($state == $count){
        		 $log['state'] = $state;
        		 $log['text'] = false;
        		 
        		 }
        		 else{
        			 $text= array();
        			 $log['state'] = $state + count($lines) - $state;
        			 foreach ($lines as $line_num => $line)
                       {
        				   if($line_num >= $state){
                         $text[] =  $line = str_replace("\n", "", $line);
        				   }
         
                        }
        			 $log['text'] = $text; 
        		 }
		*/
        	  
             break;
    	 
		case('send'):
			$nickname = htmlentities(strip_tags($_POST['nickname']));
			$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
			$message = htmlentities(strip_tags($_POST['message']));
			if(($message) != "\n"){
        			if(preg_match($reg_exUrl, $message, $url)) {
      					$message = preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $message);
				} 
		        	//fwrite(fopen('chat.txt', 'a'), "<span>". $nickname . "</span>" . $message = str_replace("\n", " ", $message) . "\n"); 
	
				 $date = date("Ymd");
				 $time = date("H:i:s");
				 $sql = "INSERT INTO `messages` (`appID`,`userID`,`message`,`date`,`time`,`app_version`) VALUES ('$_SESSION[appID]','$_SESSION[id]','".$message = str_replace("\n", " ", $message)."','$date','$time','$_SESSION[app_version]')";
				 $result = $chat->new_mysql($sql);
			 }
		break;
    	
    }
    
    echo json_encode($log);

?>
