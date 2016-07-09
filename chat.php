<?php
if ($check_login == "TRUE") {
?>
    
        <h2>Live Group Chat</h2>
       
        <div id="loading"><font color=orange>Updates every 2 seconds ... </font><img src="img/ajax-loader.gif"><br><br></div>
 
        <p id="name-area"></p>
        
        <div id="chat-wrap"><div id="chat-area"></div></div>

	<div id="lb">        
        <form id="send-message-area">
            <p>&nbsp;&nbsp;Your message:<br>&nbsp;&nbsp;
		<input type="button" value="Logout" class="btn btn-primary" onclick="document.location.href='/chat/<?=$_GET['section'];?>/<?=$_GET['id'];?>/signout'">&nbsp; 
		<input type="button" value="Profile" class="btn btn-primary" onclick="document.location.href='/chat/<?=$_GET['section'];?>/<?=$_GET['id'];?>/profile'">
		</p>
            <textarea id="sendie" maxlength = '100' placeholder="Press return to send" ></textarea>
        </form>
	</div>
    
<?php
} else {
	$chat->login($null);
}
?>
