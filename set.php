<?php

error_reporting(E_ALL ^ E_DEPRECATED);

date_default_timezone_set('PRC');

header("Access-Control-Allow-Origin:*");

$dbname = "toupiao";
$host = "localhost";
$port = ini_get("mysqli.default_port") ;
$user = "root";
$pwd = "";
/*	
//创建mysqli对象，直接在创建时指定连接信息，也可创建对象后再connect
$conn = @new mysqli($host, $user, $pwd, $dbname, $port);
if($conn->connect_errno) 
{
    die("Connect Server Failed: " . $conn->connect_error);
}
//已在建立连接时指定dbname，无须再select_db
*/
$conn=mysql_connect($host, $user, $pwd) or die("Could not connect: " . mysql_error());

//$conn = mysqli_connect($host, $user, $pwd, $dbname);

//$conn = new PDO('mysql:host=localhost;dbname=ok', $user, $pwd);

mysql_select_db($dbname,$conn);
mysql_query("set names utf8");



//接下来就可以使用其它标准php Mysql函数操作进行数据库操作

//数据库安全查询
function exec_query($sql,$binds = FALSE)
{	
	if (strpos($sql, '?') !== FALSE){

		if ( ! is_array($binds))	$binds = array($binds);

		// Get the sql segments around the bind markers
		$segments = explode('?', $sql);

		// The count of bind should be 1 less then the count of segments
		// If there are more bind arguments trim it down
		if (count($binds) >= count($segments)) {
			$binds = array_slice($binds, 0, count($segments)-1);
		}

		// Construct the binded query
		$sql = $segments[0];
		$i = 0;
		foreach ($binds as $bind)
		{
			$sql .= sys_escape($bind);
			$sql .= $segments[++$i];
		}
	}
	//return @mysql_query($sql, $this->conn_id);

	//print_r($sql.'<br>');
	
	$rs=mysql_query($sql);
	//mysql_close($conn);

	return $rs;
	
	//return mysqli_query($conn, $sql);
}

function sys_escape($str)
{
	if (is_string($str))
	{
		$str = "'".mysql_real_escape_string($str)."'";
	}
	elseif (is_bool($str))
	{
		$str = ($str === FALSE) ? 0 : 1;
	}
	elseif (is_null($str))
	{
		$str = 'NULL';
	}

	return $str;
}
//

function sys_replace($str)
{
	return str_replace("{}","{ }",$str);
}

function guolv(&$array){ 
	while(list($key,$var) = each($array)){ 
		if((strtoupper($key) != $key || ''.intval($key) == "$key") && $key != 'argc' && $key != 'argv'){ 
			if(is_string($var)){ 
				if(stristr($var,'se'.'le'.'ct')) $var = str_replace('se'.'le'.'ct','se'.'l e'.'ct',$var); 
				if(stristr($var,'un'.'io'.'n')) $var = str_replace('un'.'io'.'n','un'.'i o'.'n',$var); 
				if(stristr($var,'ev'.'al')) $var = str_replace('ev'.'al','ev '.'al',$var); 
				if(stristr($var,'<'.'?')) $var = str_replace('<'.'?','< '.'?',$var); 
				if(stristr($var,'?'.'>')) $var = str_replace('?'.'>','?'.' >',$var); 
				$array[$key] = $var; 
			} 
			if(is_array($var)) $array[$key] = guolv($var);   
		} 
	} 
	return $array; 
} 

/*
function guolv(&$array){ 
	while(list($key,$var) = each($array)){ 
		if((strtoupper($key) != $key || ''.intval($key) == "$key") && $key != 'argc' && $key != 'argv'){ 
			if(is_string($var)){ 
				if(stristr($var,'se'.'le'.'ct')) $var = str_replace('se'.'le'.'ct','se'.'l e'.'ct',$var); 
				if(stristr($var,'un'.'io'.'n')) $var = str_replace('un'.'io'.'n','un'.'i o'.'n',$var); 
				if(stristr($var,'ev'.'al')) $var = str_replace('ev'.'al','ev '.'al',$var); 
				if(stristr($var,'<'.'?')) $var = str_replace('<'.'?','< '.'?',$var); 
				if(stristr($var,'?'.'>')) $var = str_replace('?'.'>','?'.' >',$var); 
				$array[$key] = $var; 
			} 
			if(is_array($var)) $array[$key] = guolv($var);   
		} 
	} 
	return $array; 
} 
*/

?>
