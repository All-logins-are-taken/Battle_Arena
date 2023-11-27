<?php

namespace App\AttackType;

class FireBoltType implements AttackType
{
    public function performAttack(int $baseDamage): int
    {
        $burn = rand(1, 100);

        return $burn > 90 ? $baseDamage * 6 : $baseDamage + rand(3, 30);
    }
}
