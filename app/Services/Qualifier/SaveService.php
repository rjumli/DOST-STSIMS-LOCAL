<?php

namespace App\Services\Qualifier;

/**
 * Class SaveService.
 */
class SaveService
{
    public $link, $api;

    public function __construct()
    {
        $this->link = config('app.api_link');
        $this->api = config('app.api_key');
    }

    public function enroll($request){
        $postData = array(
            'user' => $request->user,
            'school_id' => $request->school_id,
            'course_id' => $request->course_id,
            'account_no' => $request->account_no,
            'type' => $request->type
        );
        $data = $this->sender($postData);
        return back()->with([
            'data' => ($data) ? $data : 'Nothing found.',
            'message' => 'Qualifier updated successfully. Thanks',
            'info' => '-',
            'status' => true,
        ]); 
    }

    public function endorse($request){
        $postData = array(
            'user' => $request->user,
            'school_id' => $request->school_id,
            'course_id' => $request->course_id,
            'type' => $request->type,
        );
        $data = $this->sender($postData);
        return back()->with([
            'data' => ($data) ? $data : 'Nothing found.',
            'message' => 'Endorsed successfully. Thanks',
            'info' => '-',
            'status' => true,
        ]); 
    }

    public function endorsed($request){
        $postData = array(
            'id' => $request->id,
            'user' => $request->user,
            'school_id' => $request->school_id,
            'course_id' => $request->course_id,
            'type' => $request->type,
        );
        $data = $this->sender($postData);
        return back()->with([
            'message' => 'Endorsed successfully. Thanks',
            'data' =>  $data,
            'type' => 'bxs-check-circle'
        ]); 
        
    }

    public function update($request){
        $postData = array(
            'id' => $request->id,
            'is_completed' => 1,
            'type' => 'updated',
            'address_id' => $request->address_id,
            'type' => 'update'
        );
        ($request->municipality_code) ? $postData['municipality_code'] = $request->municipality_code : '';
        ($request->barangay_code) ? $postData['barangay_code'] = $request->barangay_code : '';

        $data = $this->sender($postData);
        return back()->with([
            'data' => ($data) ? $data : 'Nothing found.',
            'message' => 'Qualifier updated successfully. Thanks',
            'info' => '-',
            'status' => true,
        ]); 
    }

    public function edit($request){
        $postData = array(
            'id' => $request->id,
            'status_type' => $request->status_type,
            'type' => 'edit'
        );
        $data = $this->sender($postData);
        return back()->with([
            'data' => ($data) ? $data : 'Nothing found.',
            'message' => 'Qualifier updated successfully. Thanks',
            'info' => '-',
            'status' => true,
        ]); 
    }

    public function sender($postData){
        try{
            $url = $this->link.'/api/01101011%2001110010%2001100001%2001100100/qualifiers';
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->api,
                'Content-Type: application/json',
              ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $datas = json_decode($response);

            return $datas;

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
