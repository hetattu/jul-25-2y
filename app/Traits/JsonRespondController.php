<?php

namespace App\Traits;

trait JsonRespondController
{
    protected $httpStatusCode = 200;
    protected $errorCode;

    public function getHTTPStatusCode()
    {
        return $this->httpStatusCode;
    }

    public function setHTTPStatusCode($statusCode)
    {
        $this->httpStatusCode = $statusCode;

        return $this;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Sends a JSON to the consumer.
     *
     * @param  array $data
     * @param  array  $headers [description]
     * @return JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getHTTPStatusCode(), $headers);
    }

    /**
     * Sends a response with error.
     *
     * @param string|array $message
     * @return JsonResponse
     */
    public function respondWithError($message = null)
    {
        return $this->respond([
            'error' => [
                'message' => $message ?? config('api.error_codes.'.$this->getErrorCode()),
                'error_code' => $this->getErrorCode(),
            ],
        ]);
    }

    public function respondInvalidQuery($message = null)
    {
        return $this->setHTTPStatusCode(500)
                    ->setErrorCode(40)
                    ->respondWithError($message);
    }

    public function respondNotFound()
    {
        return $this->setHTTPStatusCode(404)
                    ->setErrorCode(31)
                    ->respondWithError();
    }

    public function respondObjectDeleted($id)
    {
        return $this->respond([
            'deleted' => true,
            'id' => $id,
        ]);
    }
}
