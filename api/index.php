<?php
#walkntrade.com API for ../framework

if(isset($_POST["intent"]))
	$getIntent = htmlspecialchars($_POST["intent"]);
else
	$getIntent = null;

switch($getIntent){
	case "getPosts":
		require_once "../framework/Walkntrade.php";
		$wt = new Walkntrade();
		header ("Content-Type:text/xml");
		$query= htmlspecialchars($_POST["query"]);
		$school= htmlspecialchars($_POST["school"]);
		$category= htmlspecialchars($_POST["cat"]);
		$offset= htmlspecialchars($_POST["offset"]);
		$sort= htmlspecialchars($_POST["sort"]);
		$amount= htmlspecialchars($_POST["amount"]);
		if(isset($_POST["ellipse"]))
			echo $wt->getPosts($query, $school, $category, $sort, $offset, $amount, 1);
		else
			echo $wt->getPosts($query, $school, $category, $sort, $offset, $amount, 0);
		break;
	case "getPostByIdentifier":
		require_once "../framework/Walkntrade.php";
		$wt = new Walkntrade();
		$args=array_keys($_POST);
		$args = split(":", $args[1]);
		$identifier = htmlspecialchars($args[1]);
		$school = htmlspecialchars($args[0]);
		echo $wt->getPostByIdentifier($identifier, $school);
		break;

	case "getSchools":
		require_once "../framework/Walkntrade.php";
		$wt = new Walkntrade();
		header ("Content-Type:text/xml");
		$query= htmlspecialchars($_POST["query"]);
		$wt->getSchools($query);
		break;

	case "controlPanel":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		$oldPw = filter_var($_POST["oldPw"], FILTER_SANITIZE_STRING);
		$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
		$newPw = filter_var($_POST["newPw"], FILTER_SANITIZE_STRING);
		$phone = filter_var($_POST["phone"], FILTER_SANITIZE_NUMBER_INT);
		switch($um->controlPanel($oldPw, $email, $newPw, $phone)){
			case "1";
			echo "Not authorized";
			break;
			case "2";
			echo "Not authorized";
			break;
			case "3";
			echo "One or more of your settings are redundant and have not been changed. You will be logged out now so your changes to take effect. If you changed your email, you will need to verify it before you may log in.";
			break;
			case "0";
			echo "Your settings have been saved. You will be logged out now in order for your changes to take effect. If you changed your email, you will need to verify it before you may log in.";
			break;
			case "301";
			echo "No Act";
			break;
			case "11";
			echo "This email address exists...";
			break;
			default:
			echo "Internal error. Please report this <a href='/feedback'>here</a>.";
			break;
		}
		break;

	case "checkPassword":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		$password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
		if($um->checkPassword($password))
			echo "0";
		break;

	case "checkUsername":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		$username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
		if($um->checkUsername($username))
			echo 0;
		else
			echo 1;
		break;

	case "getPostsCurrentUser":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		header ("Content-Type:text/xml");
		echo $um->getPostsCurrentUser();
		break;

	case "getUserProfile":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		$uid = (isset($_POST["uid"])) ? filter_var($_POST["uid"], FILTER_SANITIZE_NUMBER_INT) : null;
		header ("Content-Type:text/xml");
		if($uid != null)
			echo $um->getUserProfile($uid);
		break;

	case "getWebmail":
		require_once "../framework/UserMgmt.php";
		$quiet = (isset($_POST["quiet"]) && $_POST["quiet"] == "true")?true:false;
		$um = new UserMgmt();
		header ("Content-Type:text/xml");
		echo $um->getWebmail($quiet);
		break;

	case "getSentWebmail":
		require_once "../framework/UserMgmt.php";
		$quiet = (isset($_POST["quiet"]) && $_POST["quiet"] == "true")?true:false;
		$um = new UserMgmt();
		header ("Content-Type:text/xml");
		echo $um->getSentWebmail($quiet);
		break;

	case "getMessage":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		$id = (isset($_POST["message_id"])) ? htmlspecialchars($_POST["message_id"], FILTER_SANITIZE_NUMBER_INT) : null;
		if($id == null){
			echo "no message specified";
			return;
		}
		else{
			header ("Content-Type:text/xml");
			echo $um->getMessage($id);
		}	
		break;

	case "pollNewWebmail":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		header ("Content-Type:text/xml");
		echo $um->pollNewWebmail();
		break;

	case "setEmailPref":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		$pref = (isset($_POST["pref"])) ? htmlspecialchars($_POST["pref"], FILTER_SANITIZE_NUMBER_INT) : null;
		echo $um->setEmailPref($pref);
		break;

	case "getEmailPref":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		echo $um->getEmailPref();
		break;

	case "removeMessage":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		$id = (isset($_POST["message_id"])) ? htmlspecialchars($_POST["message_id"], FILTER_SANITIZE_NUMBER_INT) : null;
		if($id == null){
			echo "no message specified";
			return;
		}
		else{
			header ("Content-Type:text/xml");
			echo $um->removeMessage($id);
		}	
		break;

	case "login":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		if (isset($_POST["email"], $_POST["password"])){
			$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
			$password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
			$rememberMe = (isset($_POST["rememberMe"]) && $_POST["rememberMe"]=="true") ? true : false;
			$r = $um->login($email, $password, $rememberMe);
			switch($r){
				case(0):
				echo("success");
				break;
				case(1):
				echo("Username or password incorrect.");
				break;
				case(2):
				echo("verify");
				break;
				case(5):
				echo("Your account has been banned for foul language. We warned you!");
				break;
				case(450):
				echo("reset");
				break;
				default:
				echo("Internal server error. ($r)");
				break;
			}
		}
		break;

	case "logout":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		if(isset($_POST["GCMClear"]))
			$GCMClear = ($_POST["GCMClear"] == "true") ? true : false;
		if($GCMClear)
			$um->removeAndroidDeviceId();
		$um->logout();
		break;

	case "getAvatar":
		require_once "../framework/BinaryHandler.php";
		$bh = new BinaryHandler();
		echo $bh->getAvatar();
		break;

	case "uploadAvatar":
		if(!isset($_FILES["avatar"])){
			echo "You gotta' choose a new image first!";
			return;
		}
		require_once "../framework/BinaryHandler.php";
		$bh = new BinaryHandler();	
		$bh->uploadAvatar($_FILES['avatar']);
		break;

	case "getUserName":
		require_once "../framework/CredentialStore.php";
		$cs = new CredentialStore();
		echo $cs->getUserName();
		break;

	case "addPost":
		require_once "../framework/PostQuery.php";
		$pq = new PostQuery();
		$category = (isset($_POST["cat"])) ? $_POST["cat"] : "";
		$title = (isset($_POST["title"])) ? $_POST["title"] : "";
		$author = (isset($_POST["author"])) ? $_POST["author"] : "";
		$details = (isset($_POST["details"])) ? $_POST["details"] : "";
		$price = (isset($_POST["price"])) ? $_POST["price"] : 0;
		$location = (isset($_POST["location"])) ? $_POST["location"] : "";
		$tags = (isset($_POST["tags"])) ? $_POST["tags"] : "";
		$isbn = (isset($_POST["isbn"])) ? $_POST["isbn"] : 0;
		$school= $pq->cookieCheck("sPref");
			if($pq->getSchoolName($school) == null) ### Prevent from inserting into nonexisting db ###
			return "500: Request malformed";
			$response = $pq->addPost($category, $school, $title, $author, $details, $price, $location, $tags, $isbn);
			switch($response){
				case 1:
				echo "Not authorized";
				break;
				case 2:
				echo "An internal error has occured. Please try again later ($response)";
				break;
				default:
				echo $response;
				break;
			}
		break;

	case "removePost":
		require_once "../framework/PostQuery.php";
		$pq = new PostQuery();
		$args=array_keys($_POST);
		$args = split(":", $args[1]);
		$identifier = htmlspecialchars($args[1]);
		$school = htmlspecialchars($args[0]);
		switch($pq->removePost($identifier, $school)){
			case(0):
			echo("success");
			break;
			case(1):
			echo("You are not authorized for this operation.");
			break;
			default:
			echo"Internal server error, Please report this error <a href='/feedback'>here</a>";
			break;
		}
		break;

	case "renewPost":
		require_once "../framework/PostQuery.php";
		$pq = new PostQuery();
		$args=array_keys($_POST);
		$args = split(":", $args[1]);
		$identifier = htmlspecialchars($args[1]);
		$school = htmlspecialchars($args[0]);
		switch($pq->renewPost($identifier, $school)){
			case(0):
			echo("success");
			break;
			case(1):
			echo("You are not authorized for this operation.");
			break;
			default:
			echo"Internal server error, Please report this error <a href='/feedback'>here</a>";
			break;
		}
		break;

	case "addAndroidDeviceId":
		require_once "../framework/UserMgmt.php";
		$wt = new UserMgmt();
		$deviceId = $_POST["deviceId"];
		$r = $wt->addAndroidDeviceId($deviceId);
		switch ($r) {
			case 0:
				echo "success";
				break;

			case 1:
				echo "Not authorized";
				break;
			
			default:
				echo "An internal error has occured.";
				break;
		}
		break;

	case "uploadPostImages":
		require_once "../framework/BinaryHandler.php";
		$bh = new BinaryHandler();
		$binImage = $_FILES['image'];
		$iteration = $_POST['iteration'];
		$identifier  =$_POST["identifier"];
		$school = $bh->cookieCheck("sPref");
		if($bh->getSchoolName($school) == null) ### Prevent from inserting into nonexisting directory ###
		return "501: Request malformed";
		echo $bh->uploadPostImages($binImage, $iteration, $identifier, $school);
		break;

	case "editPost":
		require_once "../framework/PostQuery.php";
		$pq = new PostQuery();
		$school = filter_var($_POST["school"], FILTER_SANITIZE_STRING);
		if($pq->getSchoolName($school) == null) ### Prevent from inserting into nonexisting db ###
		return "500: Request malformed";
		$title = $_POST["title"];
		$details = $_POST["details"];
		$price = filter_var($_POST["price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$tags = $_POST["tags"];
		$identifier = $_POST["identifier"];
		$result = $pq->editPost($title, $details, $price, $tags, $identifier, $school);
		switch($result){
			case 401:
			echo "You are not authorized to do this!";
			break;
			case 4:
			echo "You have not made any changes";
			break;
			case 5:
			echo "isbn length incorrect";
			break;
			case 6:
			echo "details too long or too short";
			break;
			case 7:
			echo "author length too long or short";
			break;
			case 8:
			echo "title length too long or short";
			break;
			case 9:
			echo "tags length too long or short";
			break;
			case 0:
			echo "success";
			break;
			default:
			echo "An internal error has occured. Please try again later. ($result)";
			break;
		}
		break;

	case "messageUser":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		$uid = (isset($_POST["uid"])) ? filter_var($_POST["uid"], FILTER_SANITIZE_NUMBER_INT) : null;
		$userName = (isset($_POST["userName"])) ? filter_var($_POST["userName"], FILTER_SANITIZE_STRING) : null;
		$title = $_POST["title"];
		$message = $_POST["message"];
		if($uid != null){
			$result = $um->messageUser($uid, $title, $message);
			switch($result){
				case 0:
				echo "success";
				break;
				case 5:
				echo "Foul language is not tolerated here! You have been warned.";
				break;
				default:
				echo "An internal error has occured. Please report this error <a href='/feedback'>here</a> ($result)";
				break;
			}
			return;
		}
		else if($userName != null){
			$uid = $um->resolveUsernameToID($userName);
			if($uid != null){
				$result = $um->messageUser($uid, $title, $message);
				switch($result){
					case 0:
					echo "success";
					break;
					case 5:
					echo "Foul language is not tolerated here! You have been warned.";
					break;
					default:
					echo "An internal error has occured. Please report this error <a href='/feedback'>here</a> ($result)";
					break;
				}
			}
			else echo "There is no user by this name in our system.";
			return;
		}
		else echo "Invalid recipient!";
		break;

	case "addUser":
		require_once "../framework/UserMgmt.php";
		$um = new UserMgmt();
		$username = filter_var(strip_tags($_POST["username"]), FILTER_SANITIZE_STRING);
		$email = filter_var(strip_tags($_POST["email"]), FILTER_SANITIZE_EMAIL);
		$password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
		$phone = filter_var($_POST["phone"], FILTER_SANITIZE_NUMBER_INT);
		$result = $um->addUser($username, $email, $password, $phone);
		switch($result){
			case 0:
			echo 0;
			break;
			case 3:
			echo 3;
			break;
			case 7:
			echo "Email may not be empty";
			break;
			case 4:
			echo "Username is taken";
			break;
			case 9:
			echo "check username string_length";
			break;
			case 100:
			echo "Not student email";
			break;						
			default:
			echo "An error has occured. We apoligize for the inconvience. Please report this error <a href='/feedback'>here</a> and we'll get on it. ($result)";
			break;
		}
		break;

	case "verifyKey":
		require_once "../framework/Walkntrade.php";
		$wt = new Walkntrade();
		$key = filter_var($_POST["key"], FILTER_SANITIZE_NUMBER_INT);
		switch($wt->verifyKey($key)){
			case "0":
			echo "Your email address has been verified!";
			break;
			case "2":
			echo "Either the link is no longer valid or you mistyped the key.";
			break;
			case "4":
			echo "No key provided.";
			break;
			default:
			echo "Internal Server Error. Please report this error <a href='/feedback'>here</a> and we'll get on it.";
			break;
		}
		break;

	case "resetPassword":
		require_once "../framework/Walkntrade.php";
		$wt = new Walkntrade();
		$r = $wt->resetPassword(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL));
		switch ($r) {
			case '0':
			echo "Thanks! Check your inbox for the new password";
			break;
			case '3':
			echo "We had an issue sending the email, your password has not been changed. This is our fault, please try again in a few minutes. If the problem persists please report this error <a href='/feedback'>here</a>.";
			break;
			case "5":
			echo "We couldn't find that email in our system... O_o";
			break;
			default:
			echo "An error has occured, your password has not been changed. Please report this error <a href='/feedback'>here</a> (".$r.")";
			break;
		}
		break;

	case "sendFeedback":
		require_once "../framework/Walkntrade.php";
		$wt = new Walkntrade();
		if(isset($_POST["message"])){
			$email = (isset($_POST["email"])) ? $_POST["email"] : "";
			$r = $wt->sendFeedback($email, $_POST["message"]);
			switch ($r) {
				case 0:
				echo "Thanks for your feedback!";
				break;
				case 3:
				echo "An error has occured... Can you try again? If it still doesn't work you can email us at wt@walkntrade.com and we'll get on it! :)";
			break;
			}
		}
		break;

	case "getPhoneNum":
		require_once "../framework/CredentialStore.php";
		$cs = new CredentialStore();
		echo $cs->getPhoneNum();
		break;
	default:
		echo "Hi there!";
	break;
}?>