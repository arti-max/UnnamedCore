<?php
chdir(dirname(__FILE__));
include "../incl/lib/connection.php";
require_once "../incl/lib/mainLib.php";
$lib = new lib();

$userName = $_POSt["userName"];
$levelID = $_POST["levelID"];
$levelName = $_POST["levelName"];
$levelDesc = $_POSt["levelDesc"];
$levelString = $_POST["levelString"];
$levelVersion = $_POST["levelVersion"];
$levelLength = $_POST["levelLength"];
$audioTrack = $_POST["audioTrack"];
$gameVersion = $_POST["gameVersion"];
$udid = $_POST["udid"];
$secret = $_POST["secret"];

$userID = $lib->getUserID($id, $userName);

$query = $db->prepare("INSERT INTO levels (levelName, levelID, userName, userID, levelDesc, levelLength, levelVersion, audioTrack, gameVersion, levelString, udid, secret);
VALUES (:levelName, :levelID, :userName, :userID. :levelDesc, :levelLength, :audioTrack, :gameVersion, :levelVersion, :levelString, :udid, :secret)");

if ($levelString != "" AND $levelname != "") {

    $querye=$db->prepare("SELECT levelID FROM levels WHERE levelName = :levelName AND userID = :userID");
	$querye->execute([':levelName' => $levelName, ':userID' => $userID]);
	$levelID = $querye->fetchColumn();
	$lvls = $querye->rowCount();

    if ($lvls == 0) {
        $query = $db->prepare("UPDATE levels SET levelName=:levelName, userName=:userName, levelID=:levelID, levelDesc=:levelDesc, levelVersion=:levelVersion, levelLength=:levelLength, audioTrack=:audioTrack, gameVersion=:gameVersion, udid=:udid, secret=:secret, userID=:userID");
        $query->execute([':levelID' => $levelID, ':userName' => $userName, ':levelName' => $levelName, ':levelDesc' => $levelDesc, ':levelVersion' => $levelVersion, ':levelLength' => $levelLength, ':audioTrack' => $audioTrack, ':gameVersion' => $gameVersion, ':udid' => $udid, ':secret' => $secret, ':userID' => $userID]);
        file_put_contents("../data/$levelID",$levelString);
        echo $levelID;
    } else {
        $query = $db->prepare("UPDATE levels SET levelName=:levelName, userName=:userName, levelID=:levelID, levelDesc=:levelDesc, levelVersion=:levelVersion, levelLength=:levelLength, audioTrack=:audioTrack, gameVersion=:gameVersion, levelString=:levelString, udid=:udid, secret=:secret, userID=:userID");
        $levelID = $db->lastInsertId();
        file_put_contents("../data/$levelID",$levelString);
        echo $levelID;
    }
} else {
    echo("-1");
}

echo $levelString;
?>