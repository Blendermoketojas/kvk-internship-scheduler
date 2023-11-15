<?php
enum Role
{
    case PRODEKANAS;
    case STUDENTAS;
    case PRAKTIKOS_VADOVAS;
    case MENTORIUS;
    case KADEDROS_VEDEJAS;

    public function role(): string
    {
        return match($this)
        {
            Role::PRODEKANAS => 1,
            Role::KADEDROS_VEDEJAS => 2,
            Role::PRAKTIKOS_VADOVAS => 3,
            Role::MENTORIUS => 4,
            Role::STUDENTAS => 5,
        };
    }
}
