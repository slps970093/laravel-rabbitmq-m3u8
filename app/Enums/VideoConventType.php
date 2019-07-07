<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class VideoConventType extends Enum
{
    const UPLOAD_STATUS = 0;
    const UPLOAD_CONVENT_M3U8 = 1;
    const UPLOAD_CONVENT_STATUS = 2;
}
