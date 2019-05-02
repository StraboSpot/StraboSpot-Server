<?
include_once "./includes/config.inc.php";
include("db.php");

if($_FILES['file']!=""){

	function getArcID(){
		$chars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0");
		for($x=0;$x<25;$x++){
			$arcid.=$chars[rand(0,61)];
		}
	
		return $arcid;
	}

	//create a random string to store in table
	$arcid = getArcID();

	$name = $_FILES['file']['name'];
	$type = $_FILES['file']['type'];
	$tmp_name = $_FILES['file']['tmp_name'];
	$error = $_FILES['file']['error'];
	$size = $_FILES['file']['size'];

	$pkey = $db->get_var("select nextval('arcfiles_pkey_seq')");

	move_uploaded_file ( $tmp_name , "arcfiles/$pkey" );

	$db->query("
				insert into arcfiles (pkey,name,type,tmp_name,error,size,arcid)
								values
								(
									$pkey,
									'$name',
									'$type',
									'$tmp_name',
									'$error',
									'$size',
									'$arcid'
								)
				");


	if(is_writable("test.txt")){

		$stringData = print_r($_FILES,true);

		$myFile = "test.txt";
		$fh = fopen($myFile, 'a');

		fwrite($fh, $stringData);
		fwrite($fh, $arcid);

		fclose($fh);
	
	}

	echo $arcid;

}elseif($_GET['arcid']!=""){





}








?>