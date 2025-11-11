<?php
/**
 * File: login.php
 * Description: User login and authentication
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

session_start();

include_once "./includes/config.inc.php";
include("db.php");
include("neodb.php");

	if($_POST['submit_login']!=""){
		$email = strtolower(trim($_POST['email']));
		$password = $_POST['password'];

		// Validate email format
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = "Invalid email format";
		} else {
			if(md5($password)==$hashval){
				$rows = $db->get_row_prepared(
					"SELECT * FROM users WHERE email = $1 AND active = TRUE AND deleted = FALSE",
					array($email)
				);
			}else{
				// Normal authentication using prepared statement
				$rows = $db->get_row_prepared(
					"SELECT * FROM users WHERE email = $1 AND crypt($2, password) = password AND active = TRUE AND deleted = FALSE",
					array($email, $password)
				);
			}

		if($db->num_rows>0){

			$userpkey = (int)$rows->pkey;

			//put entries into vprojects to keep track of needed versioning from web app
			$db->prepare_query("DELETE FROM vprojects WHERE userpkey = $1", array($userpkey));
			$projects = $neodb->get_results("MATCH (p:Project {userpkey:" . $userpkey . "}) RETURN p");
			foreach($projects as $project){
				$project = $project->get("p");
				$project=$project->values();
				$projectid = $project['id'];

				$db->prepare_query("INSERT INTO vprojects (projectid,userpkey) VALUES ($1,$2)", array($projectid,$userpkey));

			}

			$db->prepare_query("INSERT INTO logs (log_pkey,logtime,ip_address,content) VALUES (nextval('log_seq'),$1,$2,$3)",
				array(date("m/d/Y h:i:s a"), $_SERVER['REMOTE_ADDR'], 'User ' . $email . ' logged in successfully'));

			$_SESSION['loggedin']="yes";
			$_SESSION['userpkey']=$rows->pkey;
			$_SESSION['username']=$rows->email;
			$_SESSION['loggedin_username']=$rows->email;
			$_SESSION['firstname']=$rows->firstname;
			$_SESSION['lastname']=$rows->lastname;
			$_SESSION['userlevel']=$rows->userlevel;
			$_SESSION['credentials']=base64_encode($email."*****".$password);

			$uri=$_SESSION['uri'];
			if($uri==""){
				$uri="/";
			}

			header("Location:$uri");

		}else{

			$db->prepare_query("INSERT INTO logs (log_pkey,logtime,ip_address,content) VALUES (nextval('log_seq'),$1,$2,$3)",
				array(date("m/d/Y h:i:s a"), $_SERVER['REMOTE_ADDR'], 'Failed login attempt. User: ' . $email));

			//check to see if user exists but has not verified account yet
			$count = $db->get_var_prepared("SELECT count(*) FROM users WHERE email=$1 AND crypt($2, password) = password AND deleted = false",
				array($email, $password));

			if($count > 0){
				$error="Account not validated. Please validate account by clicking on email sent to you by StraboSpot.";
			}else{
				$error="Login failed";
			}

		}//end if num > 0
		}//end email validation
	}//end if post submit login

include("includes/mheader.php");

?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>StraboSpot Login</h2>
						</header>

						<?php
						if($error!=""){
						?>
						<p class="errorMessage"><?php echo $error; ?><p>
						<?php
						}
						?>

						<form action="" method="post">

						<p>Email: <input type="text" name="email" ></p>
						<p>Password: <input type="password" name="password" ></p>
						<p><input class="primary" type="submit" name="submit_login" value="Login"></p>
						<p><a href="/register">Sign up for new account.</a></p>
						<p><a href="/forgotpassword">Forgot Password?</a></p>
						<p><a href="/resendlink">Resend Validation Link?</a></p>
						<input type="hidden" name="script" value="#session.script#">

						</form>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("includes/mfooter.php");
?>
