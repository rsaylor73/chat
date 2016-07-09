<?php

if( !class_exists( 'Chat')) {
	class Chat {
	        public $linkID;

	        function __construct($linkID){ $this->linkID = $linkID; }

	        public function new_mysql($sql) {
        	        $result = $this->linkID->query($sql) or die($this->linkID->error.__LINE__);
	                return $result;
	        }

		public function device_type() {
			return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|iphone|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
		}

		public function check_error($id) {
			if (is_numeric($id)) {
				// ok
			} else {
				print "<br><br><font color=red>Sorry, but you have called the program incorrectly.</font><br><br>";
				die;
			}
		}

		public function signout() {
			session_destroy();
			print "<br><br><center>You have been signed out. Loading...<br><br></center>";
                        print "<meta http-equiv=\"refresh\" content=\"3; url=/chat/$_GET[section]/$_GET[id]\">";

		}

	        public function check_login() {
        	        $sql = "SELECT `id` FROM `users` WHERE `uuname` = '$_SESSION[uuname]' AND `uupass` = '$_SESSION[uupass]'";
	                $result = $this->new_mysql($sql);
        	        while ($row = $result->fetch_assoc()) {
                	        $found = "1";
	                }
        	        if ($found == "1") {
	                        return "TRUE";
        	        } else {
                	        return "FALSE";
	                }
	        }


		public function register($msg) {
			$login = str_replace("/register","",$_SERVER['REQUEST_URI']);
			$login = str_replace("/complete","",$login);
                        $login = str_replace("/signout","",$login);

                        if ($msg != "") {
                                print "<center><font color=red>$msg</font></center><br>";
                        }
			print "<form action=\"$_GET[section]/$_GET[id]/complete\" method=\"post\">
			<input type=\"hidden\" name=\"section\" value=\"$_GET[section]\">
			<input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">
			";
			print "<table class=\"table\">
			<tr><td colspan=2><b>Please complete the form below to register with our chat</b></td></tr>
			<tr><td>First Name:</td><td><input type=\"text\" name=\"fname\" size=20 required></td></tr>
			<tr><td>Last Name:</td><td><input type=\"text\" name=\"lname\" size=20 required></td></tr>
			<tr><td>Screen Alias:</td><td><input type=\"text\" name=\"alias\" size=20 required></td></tr>
			<tr><td>Email:</td><td><input type=\"text\" name=\"email\" size=20 required></td></tr>
			<tr><td>Username:</td><td><input type=\"text\" name=\"uuname\" size=20 required></td></tr>
			<tr><td>Password:</td><td><input type=\"password\" name=\"uupass\" size=20 required></td></tr>
			<tr><td><input type=\"button\" class=\"btn btn-success\" value=\"Login\" onclick=\"document.location.href='$login'\"></td><td><input type=\"submit\" class=\"btn btn-primary\" value=\"Register\"></td></tr>
			</table>
			</form>
			";
		}

		public function complete_register() {
			// check data
			$found = "0";
			$sql = "SELECT `uuname` FROM `users` WHERE `uuname` = '$_POST[uuname]'";
			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$found = "1";
			}
			if ($_POST['uupass'] == "") {
				$found = "2";
			}
			$sql = "SELECT `email` FROM `users` WHERE `email` = '$_POST[email]'";
			$result = $this->new_mysql($sql);
                        while ($row = $result->fetch_assoc()) {
				$found = "3";
			}
			switch ($found) {
				case "1":
				$msg = "The username <b>$_POST[uuname]</b> is not available.";
				$this->register($msg);
				break;

				case "2":
				$msg = "You did not enter a valid password.";
				$this->register($msg);
				break;

				case "3":
				$msg = "The email <b>$_POST[email]</b> is not available.";
				$this->register($msg);
				break;

				default:
				$pw = md5($_POST['uupass']);
				$today = date("Ymd");
				$sql = "INSERT INTO `users` (`fname`,`lname`,`email`,`uuname`,`uupass`,`alias`,`date_registered`) VALUES ('$_POST[fname]','$_POST[lname]','$_POST[email]','$_POST[uuname]','$pw','$_POST[alias]','$today')";
				$result = $this->new_mysql($sql);
				if ($result == "TRUE") {
					$msg = "<font color=green>You have been registered. Please login below:</font>";
					$this->login($msg);
				} else {
					$msg = "There was an error registering you. Please do not use any symbols in your name.";
					$this->register($msg);
				}
				break;
			}
		}

	        // Login form
	        public function login($msg) {

                        $login = str_replace("/register","",$_SERVER['REQUEST_URI']);
                        $login = str_replace("/complete","",$login);
                        $login = str_replace("/login","",$login);
                        $login = str_replace("/signout","",$login);

			$register = str_replace("/register","",$_SERVER['REQUEST_URI']);
                        $register = str_replace("/complete","",$register);
                        $register = str_replace("/login","",$register);
                        $register = str_replace("/signout","",$register);


			$forgot = str_replace("/register","",$_SERVER['REQUEST_URI']);
			$forgot = str_replace("/complete","",$forgot);
                        $forgot = str_replace("/login","",$forgot);
                        $forgot = str_replace("/signout","",$forgot);
                        $forgot = str_replace("/forgot","",$forgot);

        	        if ($msg != "") {
                	        print "<center>$msg</center><br>";
        	        }
	                print "
        	        <br>
        	        <form action=\"$login/login\" method=\"post\">
                	<table class=\"table\">
			<tr><td colspan=2 align=center>Chat Login</td></tr>
                        <tr><td>Username:</td><td><input type=\"text\" name=\"uuname\" size=20></td></tr>
                        <tr><td>Password:</td><td><input type=\"password\" name=\"uupass\" onkeypress=\"if(event.keyCode==13) { login(this.form); return false;}\" size=20></td></tr>
       	                <tr><td><input type=\"button\" class=\"btn btn-success\" value=\"Register\" onclick=\"document.location.href='$register/register'\"></td><td><input type=\"submit\" class=\"btn btn-primary\" value=\"Login\"></td></tr>
               	        <tr><td><a href=\"javascript:void(0)\" onclick=\"document.location.href='$forgot/forgot'\">Forgot Password?</a></td><td>
	                </table>
        	        </form>
	                <br>";
	        }

		public function login_user() {
			$pw = md5($_POST['uupass']);
			$sql = "SELECT * FROM `users` WHERE `uuname` = '$_POST[uuname]' AND `uupass` = '$pw'";
			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				foreach ($row as $key=>$value) {
					$_SESSION[$key] = $value;
				}
			$found = "1";
			}
			switch ($found) {
				case "1":
					print "<br><font color=green>You have been logged in. Loading...</font><br>";
					print "<meta http-equiv=\"refresh\" content=\"3; url=/chat/$_GET[section]/$_GET[id]\">";
				break;

				default:
				$msg = "<font color=red>Sorry, the username or password was incorrect.</font>";
				$this->login($msg);
				break;
			}
		}

		public function forgot() {

                        $reset = str_replace("/complete","",$_SERVER['REQUEST_URI']);
                        $reset = str_replace("/login","",$reset);
                        $reset = str_replace("/signout","",$reset);
                        $reset = str_replace("/forgot","",$reset);
			$reset = str_replace("/reset","",$reset);
			$reset = str_replace("/register","",$reset);

			$_SESSION['r1'] = rand(1,5);
			$_SESSION['r2'] = rand(1,5);
			
			print "<br>
                        <form action=\"$reset/reset\" method=\"post\">
                        <table class=\"table\">
                        <tr><td colspan=2 align=center>Forgot Password</td></tr>
                        <tr><td>Your Registered Email Address:</td><td><input type=\"text\" name=\"email\" size=20 required></td></tr>
			<tr><td>What is $_SESSION[r1] plus $_SESSION[r2]?</td><td><input type=\"text\" name=\"answer\" size=20 placeholder=\"Solve the math question\"></td></tr>
			<tr><td colspan=2><b>Note:</b> this will reset your password to a random password.</td></tr>
                        <tr><td>&nbsp;</td><td><input type=\"submit\" class=\"btn btn-primary\" value=\"Reset Password\"></td></tr>
                        </table>
                        </form>
                        <br>";

		}

		private function randomPassword() {
			$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
			$pass = array(); //remember to declare $pass as an array
			$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
			for ($i = 0; $i < 8; $i++) {
				$n = rand(0, $alphaLength);
				$pass[] = $alphabet[$n];
			}
			return implode($pass); //turn the array into a string
		}

		public function reset_pw() {
                        $login = str_replace("/register","",$_SERVER['REQUEST_URI']);
                        $login = str_replace("/complete","",$login);
                        $login = str_replace("/login","",$login);
                        $login = str_replace("/signout","",$login);
			$login = str_replace("/reset","",$login);

			$answer = $_SESSION['r1'] + $_SESSION['r2'];
			if ($_POST['answer'] != $answer) {
				print "<br><br><font color=red>Sorry, but you did not answer the math question correctly. Please click back and try again.</font><br><br>";
				die;
			}

			$new_pw = $this->randomPassword();
			$new_pw_md5 = md5($new_pw);

			$sql = "SELECT `uuname` FROM `users` WHERE `email` = '$_POST[email]'";
			$result = $this->new_mysql($sql);
			while ($row = $result->fetch_assoc()) {
				$uuname = $row['uuname'];
				$found = "1";
			}

	
			if ($found == "1") {
				$sql = "UPDATE `users` SET `uupass` = '$new_pw_md5' WHERE `email` = '$_POST[email]'";
				$result = $this->new_mysql($sql);

				$subj = "Your chat password";
				$msg = "You have requested your chat password to be reset. Your new password is:<br><br>
				Username: $uuname<br>
				Password: $new_pw<br><br>
				";

				mail($_POST['email'],$subj,$msg,email_headers);
			}

			// we don't tell the user if the email was valid so they can not guess if it is
			print "<br><br>Your password has been reset. If the email you supplied was valid then the password will be in your inbox.<br><br>Click <a href=\"$login/login\">here</a> to login.<br><br>";
		}

		public function profile() {
			$check_login = $this->check_login();
			if ($check_login == "TRUE") {

	                        $profile = str_replace("/register","",$_SERVER['REQUEST_URI']);
        	                $profile = str_replace("/complete","",$profile);
                	        $profile = str_replace("/login","",$profile);
                        	$profile = str_replace("/signout","",$profile);
	                        $profile = str_replace("/reset","",$profile);
				$profile = str_replace("/update","",$profile);
				$profile = str_replace("/profile","",$profile);

				$sql = "SELECT * FROM `users` WHERE `uuname` = '$_SESSION[uuname]' AND `uupass` = '$_SESSION[uupass]'";
				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
	                        print "<br>
        	                <form action=\"$profile/update\" method=\"post\">
                	        <table class=\"table\">
	                        <tr><td colspan=2 align=center>Profile</td></tr>
				<tr><td>First Name:</td><td><input type=\"text\" name=\"fname\" value=\"$row[fname]\" size=20 required></td></tr>
				<tr><td>Last Name:</td><td><input type=\"text\" name=\"lname\" value=\"$row[lname]\" size=20 required></td></tr>
				<tr><td>Username:</td><td>$row[uuname]</td></tr>
				<tr><td>Password:</td><td><input type=\"password\" name=\"uupass\" size=20 placeholder=\"************\"></td></tr>
				<tr><td>Alias:</td><td>$row[alias]</td></tr>
				<tr><td>Email:</td><td><input type=\"text\" name=\"email\" value=\"$row[email]\" size=20 required></td></tr>
	                        <tr><td>&nbsp;</td><td><input type=\"submit\" class=\"btn btn-primary\" value=\"Update Profile\"></td></tr>
        	                </table>
                	        </form>
	                        <br>";
				}
			} else {
				print "<br><font color=red>Your session has expired. Please close the app and re-login.</font><br>";
			}
		}

		public function update() {
                        $check_login = $this->check_login();

                        $rtn = str_replace("/register","",$_SERVER['REQUEST_URI']);
                        $rtn = str_replace("/complete","",$profile);
                        $rtn = str_replace("/login","",$profile);
                        $rtn = str_replace("/signout","",$profile);
                        $rtn = str_replace("/reset","",$profile);
                        $rtn = str_replace("/update","",$profile);
                        if ($check_login == "TRUE") {
				if ($_POST['uupass'] != "") {
					$new_pw = md5($_POST['uupass']);
					$pw_sql = ",`uupass` = '$new_pw'";
				}
				$sql = "SELECT `email` FROM `users` WHERE `email` = '$_POST[email]' AND `uuname` != '$_SESSION[uuname]'";
				$result = $this->new_mysql($sql);
				while ($row = $result->fetch_assoc()) {
					$found = "1";
				}
				if ($found == "1") {
					print "<br><br><font color=red>Sorry, but the email <b>$_POST[email]</b> is registered with another user.</font><br><br>";
					die;
				}

				$sql = "UPDATE `users` SET `fname` = '$_POST[fname]', `lname` = '$_POST[lname]', `email` = '$_POST[email]' $pw_sql WHERE `uuname` = '$_SESSION[uuname]'";
				$result = $this->new_mysql($sql);

				if ($result == "TRUE") {
					print "<br><br>Your profile has been changed. If you updated your password you will be forced to log back in. To return to chat please click <a href=\"$rtn/chat/$_GET[section]/$_SESSION[appID]\">here</a>.<br>";
				} else {
					print "<br><br><font color=red>There was an unknown error updating your profile.</font><br><br>";
				}

                        } else {
                                print "<br><font color=red>Your session has expired. Please close the app and re-login.</font><br>";
                        }

		}

	/* end of class */
	}
}
