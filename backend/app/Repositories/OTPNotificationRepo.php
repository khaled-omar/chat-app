<?php

namespace App\Repositories;

use App\Enums\OTPModels;
use App\Models\OTPNotification;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OTPNotificationRepo
 */
class OTPNotificationRepo extends AbstractRepository
{
    /**
     * OTPNotificationRepo constructor.
     */
    public function __construct()
    {
        $this->model = new OTPNotification();
        parent::__construct();
    }

    /**
     * Purge current user requests
     *
     * @return mixed
     */
    public function purgeOldRequests(Model|OTPModels $notifiable, $channel, $address, $module)
    {
        return $this->model->newQuery()
            ->where('notifiable_id', ($notifiable instanceof Model) ? $notifiable->id : null)
            ->where('notifiable_type', ($notifiable instanceof Model) ? get_class($notifiable) : $notifiable->value)
            ->where('channel', $channel)
            ->where('address', $address)
            ->where('module', $module)
            ?->delete();
    }
}
