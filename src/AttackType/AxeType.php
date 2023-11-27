<?php

namespace App\AttackType;

class AxeType implements AttackType
{
    public function performAttack(int $baseDamage): int
    {
        return $baseDamage + rand(30, 40);
    }
}
