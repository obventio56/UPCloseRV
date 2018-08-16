<?php

namespace App\Exceptions;

//these are included by default
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Pixelandhammer\ExceptionLogger\ExceptionLoggerFacade as ExceptionLogger;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
      
      /*
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
          app('sentry')->captureException($exception);
        }
      */

        ExceptionLogger::log($exception);  

      
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
           // handle 403 errors
        if ($exception instanceof HttpException && $exception->getStatusCode()==403) {

            echo 'Boo.';

        }
        return parent::render($request, $exception);
    }
}
