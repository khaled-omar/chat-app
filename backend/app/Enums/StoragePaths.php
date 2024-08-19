<?php

namespace App\Enums;

enum StoragePaths: string
{
    case LOGOS = 'logos';
    case USERS = 'users';
    case CERTIFICATES = 'certificates';
    case DOCUMENTS = 'documents';
    case ATTACHMENTS = 'attachments';
    case PRODUCTS = 'products';
}
