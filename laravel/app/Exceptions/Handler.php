<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('spn/admin/*') || $request->is('spn/admin')) {
                return response()->view('errors.admin.404', [], 404);
            }

            return response()->view('errors.client.404', [], 404);
        });

        $this->renderable(function (TooManyRequestsHttpException $e, $request) {
            if ($request->expectsJson()) {
                return null;
            }

            if ($request->is('spn/admin/*') || $request->is('spn/admin')) {
                return response()->view('errors.admin.429', [], 429);
            }

            return response()->view('errors.client.429', [], 429);
        });

        // $this->renderable(function (Throwable $e, $request) {
        //     if (config('app.debug')) {
        //         return null;
        //     }

        //     if ($e instanceof NotFoundHttpException) {
        //         return null;
        //     }

        //     return response()->view('errors.client.500', [], 500);
        // });
    }
}
