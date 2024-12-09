<?php


namespace Modules\Auth\Util;



class AuthUtil
{


    /**
     * send Activation code For User
     *
     * @param object $user
     * @return array
     */
    public function SendActivationCode($user){
//        if($user->phone == "0555555555"){
            $user->activation_code=1111;
            $user->save();
            return [
                'status'=>true,
                'message'=>'',
            ];
//        }
//        $user->activation_code=rand(1000, 9999);
//        $user->save();
//        $phones=["966{$user->phone}"];
//        $ms=__('translation.activation_code',['code'=>$user->activation_code]);
//        $data=$this->SendSMS($ms,$phones);
//        return $data;

    }


    /**
     * converty currency base on exchange rate
     *
     * @param String $text
     * @param array $numbers
     * @return array
     */
    public function SendSMS(string $text, array $numbers): array
    {

        $app_id = env('SMS_API_KEY');
        $app_sec = env('SMS_API_SEC');
        $app_sender = env('SMS_API_SENDER');
        $app_hash = base64_encode("{$app_id}:{$app_sec}");

        $messages = [
            "messages" => [
                [
                    "text" => "{$text}",
                    "numbers" => $numbers,
                    "sender" => "{$app_sender}"
                ]
            ]
        ];

        $url = "https://api-sms.4jawaly.com/api/v1/account/area/sms/send";
        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "Authorization: Basic {$app_hash}"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($messages));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $response_json = json_decode($response, true);
        if ($status_code == 200) {
            if (isset($response_json["messages"][0]["err_text"])) {
                return [
                    'status'=>false,
                    'message'=>$response_json["messages"][0]["err_text"],
                ];

            } else {
                return [
                    'status'=>true,
                    'message'=>'',
                ];
            }
        } elseif ($status_code == 400) {
            return [
                'status'=>false,
                'message'=>$response_json["message"],
            ];
        } elseif ($status_code == 422) {
            return [
                'status'=>false,
                'message'=> translate('message_not_found'),
            ];
        }

        return [
            'status'=>false,
            'message'=>  "محظور بواسطة كلاودفلير. Status code: {$status_code}"
        ];


    }
}
