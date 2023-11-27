<?php

namespace App\AttackType;

class DaggerType implements AttackType
{
    public function performAttack(int $baseDamage): int
    {
        return $baseDamage + rand(1, 50);
    }
}
