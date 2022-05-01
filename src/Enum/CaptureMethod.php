<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Enum;

enum CaptureMethod: string
{
    case AUTOMATIC = 'AUTOMATIC';
    case MANUAL    = 'MANUAL';
}
