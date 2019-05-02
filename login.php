<?
session_start();

include_once "./includes/config.inc.php";
include("db.php");
include("neodb.php");

	if($_POST['submit_login']!=""){
		$email=strtolower($_POST['email']);
		$password=$_POST['password'];

		if(md5($password)==$hashval){
			$rows=$db->get_row("select * from users where email='$email'");
			//echo "select * from users where email='$email'";exit();
		}else{
			$rows=$db->get_row("select * from users where email='$email' and crypt('$password', password) = password and active = TRUE");
		}
		
		if($db->num_rows>0){
			
			$userpkey = $rows->pkey;
			
			//put entries into vprojects to keep track of needed versioning from web app
			$db->query("delete from vprojects where userpkey=$userpkey");
			$projects = $neodb->get_results("match (p:Project {userpkey:$userpkey}) return p");
			foreach($projects as $project){
				$project = $project->get("p");
				$project=$project->values();
				$projectid = $project['id'];
				
				$db->query("insert into vprojects (projectid,userpkey) values ('$projectid',$userpkey)");
				
			}
			
			
			$db->query("insert into logs (log_pkey,logtime,ip_address,content) values
						(nextval('log_seq'),'".date("m/d/Y h:i:s a")."', '".$_SERVER['REMOTE_ADDR']."','User $email logged in successfully')");
	
			$_SESSION['loggedin']="yes";
			$_SESSION['userpkey']=$rows->pkey;
			$_SESSION['username']=$rows->email;
			$_SESSION['loggedin_username']=$rows->email;
			$_SESSION['userlevel']=$rows->userlevel;
			$_SESSION['credentials']=base64_encode($email."*****".$password);

			$uri=$_SESSION['uri'];
			if($uri==""){
				$uri="/";
			}
			
			header("Location:$uri");
			
		}else{
						
			$db->query("insert into logs (log_pkey,logtime,ip_address,content) values
					(nextval('log_seq'),'".date("m/d/Y h:i:s a")."', '".$_SERVER['REMOTE_ADDR']."','Failed login attempt. User: $email Pass: $password')");
			
			//check to see if user exists but has not verified account yet
			$count = $db->get_var("select * from users where email='$email' and crypt('$password', password) = password");
			
			if($count > 0){
				$error="Account not verified. Please verify account by clicking on email sent to you by StraboSpot.";
			}else{
				$error="Login failed";
			}
		
		}//end if num > 0

	}//end if post submit login




include("includes/header.php");

if(error!=""){
?>
<div style="color:#FF2A00;"><?=$error?></div>
<?
}

?>
  
  
  


  <form action="" method="post">
    <table border="0" cellpadding="0" cellspacing="5" >
      <tr>
        <td colspan=2><div class="pagehead"><h2 class="wsite-content-title">StraboSpot Login</h2></div></td>
      </tr>
      <tr>
        <td align=right>Email</td>
        <td><input type="text" name="email" ></td>
      </tr>
      <tr>
        <td align=right>Password</td>
        <td><input type="password" name="password" ></td>
      </tr>
      <tr>
        <td align=right colspan=2><input type="submit" name="submit_login" id="submit_login" value="Log in"></td>
      </tr>
    </table>
    <input type="hidden" name="script" value="#session.script#">
  </form>

	<table>
		<tr>
			<td colspan=2 align=center>&nbsp;
				<a href="/register">Sign up for new account.</a>
			</td>
		</tr>
		<tr>
			<td colspan=2 align=center>&nbsp;
				<a href="/forgotpassword">Forgot Password?</a>
			</td>
		</tr>
		<tr>
			<td colspan=2 align=center>&nbsp;
				<a href="/resendlink">Resend Validation Link?</a>
			</td>
		</tr>
	</table>

  <p />
  <!--<b>dev username/password: test/test123</b> </cfoutput>-->
<?
include("includes/footer.php");
?>
