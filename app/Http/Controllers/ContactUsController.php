<?php

namespace App\Http\Controllers;

use App\ContactUs;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Validator;
use GuzzleHttp\Client;

class ContactUsController extends Controller
{
    public $successStatus = 200;
    public $errorStatus = 500;
    public $validationStatus = 400;
    public $response =  []; // Response send back to front end

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        User::create([
            'name' => 'me',
            'email' => 'me@me.com',
            'password' => bcrypt('password'),
        ]);
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $client = new Client;
    $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => env('RECAPTCHA_SECRET'),
                'response' => ($request->recaptcha_token),
                // 'remoteip' => Request::ip()
            ]
    ]);

        if(json_decode($response->getBody(), true)['success']) // true = assoc. array
        {
          return $this->ValidationandStoreData($request);
        }
        else
        {
            $this->response['status']  = $this->errorStatus;
            $this->response['statusText'] = 'Server Error';
            $this->response['error'] = true;

            $this->response['msg'] = "Server Errpr: General error : 00001 Captcha could not be verified. (Budyet : 'Front End Field Error')";
            return response()->json($this->response);
        }

       
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ValidationandStoreData($request)
    {
       
        
        //******************** Validation Error Checking Section Started ********************/

        $front_end_request = []; // validation checker array
        $front_end_request['name'] = $request->name;
        $front_end_request['email'] = $request->email['value'];
        $front_end_request['msg'] = $request->message['text'];
        $front_end_request['recaptcha_token'] = $request->recaptcha_token;

        $validator = Validator::make($front_end_request, [ 
        'name' => 'required', 
        'email' => 'required|email', 
        'msg' => 'required', 
        'recaptcha_token' => 'required'
        ]);

        if ($validator->fails()) 
        { 
            $this->response['status']  = $this->validationStatus;
            $this->response['statusText'] = 'Bad Request';
            $this->response['error'] = true;
            $this->response['msg'] = $validator->errors();
            return response()->json($this->response);            
        }

        
        //******************** Validation Error Checking Section End ********************/

        //******************** Add Data to Db Section Start ********************/



        // Data to Store in the Db Array
        $data['name'] = $request->name;
        $data['email'] = $request->email['value'];
        $data['msg'] = $request->message['text'];

        
        try {
            if( ContactUs::create($data))
            {
               $this->response['status']  = $this->successStatus;
               $this->response['statusText'] = 'OK';
               $this->response['error'] = false;
               $this->response['msg'] = 'We will review your query as soon as possible';
                
               return response()->json($this->response);
            }
        } catch (Exception $error) {
            $this->response['status']  = $this->errorStatus;
            $this->response['statusText'] = 'Server Error';
            $this->response['error'] = true;
            $this->response['msg'] = $error->getMessage();
            return response()->json($this->response);
        }
        //******************** Add Data to Db Section Start End ********************/
    }
}
