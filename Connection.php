<?php
include_once('IConnectInfo.php');
	class Connection implements IConnectInfo {
		private static $server = IConnectInfo::HOST;
		private static $currentDB = IConnectInfo::DBNAME;
		private static $user = IConnectInfo::UNAME;
		private static $pass = IConnectInfo::PW;
		private static $hookup;
		
		public static function doConnect(){
			self::$hookup = mysqli_connect(self::$server, self::$user, self::$pass, self::$currentDB);
			if (mysqli_connect_error(self::$hookup)){
				printf("error connecting to db server! error description: %s", mysqli_connect_error());
			}
			return self::$hookup;
		}
	}
?>
