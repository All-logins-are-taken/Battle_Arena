<?php

namespace App\Service;

use App\Character\Character;

class XpCalculator implements XpCalculatorInterface
{
    public function addXp(Character $winner, int $enemyLevel): void
    {
        $xpEarned = $this->calculateXpEarned($winner->getLevel(), $enemyLevel);
        $totalXp = $winner->addXp($xpEarned);
        $xpForNextLvl = $this->getXpForNextLvl($winner->getLevel());
        if ($totalXp >= $xpForNextLvl) {
            $winner->levelUp();
        }
    }

    private function calculateXpEarned(int $winnerLevel, int $loserLevel): int
    {
        $baseXp = 30;
        $rawXp = $baseXp * $loserLevel;
        $levelDiff = $winnerLevel - $loserLevel;

        return match (true) {
            $levelDiff === 0 => $rawXp,
            $levelDiff > 0 => $rawXp - floor($loserLevel * 0.20),
            $levelDiff < 0 => $rawXp + floor($loserLevel * 0.20),
        };
    }

    private function getXpForNextLvl(int $currentLvl): int
    {
        $baseXp = 100;
        $xpNeededForCurrentLvl = $this->fibonacciProgressionFormula($baseXp, $currentLvl);
        $xpNeededForNextLvl = $this->fibonacciProgressionFormula($baseXp, $currentLvl + 1);

        return $xpNeededForCurrentLvl + $xpNeededForNextLvl;
    }

    private function fibonacciProgressionFormula(int $baseXp, int $currentLvl): int
    {
        $currentLvl--;
        if ($currentLvl === 0) {
            return 0;
        }

        return $baseXp * ($currentLvl - 1) + ($baseXp * ($currentLvl));
    }
}
