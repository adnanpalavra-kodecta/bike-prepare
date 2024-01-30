<?php

namespace App\Models\Enums;

enum ProductState: string {
    case DRAFT = 'DRAFT';
    case ACTIVE = 'ACTIVE';
    case DELETED = 'DELETED';
}
