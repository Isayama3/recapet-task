<?php
namespace App\Base\Traits\Custom;

use Illuminate\Http\Exceptions\HttpResponseException;

trait HttpExceptionTrait
{
    public function throwHttpExceptionForWebAndApi($message, $statusCode = 400 , $redirect = null)
    {
        if (request()->wantsJson()) {
            throw new HttpResponseException($this->setStatusCode($statusCode)->respondWithError($message));
        }

        if ($redirect) {
            return redirect($redirect)->with('error', $message);
        }

        return;
    }
}
