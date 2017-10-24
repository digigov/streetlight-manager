<?php
use GuzzleHttp\Client;

class Line extends MY_Controller {


    public function line_connect(){
        
        $code = $this->input->get("token");

        $URL = 'https://notify-bot.line.me/oauth/authorize?';
        $URL .= 'response_type=code';
        $URL .= '&client_id='.LINE_NOTIFY_CLIENT_ID;
        $URL .= '&redirect_uri='.site_url("line/line_callback");
        $URL .= '&scope=notify';
        $URL .= '&state='.$code;
        $URL .= '&response_mode=form_post';
        return redirect($URL);
        
    }

    public function line_callback(){
        $client = new Client();
        try{
            $res = $client->request('POST', 'https://notify-bot.line.me/oauth/token', [
                'form_params' => [
                    "grant_type" => "authorization_code",
                    "code" => $this->input->post('code', ''),
                    'client_id' => LINE_NOTIFY_CLIENT_ID,
                    'client_secret' => LINE_NOTIFY_CLIENT_SECRET,
                    'redirect_uri' => site_url("line/line_callback"),
                ]
            ]);
            // "200"

            // die(var_dump( $res));
            // echo $res->getHeader('content-type');
            // 'application/json; charset=utf8'
            
            $obj = json_decode($res->getBody()->getContents());
            
            if($this->input->post('state') == ""){
                die("綁定失敗");
            }else{

                $this->load->database();
                $this->load->model("accountModel");
                $this->accountModel->set_user_line_token($this->input->post('state'),
                    $obj->access_token);
            }
            
            die("綁定成功");
            
            // die(var_dump($res->getBody()->getContents()));
        }catch(\Exception $ex){
            //invalid code
            die(var_dump($ex));        
        }
    }

}
