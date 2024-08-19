<?php

use Illuminate\Support\Str;

if (! function_exists('repo')) {
    /**
     * Get repo by model
     *
     * @throws \Exception
     */
    function repo($model): App\Repositories\Contracts\RepositoryInterface
    {
        $repoName = 'App\\Repositories\\'.current(array_reverse(explode('\\', $model))).'Repo';
        if (class_exists($repoName)) {
            return new $repoName;
        }

        throw new Exception("Class {$repoName} doesn't exist");
    }
}

if (! function_exists('isProductionEnv')) {
    /**
     * Is production environment
     *
     * @return bool
     */
    function isProductionEnv()
    {
        return (bool) in_array(config('app.env'), ['prod', 'production']);
    }
}

if (! function_exists('getModelName')) {
    /**
     * Get Model name of specific object
     *
     * @param  \Illuminate\Database\Eloquent\Model|string  $modelObject
     * @return string
     */
    function getModelName($modelObject)
    {
        $className = is_string($modelObject) ? $modelObject : get_class($modelObject);
        $appModelPath = config('repo.appModelsPath');

        return Str::kebab(str_replace($appModelPath, '', $className));
    }
}

if (! function_exists('getTranslatedLookup')) {
    /**
     * Get translated name for the lookup by code.
     *
     * @param  string  $identifierName
     * @return string
     */
    function getTranslatedLookup($configName, $identifierValue, $identifierName = 'code')
    {
        if (! $identifierValue) {
            return null;
        }
        $key = array_key_first(collect($configName)->where($identifierName, $identifierValue)->toArray());

        return __($key);
    }
}

if (! function_exists('getTranslatedValues')) {
    /**
     * Get translated config array values for the lookup by code.
     *
     * @param  string  $key
     * @return array
     */
    function getTranslatedValues(array $configArray, $key = 'name')
    {
        if (empty($configArray)) {
            return [];
        }

        return collect($configArray)->map(function ($configItem) use ($key) {
            if (is_object($configItem)) {
                $configItem->$key = __($configItem->$key);

                return $configItem;
            }

            $configItem[$key] = __($configItem[$key]);

            return $configItem;
        })->toArray();
    }
}
