<?php
include_once __DIR__ . "/ip_in_range.php";

class Lib {
    public function getIDFromPost(){
		include_once __DIR__ . "/exploitPatch.php";
		include_once __DIR__ . "/GJPCheck.php";

		if($_POST["udid"] AND $_POST['gameVersion'] < 20) 
		{
			$id = $_POST["udid"];
			if($id) exit("-1");
		}
		else
		{
			exit("-1");
		}
		return $id;
	}

    public function getUserID($udid, $userName = "Undefined") {
		include __DIR__ . "/connection.php";
		
		$query = $db->prepare("SELECT userID FROM users WHERE udid LIKE BINARY :id");
		$query->execute([':id' => $udid]);
		if ($query->rowCount() > 0) {
			$userID = $query->fetchColumn();
		} else {
			$query = $db->prepare("INSERT INTO users (udid, userName, lastPlayed)
			VALUES (:id, :userName, :uploadDate)");

			$query->execute([':id' => $udid, ':userName' => $userName, ':uploadDate' => time()]);
			$userID = $db->lastInsertId();
		}
		return $userID;
	}
    public function isCloudFlareIP($ip) {
    	$cf_ips = array(
	        '173.245.48.0/20',
			'103.21.244.0/22',
			'103.22.200.0/22',
			'103.31.4.0/22',
			'141.101.64.0/18',
			'108.162.192.0/18',
			'190.93.240.0/20',
			'188.114.96.0/20',
			'197.234.240.0/22',
			'198.41.128.0/17',
			'162.158.0.0/15',
			'104.16.0.0/13',
			'104.24.0.0/14',
			'172.64.0.0/13',
			'131.0.72.0/22'
	    );
	    foreach ($cf_ips as $cf_ip) {
	        if (ipInRange::ipv4_in_range($ip, $cf_ip)) {
	            return true;
	        }
	    }
	    return false;
	}
    public function getIP(){
		if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && $this->isCloudFlareIP($_SERVER['REMOTE_ADDR'])) //CLOUDFLARE REVERSE PROXY SUPPORT
  			return $_SERVER['HTTP_CF_CONNECTING_IP'];
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ipInRange::ipv4_in_range($_SERVER['REMOTE_ADDR'], '127.0.0.0/8')) //LOCALHOST REVERSE PROXY SUPPORT (7m.pl)
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		return $_SERVER['REMOTE_ADDR'];
	}
}
?>