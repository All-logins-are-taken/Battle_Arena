<?php

namespace App\Character;

use App\ArmorType\ArmorType;
use App\AttackType\AttackType;

class Character
{
    private const MAX_STAMINA = 100;
    private int $currentStamina = self::MAX_STAMINA;
    private int $currentHealth;
    private string $title = '';
    private int $level = 1;
    private int $xp = 0;

    public function __construct(
        private int $maxHealth,
        private int $baseDamage,
        private readonly AttackType $attackType, /* STRATEGY Attack types */
        private readonly ArmorType $armorType /* STRATEGY Armor types */
    ) {
        $this->currentHealth = $this->maxHealth;
    }

    public function attack(): int
    {
        $this->currentStamina -= (25 + rand(1, 20));
        if ($this->currentStamina <= 0) {
            $this->currentStamina = self::MAX_STAMINA;

            return 0;
        }
        /*
         * To have the random type of attack and not create ugly conditions for each type which can raise up to dozens
         * and not to create subclasses where the attack will be rewritten each time we use here design-pattern STRATEGY
         * In the folder AttackType created interface AttackType to implement in Character
         * This way we can keep Single Responsibility and Open/Closed principals
         * Therefore it allows us to perform MultiAttack using it as array parameter for Character object
         */
        return $this->attackType->performAttack($this->baseDamage);
    }

    public function receiveAttack(int $damage): int
    {
        /*
         * ArmorType carry out in the image and likeness of AttackType
         */
        $armorReduction = $this->armorType->getArmorReduction($damage);
        $damageTaken = max($damage - $armorReduction, 0);
        $this->currentHealth -= $damageTaken;

        return $damageTaken;
    }

    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $nickname): void
    {
        $this->title = $nickname;
    }

    public function rest(): void
    {
        $this->currentHealth = $this->maxHealth;
        $this->currentStamina = self::MAX_STAMINA;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function levelUp(): void
    {
        $bonus = 1.15;
        $this->level++;
        $this->maxHealth = floor($this->maxHealth * $bonus);
        $this->baseDamage = floor($this->baseDamage * $bonus);
    }

    public function addXp(int $xpEarned): int
    {
        $this->xp += $xpEarned;

        return $this->xp;
    }

    public function getXp(): int
    {
        return $this->xp;
    }
}
