<?php

namespace App\ArmorType;

class ShieldType implements ArmorType
{
    public function getArmorReduction(int $damage): int
    {
        $chanceToBlock = rand(1, 100);

        return $chanceToBlock > 80 ? floor($damage * rand(1, 25) / 100) : $damage;
    }
}
