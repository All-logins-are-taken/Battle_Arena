<?php

namespace App\AttackType;

class BowType implements AttackType
{
    public function performAttack(int $baseDamage): int
    {
        $criticalChance = rand(1, 100);

        return $criticalChance > 70 ? $baseDamage * 3 : $baseDamage + rand(1, 10);
    }
}
