<?php

namespace App\Helpers\Cookie;

use Illuminate\Support\Facades\Cookie;
class CookieHelper {
    public function getConfiguredCookie($token)
    {
        return Cookie::make(
            'jwt',
            $token,
            60 * 24 * 7,
            '/',
            null,
            true,
            true,
            false,
            'None'
        );
    }
}
