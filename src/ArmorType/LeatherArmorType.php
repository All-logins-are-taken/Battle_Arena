<?php

namespace App\ArmorType;

class LeatherArmorType implements ArmorType
{
    public function getArmorReduction(int $damage): int
    {
        return floor($damage * (rand(16, 33) / 100));
    }
}
