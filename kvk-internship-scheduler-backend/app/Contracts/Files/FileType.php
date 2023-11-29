<?php

namespace App\Contracts\Files;
enum FileType: string
{
    case Internship = "Internship";
    case LearningMaterials = "LearningMaterials";

    public function equalsValue(string $value): bool {
        return $this->value === $value;
    }
}
