<?php

namespace App\Providers;

use App\Exceptions\ApiException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('api', function ($response = [], $statusCode = 200, $message = 'Done', $moreMeta = []) {
            if ($response instanceof \Throwable) {
                $status = new \stdClass();
                $data = null;
                $status->code = $response->getCode() ?: 500;
                if ($response instanceof NotFoundHttpException || $response instanceof ModelNotFoundException) {
                    $status->code = 404;
                }
                $status->message = $response->getMessage() ?: class_basename($response);
                $status->errors = '';

                if ($response instanceof ApiException) {
                    $status->errors = $response->getErrors();
                } elseif ($response instanceof AuthenticationException) {
                    $status->code = 401;
                }
                // Handle throttle on api routes.
                elseif ($response instanceof HttpResponseException) {
                    $status->code = $response->getResponse()->getStatusCode();
                    $status->message = $response->getResponse()->getContent();
                    $data = [];
                }
            } else {
                $status = new \stdClass();
                $status->code = $statusCode;
                $status->message = $message;
                $data = $response;
            }
            $apiFormat = ['status' => $status, 'data' => $data];
            if ($response instanceof ResourceCollection) {
                $response = $response->toResponse(request())->getData();
                $apiFormat['data'] = $response->data;
                if (isset($response->meta) && isset($response->links)) {
                    $apiFormat['meta'] = $response->meta;
                }
            }
            if (count($moreMeta)) {
                $apiFormat['meta'] = array_merge((array) ($apiFormat['meta'] ?? []), $moreMeta);
            }

            // Append query log in response
            //            if (config('app.debug')) {
            //                $apiFormat['_debugbar'] = app('debugbar')->getData();
            //                if (isset($apiFormat['_debugbar']['queries']['statements'])) {
            //                    $apiFormat['_debugbar']['queries']['statements'] = collect($apiFormat['_debugbar']['queries']['statements'])->pluck('sql') ?? null;
            //                }
            //            }

            return Response::make($apiFormat, $status->code);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
