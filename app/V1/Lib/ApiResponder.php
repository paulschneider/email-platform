<?php Namespace App\V1\Lib;

use \Illuminate\Http\Request AS Request;

Class ApiResponder {

    /**
     * [$request description]
     * @var [type]
     */
    protected $request;

    /**
     * [__construct description]
     * @param Illuminate\Http\Request $request [description]
     */
    public function __construct()
    {
        $this->request = New Request();
    }

    /**
     * get the Api response code for this request
     *
     * @return statusCode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * set the Api response code for this request
     *
     * @return null
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * get the message for the current request
     *
     * @return message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * set the message for the current request
     *
     * @return null
     */
    public function setMessage($overrideMessage, $defaultMessage)
    {
        if( ! is_null($overrideMessage) )
        {
            $this->message = $overrideMessage;
        }
        else
        {
            $this->message = $defaultMessage;
        }
    }

    /**
     * send the response back to the API client
     *
     * @return Response
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * return an error to the API client
     *
     * @return Response
     */
    public function respondWithError($message, $data = [])
    {   
        $response = [
            'error' => [
                'message' => $message,
                'statusCode' => $this->getStatusCode(),
                'method' => $this->request->method(),
                'endpoint' => $_SERVER['REQUEST_URI'],
                'time' => time(),
            ],
            'data' => $data
        ];

        return $this->respond($response);
    }

    /**
     * return an error to the API client
     *
     * @return Response
     */
    public function respondWithSuccess($message, $data = null)
    {
        $response = $this->respond([
            'success' => [
                'message' => $message
                ,'statusCode' => $this->getStatusCode()
                ,'method' => $this->request->method()
                ,'endpoint' => $_SERVER['REQUEST_URI']
                ,'time' => time()
                ,'data' => $data
            ]
        ]);

        return $response;
    }
}   