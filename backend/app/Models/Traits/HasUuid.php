<?php

namespace App\Models\Traits;

use Exception;
use Ramsey\Uuid\Uuid as RamseyUuid;

trait HasUuid
{
    /**
     * Indicates if the IDs are UUIDs.
     */
    protected function keyIsUuid(): bool
    {
        return true;
    }

    /**
     * The UUID version to use.
     */
    protected function uuidVersion(): int
    {
        return 4;
    }

    protected static function boot()
    {
        parent::boot();

        $creationCallback = function ($model) {
            if ($model->keyIsUuid() && empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = $model->generateUuid();
            }
        };

        static::creating($creationCallback);
    }

    /**
     * @throws \Exception
     */
    protected function generateUuid(): string
    {
        switch ($this->uuidVersion()) {
            case 1:
                return RamseyUuid::uuid1()->toString();
            case 4:
                return RamseyUuid::uuid4()->toString();
        }

        throw new Exception("UUID version [{$this->uuidVersion()}] not supported.");
    }
}
