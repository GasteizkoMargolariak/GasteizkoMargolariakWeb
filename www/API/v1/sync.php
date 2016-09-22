<?php
	Gasteizko Margolariak API v1
	
	define('DEF_FORMAT', 'json');
	
	/****************************************************
	* Echoes the version of one or more of the sections *
	* of the database, or the global section.           * 
	*                                                   *
	* @params:                                          *
	*    con: (MySQL server connection) RO mode enough. *
	*    section: (string): 'blog', 'activities',       *
	*             'gallery', 'lablanca', 'global'. None *
	*             to get them all.                      *
	*    format: (string): 'json' (default).            *
 	****************************************************/
	function get_version(con, section = '', format = DEF_FORMAT){
		//TODO
	}
	
	/****************************************************
	* Echoes the contents of a table from the database. *
	* Inaccessible or sensitive tables or fields are    *
	* not printed.                                      *
	*                                                   *
	* @params:                                          *
	*    con: (MySQL server connection) RO mode enough. *
	*    format: (string): 'json' (default).            *
 	****************************************************/
	function get_table(con, format = DEF_FORMAT){
		//TODO
	}
?>
