<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Global scopes path
    |--------------------------------------------------------------------------
    |
    | This value is the path of global scopes. This value is used when the
    | repo register Global Scope to Query Builder.
    |
    */

    'appScopesPath' => 'App\\Models\\Scopes\\',

    /*
    |--------------------------------------------------------------------------
    | Model path
    |--------------------------------------------------------------------------
    |
    | This value is the path of models path. This value is used when get Model
    | Object by name
    |
    */

    'appModelsPath' => 'App\\Models\\',

    /*
    |--------------------------------------------------------------------------
    | Models that use morph relation
    |--------------------------------------------------------------------------
    |
    | This value is array of models that use morph relation. This value is used
    | when delete operation from repo to ensure that all dependencies are deleted.
    |
    */

    'morph_classes' => [],

    /*
    |--------------------------------------------------------------------------
    | Filter method name for field
    |--------------------------------------------------------------------------
    |
    | This value is filter method name for field. This value is used get method
    | name for field filter.
    |
    */

    'filter_method_name' => 'Filter',

    /*
    |--------------------------------------------------------------------------
    | Global sorting by `created_at`
    |--------------------------------------------------------------------------
    |
    | This value is Global sorting by `created_at`. This value is used enable
    | global sorting by `created_at`.
    |
    */
    'global_sort' => true,

    /*
    |--------------------------------------------------------------------------
    | Translation Configuration
    |--------------------------------------------------------------------------
    |
    | This value is Global sorting by `created_at`. This value is used enable
    | global sorting by `created_at`.
    |
    */
    'translation' => [
        /**
         * Relation specify localization name of translation in \App\Models\Model.
         */
        'relation' => 'language',

        /**
         * Relation specify localization name of translation in \App\Models\Model.
         */
        'localizations_relation' => 'languages',

        /**
         * Key name of translation model.
         */
        'model_key_name' => 'Lang',

        /**
         * Local field key.
         */
        'local_field_key' => 'lang_code',
    ],

];
