<?php

namespace App\Common;

class Response
{
    public function success($data = null, $messageKey = 'Success')
    {
        return response()->json([
            'status' => true,
            'message' => (new ResponseMessage($messageKey))->get(),
            'data' => $data,
        ], 200);
    }

    public function validationError($messageKey = 'Error', $data = null)
    {
        return response()->json([
            'status' => false,
            'message' => (new ResponseMessage($messageKey))->get(),
            'errors' => $data->errors(),
        ], 422);
    }

    public function exception($messageKey = 'Exception', $statusCode = 401, $errorCode = null)
    {
        return response()->json([
            'status' => false,
            'message' => (new ResponseMessage($messageKey))->get(),
            'code' => $errorCode
        ], $statusCode);
    }

    public function error($messageKey = 'Error', $data = null)
    {
        return response()->json([
            'status' => false,
            'message' => (new ResponseMessage($messageKey))->get(),
            'data' => $data,
        ], 400);
    }
}
