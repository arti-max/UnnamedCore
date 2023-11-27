<?php
include_once __DIR__ . "/ip_in_range.php";
class lib {
	public function getUserID($udid, $userName = "Undefined") {
		include __DIR__ . "/db.php";
		$query = $db->prepare("SELECT userID FROM users WHERE udid LIKE BINARY :id");
		$query->execute([':id' => $udid]);
		if ($query->rowCount() > 0) {
			$userID = $query->fetchColumn();
		} else {
			$query = $db->prepare("INSERT INTO users (udid, userName)
			VALUES (:id, :userName)");

			$query->execute([':id' => $udid, ':userName' => $userName]);
			$userID = $db->lastInsertId();
		}
		return $userID;
	}
	public function getUDID($userID) {
		include __DIR__ . "/db.php";
		$query = $db->prepare("SELECT udid FROM users WHERE userID = :id");
		$query->execute([':id' => $userID]);
		if ($query->rowCount() > 0) {
			return $query->fetchColumn();
		}else{
			return 0;
		}
	}
	public function getAudioTrack($id) {
		$songs = ["Stereo Madness by ForeverBound",
			"Back on Track by DJVI",
			"Polargeist by Step",
			"Dry Out by DJVI",
			"Base after Base by DJVI",
			"Can't Let Go by DJVI",
			"Jumper by Waterflame"];
		if($id < 0 || $id >= count($songs))
			return "Unknown by DJVI";
		return $songs[$id];
	}
}
?>