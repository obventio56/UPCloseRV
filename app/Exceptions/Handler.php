<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use GuzzleHttp\Client;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Carbon\Carbon;

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

        //todo - add more data!
      
        $e = FlattenException::create($exception);
        $handler = new SymfonyExceptionHandler();
        $html = $handler->getHtml($e);
        $client = new Client([
          'base_uri' => 'http://workshop.developingpixels.com'
        ]);
        $result = $client->post('/api/exceptionLogger', [
            'form_params' => [
                'app' => env("APP_NAME", "Up-close"),
                'reported_exception' => $html,
                'reported_on' => Carbon::now()->toDateTimeString()
            ]
        ]);            
      
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
