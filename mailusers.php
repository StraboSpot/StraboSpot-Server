<?php
/**
 * File: mailusers.php
 * Description: Handles mailusers operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

if(!in_array($userpkey, array(3, 7217))) exit("access denied");

$users = $db->get_results("
				select * from users
				where
				active
				and email like '%@%'
				and pkey not in (select users_pkey from unsubscribed_users)
				order by lastname, firstname
	");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="strabo_users.csv"');

echo '"Last Name","First Name","Email Address"'."\n";

foreach($users as $user){
	echo '"'.$user->lastname . '","' . $user->firstname.'","' . $user->email .'"'."\n";
}

?>