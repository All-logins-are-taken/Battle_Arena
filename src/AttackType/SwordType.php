<?php

namespace App\AttackType;

class SwordType implements AttackType
{
    public function performAttack(int $baseDamage): int
    {
        $slash = rand(1, 100);

        return $slash > 70 ? $baseDamage * 3 : $baseDamage + rand(2, 24);
    }
}
