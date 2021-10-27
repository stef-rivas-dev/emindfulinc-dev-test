<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e)
    {
        $code = 500;

        // dump('debug', $e->getMessage(), $e);

        $classname = get_class($e);
        if ($pos = strrpos($classname, '\\')) {
            $classname = substr($classname, $pos + 1);
        };

        if ($this->isHttpException($e)) {
            return response()->json([
                'error' => $e->getMessage() ?: $classname,
            ], $e->getStatusCode());
        }

        switch ($classname) {
            case 'MethodNotAllowedHttpException':
            case 'NotFoundHttpException':
                $code = 400;
                $message = 'User exception: ' . $e->getMessage();
                break;
            case 'ValidationException':
                $code = 400;
                $authUser = config('app.auth_user_id');
                $message = !isset($authUser) ? 'Unauthenticated' : 'User exception: ' . $e->getMessage();
                break;
            default:
                $message = $e->getMessage();
        }

        return response()->json([
            'error' => $message,
        ], $code);
    }
}
