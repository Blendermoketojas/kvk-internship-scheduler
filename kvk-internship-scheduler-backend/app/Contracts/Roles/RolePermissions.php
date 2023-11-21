<?php

namespace App\Contracts\Roles;
enum RolePermissions: int
{
    case PRODEKANAS = 1;
    case KATEDROS_VEDEJAS = 2;
    case PRAKTIKOS_VADOVAS = 3;
    case MENTORIUS = 4;
    case STUDENTAS = 5;

    public function equalsValue(int $value): bool {
        return $this->value === $value;
    }
}
