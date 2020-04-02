<?php
namespace App\Responses;

class SuccessResponse
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(): \Illuminate\Http\JsonResponse
    {
        if (!isset($this->data)) {
            $this->data='Address processing in background ';
        }
        return response()->json([
            'success' => true,
            'data' => $this->data,
            'message' => 'Request was successful'
        ], 200);
    }
}
