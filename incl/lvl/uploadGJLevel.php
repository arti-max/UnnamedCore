<?php
chdir(dirname(__FILE__));
include("../incl/lib/connection.php");
require_once("../incl/lib/lib.php");
$lib = new Lib();
require_once("../incl/lib/lib.php");
$gs = new Lib();

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

$id = $gs->getIDFromPost();
$userID = $lib->getUserID($id, $userName);
$hostname = $gs->getIP();
$uploadDate = time();
$query = $db->prepare("SELECT count(*) FROM levels WHERE uploadDate > :time AND (userID = :userID OR hostname = :ip)");
$query->execute([':time' => $uploadDate - 60, ':userID' => $userID, ':ip' => $hostname]);
if($query->fetchColumn() > 0){
	exit("-1");
}
$query = $db->prepare("INSERT INTO levels (levelName, userName, userID, udid, levelLength, levelVersion, levelDesc, gameVersion, audioTrack, secret, uploadDate)
VALUES (:levelName, :userName, :userID, :udid, :levelLength, :levelVersion, :levelDesc, :gameVersion, :audioTrack, :secret, :uploadDate)");

if($levelString != "" AND $levelName != "") {
    $querye=$db->prepare("SELECT levelID FROM levels WHERE levelName = :levelName AND userID = :userID");
    $querye->execute([':levelName' => $levelName, ':udid' => $udid]);
    $levelID = $querye->fetchColumn();
    $lvls = $querye->rowCount();
    if($lvls==1){
		$query = $db->prepare("UPDATE levels SET levelName=:levelName, userName=:userName, levelString=:levelString, levelID=:levelID, levelLength=:levelLength, levelVersion=:levelVersion, audioTrack=:audioTrack, gameVersion=:gameVersion, levelDesc=:levelDesc, secret=:secret WHERE levelName=:levelName AND udid=:udid");	
		$query->execute([':levelName' => $levelName, ':userName' => $userName, ':levelString' => "", ':levelID' => $levelID, ':udid' => $udid, ':levelLength' => $levelLength, ':levelVersion' => $levelVersion, ':audioTrack' => $audioTrack, ':gameVersion' => $gameVersion, ':levelDesc' => $levelDesc, ':secret' => $secret]);
		file_put_contents("../data/$levelID",$levelString);
		echo $levelID;
    } else {
        $query->execute([':levelName' => $levelName, ':userName' => $userName, ':levelString' => "", ':levelID' => $levelID, ':udid' => $udid, ':levelLength' => $levelLength, ':levelVersion' => $levelVersion, ':audioTrack' => $audioTrack, ':gameVersion' => $gameVersion, ':levelDesc' => $levelDesc, ':secret' => $secret]);
        $levelID = $db->lastInsertId();
        file_put_contents("../data/$levelID",$levelString);
		echo $levelID;
    }

} else {
    echo("-1");
}

?>