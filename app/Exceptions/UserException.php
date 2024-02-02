<?php

namespace App\Exceptions;

use Exception;
class UserException extends Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * This method is called when the exception needs to be
     * converted to an HTTP response to be sent back to the client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        // You can customize the response format here
        return response()->json(['error' => $this->getMessage()], 400);
    }
}
