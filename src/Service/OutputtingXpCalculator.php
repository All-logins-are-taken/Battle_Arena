<?php

namespace App\Service;

use App\Character\Character;
use Symfony\Component\Console\Output\ConsoleOutput;

class OutputtingXpCalculator implements XpCalculatorInterface
{
    public function __construct(
        private readonly XpCalculatorInterface $innerCalculator
    )
    {
    }

    public function addXp(Character $winner, int $enemyLevel): void
    {
        $beforeLevel = $winner->getLevel();
        $this->innerCalculator->addXp($winner, $enemyLevel);
        $afterLevel = $winner->getLevel();
        if ($afterLevel > $beforeLevel) {
            $output = new ConsoleOutput();
            $output->writeln('-----------------------------------------------------');
            $output->writeln('<bg=green;fg=white>Congrats! Fighter got level up!</>');
            $output->writeln(sprintf('Now it\'s "%d"', $winner->getLevel()));
            $output->writeln('-----------------------------------------------------');
        }
    }
}
