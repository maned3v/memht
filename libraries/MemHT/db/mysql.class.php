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

		$this->db_link = ($config_db['persistent']) ? @mysql_pconnect($config_db['host'],$config_db['user'],$config_db['pass']) : @mysql_connect($config_db['host'],$config_db['user'],$config_db['pass']) ;

		//Check connection
		if (!$this->db_link) Error::Trigger("FATAL","Unable to connect to the database server",@mysql_error($this->db_link));

		//Select database
		if (!@mysql_select_db($config_db['name'],$this->db_link)) Error::Trigger("FATAL","Unable to select the database");

		//Set UTF-8 communication
		if (!@mysql_query("SET NAMES 'utf8'",$this->db_link))
			Error::Trigger("WARNING","Unable to set UTF-8 communication with the database server (1)",@mysql_error($this->db_link));
		if (!@mysql_query("SET character_set_server = 'utf8'",$this->db_link))
			Error::Trigger("WARNING","Unable to set UTF-8 communication with the database server (2)",@mysql_error($this->db_link));
	}

	function __destruct() {
		//Disconnect
		@mysql_close($this->db_link);
	}

	function Query($query) {
		global $config_sys;

		//TODO: Optional query filter?
		if ($config_sys['debug']==1) { $start = Utils::GetMicrotime(); }
		$result = mysql_query($this->ReplacePrefix($query),$this->db_link);
		$errtrace = debug_backtrace();
		if ($config_sys['debug']==1) { $this->Queries[] = array("speed" => sprintf("%01.6f",Utils::GetMicrotime()-$start), "query" => $query, "rows" => mysql_affected_rows($this->db_link),"file" => $errtrace[1]['file'], "line" => $errtrace[1]['line'], "error" => mysql_error($this->db_link)); }
		return ($result) ? $result : false ;
	}

	function GetRow($query) {
		$result = $this->Query($query);
		$returned = mysql_fetch_assoc($result);
		@mysql_free_result($result);
		return $returned;
	}

	function GetList($query) {
		$returned = array();

		$result = $this->Query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$returned[] = $row;
		}

		@mysql_free_result($result);
		return $returned;
	}

	function GetNum($query) {
		$result = $this->Query($query);
		$num = mysql_num_rows($result);
		@mysql_free_result($result);
		return $num;
	}

	function AffectedRows(){
		return mysql_affected_rows($this->db_link);
	}

	function InsertId(){
		return mysql_insert_id($this->db_link);
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
        return mysql_error($this->db_link);
    }

    function GetErrno() {
        return mysql_errno($this->db_link);
    }

	function _e($string) {
		return mysql_real_escape_string($string);
	}
}

?>