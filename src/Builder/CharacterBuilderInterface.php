<?php

namespace App\Builder;

use App\Character\Character;

interface CharacterBuilderInterface
{
    public function setMaxHealth(int $maxHealth): self;

    public function setBaseDamage(int $baseDamage): self;

    public function setAttackType(string ...$attackTypes): self;

    public function setArmorType(string $armorType): self;

    public function buildCharacter(): Character;
}
