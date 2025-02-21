<?php

declare(strict_types=1);

namespace App\Common\Helper;

final class HttpHelper
{
    public const int STATUS_OK = 200;
    public const int STATUS_CREATED = 201;
    public const int STATUS_UNAUTHORIZED= 401;
    public const int STATUS_NOT_FOUND= 404;
    public const int STATUS_UNPROCESSABLE = 422;
}
