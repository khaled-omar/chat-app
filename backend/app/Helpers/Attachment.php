<?php

namespace App\Helpers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Class Attachment
 */
class Attachment
{
    /**
     * Generate attachment secure url
     */
    public static function generateUrl(int $eloquentModelId, string $attachableFieldName, string $modelName): string
    {
        $route = 'api.attachments.get-file';

        $token = hash_hmac('sha256', Str::random(20), config('app.key'));
        $expireIn = now()->addMinutes(5);
        $param = ['id' => $eloquentModelId, 'token' => $token, 'field' => $attachableFieldName, 'model' => $modelName];

        return url(URL::temporarySignedRoute($route, $expireIn, $param, false));
    }

    /**
     * Get attachment file response
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function getFile(int $eloquentModelId, string $attachableFieldName, string $modelName)
    {
        /* @var \App\Models\Model $modelInstance */

        $modelInstance = app()->make(config('repo.appModelsPath').$modelName);
        $path = $modelInstance::findOrFail($eloquentModelId)?->$attachableFieldName;

        return response()->file(storage_path("app/{$path}"));
    }
}
