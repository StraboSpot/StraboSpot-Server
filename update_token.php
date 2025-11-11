<?php
/**
 * File: update_token.php
 * Description: Handles update token operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("logincheck.php");
include("prepare_connections.php");

$username = $_SESSION['username'];
$apptoken = $uuid = $uuid->v4();
$db->get_var("DELETE FROM apptokens WHERE created_on < NOW() - INTERVAL '24 hours'");
$db->prepare_query("INSERT INTO apptokens (uuid, email) VALUES ($1, $2)", array($apptoken, $username));

$out = new stdClass();
$out->tokencreds = base64_encode($username."*****".$apptoken);
header('Content-type: application/json');
echo json_encode($out);

?>