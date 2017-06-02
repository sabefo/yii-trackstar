<?php
	class DbTest extends CTestCase {
		public function testConnection() {
			$connection = new CDbConnection("mysql:host=127.0.0.1;dbname=trackstar_dev", "root", "root");
			$this -> assertNotEquals(NULL, $connection);
		}
	}
?>
