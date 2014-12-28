<?php

//========================================================================
// MemHT Portal
// 
// Copyright (C) 2008-2012 by Miltenovikj Manojlo <dev@miltenovik.com>
// http://www.memht.com
// 
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your opinion) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License along
// with this program; if not, see <http://www.gnu.org/licenses/> (GPLv2)
// or write to the Free Software Foundation, Inc., 51 Franklin Street,
// Fifth Floor, Boston, MA02110-1301, USA.
//========================================================================

/**
 * @author      Miltenovikj Manojlo <dev@miltenovik.com>
 * @copyright	Copyright (C) 2008-2012 Miltenovikj Manojlo. All rights reserved.
 * @license     GNU/GPLv2 http://www.gnu.org/licenses/
 */

//Deny direct access
defined("_LOAD") or die("Access denied");

class BaseDbLayer {
	var $Queries = array();
	protected $db_link;

	function __construct() {
		global $config_db;

		$this->db_link = ($config_db['persistent']) ? @mysqli_connect("p:".$config_db['host'],$config_db['user'],$config_db['pass'],$config_db['name']) : @mysqli_connect($config_db['host'],$config_db['user'],$config_db['pass'],$config_db['name']) ;

		//Check connection
		if (!$this->db_link) Error::Trigger("FATAL","Unable to connect to the database server",@mysqli_error($this->db_link));

		//Select database
		if (!mysqli_select_db($this->db_link,$config_db['name'])) Error::Trigger("FATAL","Unable to select the database");

		//Set UTF-8 communication
		if (!@mysqli_query($this->db_link,"SET NAMES 'utf8'"))
			Error::Trigger("WARNING","Unable to set UTF-8 communication with the database server (1)",@mysqli_error($this->db_link));
		if (!@mysqli_query($this->db_link,"SET character_set_server = 'utf8'"))
			Error::Trigger("WARNING","Unable to set UTF-8 communication with the database server (2)",@mysqli_error($this->db_link));
	}

	function __destruct() {
		//Disconnect
		@mysqli_close($this->db_link);
	}

	function Query($query) {
		global $config_sys;

		//TODO: Optional query filter?
		if ($config_sys['debug']==1) { $start = Utils::GetMicrotime(); }
		$result = mysqli_query($this->db_link,$this->ReplacePrefix($query));
		$errtrace = debug_backtrace();
		if ($config_sys['debug']==1) { $this->Queries[] = array("speed" => sprintf("%01.6f",Utils::GetMicrotime()-$start), "query" => $query, "rows" => mysqli_affected_rows($this->db_link),"file" => $errtrace[1]['file'], "line" => $errtrace[1]['line'], "error" => mysqli_error($this->db_link)); }
		return ($result) ? $result : false ;
	}

	function GetRow($query) {
		$result = $this->Query($query);
		$returned = mysqli_fetch_assoc($result);
		@mysqli_free_result($result);
		return $returned;
	}

	function GetList($query) {
		$returned = array();

		$result = $this->Query($query);
		while ($row = mysqli_fetch_assoc($result)) {
			$returned[] = $row;
		}

		@mysqli_free_result($result);
		return $returned;
	}

	function GetNum($query) {
		$result = $this->Query($query);
		$num = mysqli_num_rows($result);
		@mysqli_free_result($result);
		return $num;
	}

	function AffectedRows(){
		return mysqli_affected_rows($this->db_link);
	}

	function InsertId(){
		return mysqli_insert_id($this->db_link);
	}

	function ReplacePrefix($query) {
		global $config_db;

		return str_replace("#__",$config_db['prefix']."_",$query);
	}

	function GetPrefix() {
		return $this->db_prefix;
	}

	function GetNumQueries() {
		return sizeof($this->Queries);
	}

	function GetQueries() {
		return $this->Queries;
	}

    function GetError() {
        return mysqli_error($this->db_link);
    }

    function GetErrno() {
        return mysqli_errno($this->db_link);
    }

	function _e($string) {
		return mysqli_real_escape_string($this->db_link,$string);
	}
}

?>