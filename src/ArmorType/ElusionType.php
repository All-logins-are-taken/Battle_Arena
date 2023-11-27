<?php

namespace App\ArmorType;

class ElusionType implements ArmorType
{
    public function getArmorReduction(int $damage): int
    {
        $chanceToPerplex = rand(1, 100);

        return $chanceToPerplex > 30 ? floor($damage * (rand(10, 99) / 100)) : $damage;
    }
}
