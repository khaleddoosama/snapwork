<?php

namespace App\Exceptions;

use App\Http\Controllers\Api\ApiResponseTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Yoeunes\Toastr\Facades\Toastr;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;
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
            Log::error($e->getMessage());
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson() || $request->is('api/*')) {  // Check if the request is for API
            return $this->handleApiException($request, $exception);
        } else {
            return $this->handleWebException($request, $exception);
        }
    }


    protected function handleApiException($request, Throwable $exception)
    {
        // Map exception types to HTTP status codes
        $exceptionTypeToStatus = [
            ModelNotFoundException::class => 404,
            NotFoundResourceException::class => 404,
            NotFoundHttpException::class => 404,
            AuthorizationException::class => 403,
            ValidationException::class => 422,
            AuthenticationException::class => 401
        ];

        foreach ($exceptionTypeToStatus as $type => $status) {
            if ($exception instanceof $type) {
                return $this->apiResponse(null, $exception->getMessage(), $status);
            }
        }

        // Default response for other types of exceptions
        return $this->apiResponse(null, $exception->getMessage(), $exception->getCode() ?: 500);
    }

    protected function handleWebException($request, Throwable $exception)
    {
        // Handle web exceptions, using Toastr for flash messages
        if ($exception instanceof ValidationException) {
            Toastr::error($exception->getMessage(), __('status.error_in_inputs'));
        } elseif ($exception instanceof AuthorizationException) {
            Toastr::error($exception->getMessage(), __('status.error_in_permissions'));
        } elseif ($exception instanceof AuthenticationException) {
            Toastr::error($exception->getMessage(), __('status.error_in_register'));
        } elseif ($exception instanceof PDOException) {
            Toastr::error($exception->getMessage(), __('status.database_error'));
            return redirect()->back();
        } else {
            Toastr::error($exception->getMessage(),  __('status.error'));
        }
        return parent::render($request, $exception);
    }
}
