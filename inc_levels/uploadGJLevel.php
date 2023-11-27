<?php
chdir(dirname(__FILE__));
include("../incl/lib/connection.php");

$userName = $_POST["userName"];
$levelID = $_POST["levelID"];
$levelName = $_POST["levelName"];
$levelString = $_POST["levelString"];
$levelVersion = $_POST["levelVersion"];
$levelLength = $_POST["levelLength"];
$audioTrack = $_POST["audioTrack"];
$gameVersion = $_POST["gameVersion"];
$udid = $_POST["udid"];
$secret = $_POST["secret"];
$levelDesc = $_POST["levelDesc"];

if($levelString != "" AND $levelName != "") {
    $querye=$db->prepare("SELECT levelID FROM levels WHERE levelName = :levelName AND udid = :udid");
    $querye->execute([':levelName' => $levelName, ':udid' => $udid]);
    $levelID = $querye->fetchColumn();
    $lvls = $querye->rowCount();
    if($lvls==1){
		$query = $db->prepare("UPDATE levels SET levelName=:levelName, userName=:userName, levelString=:levelString, levelID=:levelID, levelLength=:levelLength, levelVersion=:levelVersion, audioTrack=:audioTrack, gameVersion=:gameVersion, levelDesc=:levelDesc, secret=:secret WHERE levelName=:levelName AND udid=:udid");	
		$query->execute([':levelName' => $levelName, ':userName' => $userName, ':levelString' => $levelString, ':levelID' => $levelID, ':udid' => $udid, ':levelLength' => $levelLength, ':levelVersion' => $levelVersion, ':audioTrack' => $audioTrack, ':gameVersion' => $gameVersion, ':levelDesc' => $levelDesc, ':secret' => $secret]);
		file_put_contents("../data/$levelID",$levelString);
		echo $levelID;
    } else {
        $query->execute([':levelName' => $levelName, ':userName' => $userName, ':levelString' => $levelString, ':levelID' => $levelID, ':udid' => $udid, ':levelLength' => $levelLength, ':levelVersion' => $levelVersion, ':audioTrack' => $audioTrack, ':gameVersion' => $gameVersion, ':levelDesc' => $levelDesc, ':secret' => $secret]);
        $levelID = $db->lastInsertId();
        file_put_contents("../data/$levelID",$levelString);
		echo $levelID;
    }

} else {
    echo("-1");
}

?>