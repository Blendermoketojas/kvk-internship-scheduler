<?php

namespace App\Contracts\Roles;
enum Role: int
{
    case SELF = 0;
    case PRODEKANAS = 1;
    case KATEDROS_VEDEJAS = 2;
    case PRAKTIKOS_VADOVAS = 3;
    case MENTORIUS = 4;
    case STUDENTAS = 5;

    public function equalsValue(int $value): bool {
        return $this->value === $value;
    }
}
