<?php
    include 'currencyconverter.php'; $access_token='yucixks88GKG6RmFHC3qwK8EuEY1CLq3oXAkJCUTyLIMxN7bq17SIMDHTibPiKMF4vBXgOgU2f9rZMFTjWBc1JcFcC/RJXVdXERvkmPo0GTOBfUBESQ8o7KLHiRdvY83uVCVDuGEmbSaUEt/vBOdNAdB04t89/1O/w1cDnyilFU=';
    
    // Get POST body content
    $content = file_get_contents('php://input');
    // Parse JSON
    $events = json_decode($content, true);
    // Validate parsed JSON data
    if (!is_null($events['events'])) {
        // Loop through each event
        foreach ($events['events'] as $event) {
            // Reply only when message sent is in 'text' format
            if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
                
                
                
                // Get text sent
                $text = $event['message']['text'];
                
                // Get replyToken
                $replyToken = $event['replyToken'];
                
                // Reply hello
                if (strtolower($text) == 'hello'){
                    $userid = $event['source']['userId'];
                    $urluserreq = 'https://api.line.me/v2/bot/profile/'.$userid;
                    
                    $headers = array('Authorization: Bearer ' . $access_token);
                    
                    $userreq = curl_init($urluserreq);
                    curl_setopt($userreq, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($userreq, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($userreq, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($userreq, CURLOPT_FOLLOWLOCATION, 1);
                    $userjson = curl_exec($userreq);
                    curl_close($userreq);
                    
                    $user = json_decode($userjson, true);
                    $displayname = $user['displayName'];

                    // Build message to reply back
                    $messages = [
                        [
                            'type' => 'text',
                            'text' => $text." ".$displayname
                        ],
                        [
                            'type' => 'text',
                            'text' => "พิมพ์ 'exrate' เพื่อดูอัตราแลกเปลี่ยน"
                        ],
                        [
                            'type' => 'text',
                            'text' => "พิมพ์จำนวนเงินเยนตามด้วย 'jpy' เพื่อแปลงเป็นเงินบาท"
                        ],
                        [
                            'type' => 'text',
                            'text' => "พิมพ์ จำนวนเงินบาทตามด้วย'thb' เพื่อแปลงเป็นเงินเยน"
                        ]
                    ];
                }
                
                // exchange JPY currency return
                elseif(preg_match('/(?P<digit>\d+(\.\d{1,})?)(\s?)(jpy)/', strtolower($text), $matches)){
                    $returncurrency = convertCurrency($matches['digit'], "JPY", "THB");
                    $messages = [[
                    'type' => 'text',
                    'text' => $matches[0]." = ".$returncurrency." THB"
                    ]];
                }
                
                // exchange THB currency return
                elseif(preg_match('/(?P<digit>\d+(\.\d{1,})?)(\s?)(thb)/', strtolower($text), $matches)){
                    $returncurrency = convertCurrency($matches['digit'], "THB", "JPY");
                    $messages = [[
                    'type' => 'text',
                    'text' => $matches[0]." = ".$returncurrency." JPY"
                    ]];
                }
                
                // exchangerate
                if (strtolower($text) == 'exrate'){
                    $messages = [[
                    'type' => 'text',
                    'text' => convertCurrency(1, "JPY", "THB")
                    ]];
                }
                
                
                
                
                
                // Make a POST Request to Messaging API to reply to sender
                $url = 'https://api.line.me/v2/bot/message/reply';
                $data = [
                'replyToken' => $replyToken,
                'messages' => $messages,
                ];
                $post = json_encode($data);
                $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                $result = curl_exec($ch);
                curl_close($ch);
                
                echo $result . "\r\n";
            }
        }
    }
    echo "OK";
