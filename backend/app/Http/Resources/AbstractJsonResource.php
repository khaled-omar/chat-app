<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AbstractJsonResource
 */
abstract class AbstractJsonResource extends JsonResource
{
    /**
     * Define the date fields
     *
     * @var array
     */
    protected $date = ['created_at', 'updated_at'];

    /**
     * the preferred date format
     *
     * @var string
     */
    protected $date_format;

    /**
     * Collection of mobile app configurations
     *
     * @var \Illuminate\Support\Collection
     */
    protected $appConfigurations;

    /**
     * AbstractJsonResource constructor.
     *
     * @param  mixed  $resource
     */
    public function __construct($resource)
    {
        $this->date_format = config('api.api_date_format');

        parent::__construct($resource);
    }

    /**
     * Load relation resource if loaded
     *
     * @param  $default  | default for object should be null but for collection will be of type array
     * @return object
     *
     * @throws \Exception
     */
    protected function relationLoaded($relationName, $resourceClass, $default = null)
    {
        // Check relation is not loaded to return the default
        if (! $this->resource->relationLoaded($relationName)) {
            return $default;
        }

        $reflectionClass = new \ReflectionClass($resourceClass);

        // Call resource to one object
        if (strtolower(gettype($default)) === 'null') {
            return $reflectionClass->newInstance($this->{$relationName});
        }

        // Call collection resource for multiple value
        if (strtolower(gettype($default)) === 'array') {
            return $reflectionClass->newInstanceWithoutConstructor()->collection($this->{$relationName});
        }

        return $default;
    }

    /**
     * Get current route name
     *
     * @return string
     */
    protected function routeName()
    {
        return request()->route()->getName();
    }
}
