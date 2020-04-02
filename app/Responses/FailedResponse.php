<?php

namespace App\Responses;

class FailedResponse
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => [],
            'message' =>  $this->message
        ], 500);
    }
}
