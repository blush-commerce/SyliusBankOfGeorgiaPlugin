<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Enum;

enum Intent: string
{
    case CAPTURE   = 'CAPTURE';
    case AUTHORIZE = 'AUTHORIZE';
}
