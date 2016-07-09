<?php
session_start();
include "settings.php";

$device = $chat->device_type(); // 0 = desktop ; 1 = mobile

include "header.php";

$check_login = $chat->check_login();

$check_error = $chat->check_error($_GET['id']);
if ($_GET['id'] != "") {
	$_SESSION['appID'] = $_GET['id'];
}
if ($_GET['section'] != "") {
	$_SESSION['app_version'] = $_GET['section'];
}

if ($check_login != "TRUE") {
	if ($_GET['action'] == "register") {
		$chat->register($null);
	} elseif ($_GET['action'] == "complete") {
		$chat->complete_register();
	} elseif ($_GET['action'] == "login") {
		$chat->login_user();
	} elseif ($_GET['action'] == "signout") {
		$chat->signout();
	} elseif ($_GET['action'] == "forgot") {
		$chat->forgot();
	} elseif ($_GET['action'] == "reset") {
		$chat->reset_pw();
	} else {
		$chat->login($null);
	}
} else {
	if ($_GET['action'] == "signout") {
		$chat->signout();
	} elseif ($_GET['action'] == "profile") {
                $chat->profile();
        } elseif ($_GET['action'] == "update") {
                $chat->update();
	} else {
		include "chat.php";
	}
}
include "footer.php";
?>
