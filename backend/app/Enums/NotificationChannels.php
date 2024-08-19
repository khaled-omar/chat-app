<?php

namespace App\Enums;

enum NotificationChannels: string
{
    case SMS = 'sms';
    case EMAIL = 'email';
}
