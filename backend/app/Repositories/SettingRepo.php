<?php

namespace App\Repositories;

use App\Enums\SettingKey;
use App\Models\Setting;

class SettingRepo extends AbstractEntityRepo
{
    public function __construct()
    {
        $this->model = new Setting();
        parent::__construct();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|mixed
     */
    protected function createEntity($model, $data)
    {
        return $model;
    }

    protected function updateEntity($entity, $data)
    {
        return $entity;
    }

    public function getByKey(SettingKey $key, ?string $locale = 'en')
    {
        return $this->model->where('key', $key->value)->first()->lang($locale)->value;
    }
}
