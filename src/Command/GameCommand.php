<?php

namespace App\Command;

use App\Character\Character;
use App\FightResult;
use App\GameApplication;
use App\Observer\XpEarnedObserver;
use App\Service\OutputtingXpCalculator;
use App\Service\XpCalculator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
#[AsCommand('app:game:play')]
class GameCommand extends Command
{
    public function __construct(
        private readonly GameApplication $game
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $xpCalculator = new XpCalculator();
        $xpCalculator = new OutputtingXpCalculator($xpCalculator);
        $this->game->subscribe(new XpEarnedObserver($xpCalculator));

        $io = new SymfonyStyle($input, $output);

        $outputStyle = new OutputFormatterStyle('red', '#ff0');
        $output->getFormatter()->setStyle('title', $outputStyle);
        $output->writeln('<title>Welcome to battle arena!</>');

        $characters = $this->game->getCharactersList();
        $characterChoice = $io->choice('Choose your destiny', $characters);
        $playerCharacter = $this->game->createCharacter($characterChoice);
        $playerCharacter->setTitle('You choose ' . $characterChoice);
        $this->play($io, $playerCharacter);

        return Command::SUCCESS;
    }

    private function play(SymfonyStyle $io, Character $player): void
    {
        do {
            $aiCharacter = $this->selectAiCharacter();
            $fightResult = $this->game->play($player, $aiCharacter);
            $this->printResult($fightResult, $player, $io);
            $answer = $io->choice('<bg=green;fg=blue>Next round?</>', [
                1 => 'Yes',
                2 => 'No',
            ]);
        } while ($answer === 'Yes');
    }

    private function selectAiCharacter(): Character
    {
        $characters = $this->game->getCharactersList();
        $aiCharacterString = $characters[array_rand($characters)];
        $aiCharacter = $this->game->createCharacter($aiCharacterString);
        $aiCharacter->setTitle(ucfirst($aiCharacterString));
        $aiCharacter->setLevel(rand(1, 10));

        return $aiCharacter;
    }

    private function printResult(FightResult $fightResult, Character $player, SymfonyStyle $io): void
    {
        $io->writeln('↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓');
        if ($fightResult->getWinner() === $player) {
            $io->writeln('<bg=green;fg=black>You are victorious!</>');
        } else {
            $io->writeln('<bg=red;fg=black;options=bold>Defeated!</>');
        }

        $io->writeln('Total Rounds: ' . $fightResult->getRounds());
        $io->writeln('Damage dealt: ' . $fightResult->getDamageDealt());
        $io->writeln('Damage received: ' . $fightResult->getDamageReceived());
        $io->writeln('Exhausted Turns: ' . $fightResult->getExhaustedTurns());
        $io->writeln('XP: ' . $player->getXp());
        $io->writeln('Final Level: ' . $player->getLevel());
        $io->writeln('↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑');
    }
}
