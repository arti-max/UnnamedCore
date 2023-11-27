<?php
chdir(dirname(__FILE__));
include "../incl/lib/connection.php";
require_once "../incl/lib/mainLib.php";
$lib = new mainLib();
require_once "../incl/lib/mainLib.php";
$gs = new mainLib();

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
if($gameVersion < 20){
	$rawDesc = $levelDesc;
	$levelDesc = str_replace('+', '-', base64_encode($rawDesc));
	$levelDesc = str_replace('/', '_', $levelDesc);
}

$userID = $lib->getUserID($id, $userName);
$id = $gs->getIDFromPost();
$hostname = $gs->getIP();
$uploadDate = time();
$query = $db->prepare("SELECT count(*) FROM levels WHERE uploadDate > :time AND (userID = :userID OR hostname = :ip)");
$query->execute([':time' => $uploadDate - 60, ':userID' => $userID, ':ip' => $hostname]);
if($query->fetchColumn() > 0){
	exit("-1");
}

$query = $db->prepare("INSERT INTO levels (levelName, levelID, userName, userID, levelDesc, levelLength, levelVersion, audioTrack, gameVersion, levelString, udid, secret, uploadDate)
VALUES (:levelName, :levelID, :userName, :userID, :levelDesc, :levelLength, :levelVersion, :audioTrack, :gameVersion, :levelString, :id, :secret, :uploadDate)");

if ($levelString != "" AND $levelname != "") {

    $querye=$db->prepare("SELECT levelID FROM levels WHERE levelName = :levelName AND userID = :userID");
	$querye->execute([':levelName' => $levelName, ':userID' => $userID]);
	$levelID = $querye->fetchColumn();
	$lvls = $querye->rowCount();

    if ($lvls == 0) {
        $query = $db->prepare("UPDATE levels SET userName=:userName, levelID=:levelID, levelDesc=:levelDesc, levelVersion=:levelVersion, levelLength=:levelLength, audioTrack=:audioTrack, gameVersion=:gameVersion, secret=:secret, userID=:userID, :id => :id WHERE levelName=:levelName AND udid=:udid");
        $query->execute([':levelID' => $levelID, ':userName' => $userName, ':levelName' => $levelName, ':levelDesc' => $levelDesc, ':levelVersion' => $levelVersion, ':levelLength' => $levelLength, ':audioTrack' => $audioTrack, ':gameVersion' => $gameVersion, ':udid' => $udid, ':secret' => $secret, ':userID' => $userID, ':id' => $id]);
        file_put_contents("../data/$levelID",$levelString);
        echo $levelID;
    } else {
        $query->execute([':levelID' => $levelID, ':userName' => $userName, ':levelName' => $levelName, ':levelDesc' => $levelDesc, ':levelVersion' => $levelVersion, ':levelLength' => $levelLength, ':audioTrack' => $audioTrack, ':gameVersion' => $gameVersion, ':udid' => $udid, ':secret' => $secret, ':userID' => $userID, ':id' => $id]);
        $levelID = $db->lastInsertId();
        file_put_contents("../data/$levelID",$levelString);
        echo $levelID;
    }
} else {
    echo("-1");
}

echo $levelString;

?>