<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://actvisual.vidyocloud.com/services/VidyoPortalAdminService?wsdl');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
print_r( $output );
curl_close($ch);