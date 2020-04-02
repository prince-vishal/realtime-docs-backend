<?php
namespace App\Responses;

class SavedOrDeletedResponse
{
    public $resourceName;
    public $isSaved;
    public $isDeleted;
    public $data;

    public function __construct($resourceName, bool $isSaved = false, bool $isDeleted = false, $data = null)
    {
        $this->resourceName = $resourceName;
        $this->isDeleted = $isDeleted;
        $this->isSaved = $isSaved;
        $this->data = $data;
    }

    /**
     * Send saved or deleted response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(): \Illuminate\Http\JsonResponse
    {
        $message = $this->resourceName . ' was successfully saved';
        $code = 201;
        if ($this->isDeleted) {
            $message = $this->resourceName . ' was successfully deleted';
            $code = 200;
        }

        $body = [
            'success' => true,
            'message' => $message
        ];

        if ($this->data) {
            $body['data'] = $this->data;
        }
        return response()->json($body, $code);
    }
}
