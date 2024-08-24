<?php

namespace App\Enums;

enum SettingKey: string
{
    case PRIVACY_POLICY = 'privacy_policy';
    case TERMS_AND_CONDITIONS = 'terms_and_conditions';
    case CONTACT_US = 'contact_us';
    case ADDRESS = 'address';
    case DEMO_VIDEO_URL = 'demo_video_url';
    case SOCIAL_LINKS = 'social_links';
    case INVITATION_EXPIRE_ON = 'invitation_expire_on';

    case NUMBER_OF_DAYS_BEFORE_CERTIFICATE_EXPIRATION_REMINDER = 'number_of_days_before_certificate_expiration_reminder';
}
