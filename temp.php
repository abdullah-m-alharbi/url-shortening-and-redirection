       

<?php

$myPublicIP = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com")); //public ip on a local server (XAMPP)
//$details = json_decode(file_get_contents("http://ipinfo.io/{$myPublicIP}/json"));
       
//$customerCity = $details->city;
//$customerCountry = $details->country;

//var_dump($details);

//$ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
//echo ("My public IP: ".$ip);

echo $myPublicIP;


?>