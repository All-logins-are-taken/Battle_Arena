<?php

namespace App\AttackType;

class IceBoltType implements AttackType
{
    public function performAttack(int $baseDamage): int
    {
        $maim = rand(1, 100);

        return $maim > 80 ? $baseDamage * 5 : $baseDamage + rand(1, 10);
    }
}
