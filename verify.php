<?php
    $access_token = 'yucixks88GKG6RmFHC3qwK8EuEY1CLq3oXAkJCUTyLIMxN7bq17SIMDHTibPiKMF4vBXgOgU2f9rZMFTjWBc1JcFcC/RJXVdXERvkmPo0GTOBfUBESQ8o7KLHiRdvY83uVCVDuGEmbSaUEt/vBOdNAdB04t89/1O/w1cDnyilFU=';
    
    $url = 'https://api.line.me/v1/oauth/verify';
    
    $headers = array('Authorization: Bearer ' . $access_token);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    
    echo $result;
