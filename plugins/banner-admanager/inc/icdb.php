<?php
class ICDB {
	var $server = "";
	var $db = "";
	var $user = "";
	var $password = "";
	var $prefix = "";
	var $insert_id;
	
	var $link;

	function __construct($_server, $_db, $_user, $_password, $_prefix) {
		$this->server = $_server;
		$this->db = $_db;
		$this->user = $_user;
		$this->password = $_password;
		$this->prefix = $_prefix;
		$this->link = mysqli_connect($this->server, $this->user, $this->password) or die("Could not connect: " . mysqli_error($this->link));
		mysqli_select_db($this->link, $this->db) or die ('Can\'t use database : ' . mysqli_error($this->link));
	}
	
	function get_row($_sql) {
		$result = mysqli_query($this->link,$_sql) or die("Invalid query: " . mysqli_error($this->link));
		$row = mysqli_fetch_assoc($result);
		mysqli_free_result($result);
		return $row;
	}
	
	function get_rows($_sql) {
		$rows = array();
		$result = mysqli_query($this->link,$_sql) or die("Invalid query: " . mysqli_error($this->link));
		while ($row = mysqli_fetch_assoc($result)) {
			$rows[] = $row;
		}
		mysqli_free_result($result);
		return $rows;
	}

	function get_var($_sql) {
		$result = mysqli_query($this->link,$_sql) or die("Invalid query: " . mysqli_error($this->link));
		$row = mysqli_fetch_array($result);
		mysqli_free_result($result);
		if ($row && is_array($row)) return $row[0];
		return false;
	}
	
	function query($_sql) {
		$result = mysqli_query($this->link,$_sql) or die("Invalid query: " . mysqli_error($this->link));
		$this->insert_id = mysqli_insert_id($this->link);
		return $result;
	}

}
?>