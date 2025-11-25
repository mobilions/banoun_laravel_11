<?php



namespace App\Exceptions;



use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;



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

     * @param  \Throwable  $exception

     * @return void

     *

     * @throws \Exception

     */

    public function report(Throwable $exception)

    {

        parent::report($exception);

    }



    /**

     * Render an exception into an HTTP response.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \Throwable  $exception

     * @return \Symfony\Component\HttpFoundation\Response

     *

     * @throws \Throwable

     */

    public function render($request, Throwable $exception)

    {
        if ($request->expectsJson() || $request->is('api/*')) {
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $exception->errors(),
                ], 422);
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                ], 404);
            }

            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            if ($exception instanceof HttpException) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage() ?: 'Request failed',
                ], $exception->getStatusCode());
            }

            Log::error('API Error', [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'trace' => config('app.debug') ? $exception->getTraceAsString() : null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
            ], 500);
        }

        return parent::render($request, $exception);

    }

}

