<?php

namespace App\Contracts\Files;
enum FileType: string
{
    case Internships = "Internships";
    case LearningMaterials = "LearningMaterials";

    public function equalsValue(string $value): bool {
        return $this->value === $value;
    }
}
