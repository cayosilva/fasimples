<?php
	interface IConnectInfo {
		const HOST = "127.0.0.1";
		const UNAME = "root";
		const PW = "";
		const DBNAME = "fasimples";
		public static function doConnect();
	}
?>