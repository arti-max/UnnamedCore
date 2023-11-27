<?php
chdir(dirname(__FILE__));
include "../config/db.php";
require_once "../lib/mainLib.php";

$lib = new lib();

$username = $_POSt["userName"];
$levelID = $_POST["levelID"];
$levelname = $_POST["levelName"];
$levelDesc = $_POSt["levelDesc"];
$levelString = $_POST["levelString"];
$levelVersion = $_POST["levelVersion"];
$levelLength = $_POST["levelLength"];
$audioTrack = $_POST["audioTrack"];
$gameVersion = $_POST["gameVersion"];
$userID = $lib->getUserID($id, $userName);

if ($levelString != "" AND $levelname != "") {
    $query = $db->prepare("UPDATE levels SET levelName=:levelName, userName=:userName, levelID=:levelID, levelDesc=:levelDesc, levelVersion=:levelVersion, levelLength=:levelLength, audioTrack=:audioTrack, gameVersion=:gameVersion, userID=:userID, levelString=:levelString");
    $query->execute([':levelID' => $levelID, 'userName' => $username, ':levelName' => $levelname, ':levelDesc' => $levelDesc, ':levelVersion' => $levelVersion, ':levelLength' => $levelLength, ':audioTrack' => $audioTrack, ':gameVersion' => $gameVersion, 'userID' => $userID, ':levelString' => $levelString]);
    file_put_contents("../data/$levelID",$levelString);
    echo $levelID;
} else {
    echo("-1");
}


?>