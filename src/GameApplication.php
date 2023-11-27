<?php

namespace App;

use App\Builder\CharacterBuilder;
use App\Builder\CharacterBuilderFactory;
use App\Character\Character;
use App\Event\FightStartingEvent;
use App\Observer\GameObserverInterface;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GameApplication
{
    /** @var GameObserverInterface[] */
    private array $observers = [];
    /*
     * FACTORY pattern to BUILD characters
     * and
     * PUBLISHER to let us know when battle has begun
     */
    public function __construct(
        private CharacterBuilderFactory $characterBuilderFactory,
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    public function play(Character $player, Character $ai): FightResult
    {
        /*
         * PUBLISHER/SUBSCRIBER pattern dispatcher to notify about starting fight
         */
        $this->eventDispatcher->dispatch(new FightStartingEvent($player, $ai));
        $player->rest();
        $fightResult = new FightResult();

        while (true) {
            $fightResult->addRound();
            $damage = $player->attack();
            if ($damage === 0) {
                $fightResult->addExhaustedTurn();
            }

            $damageDealt = $ai->receiveAttack($damage);
            $fightResult->addDamageDealt($damageDealt);
            if ($this->didPlayerDie($ai)) {
                return $this->finishFightResult($fightResult, $player, $ai);
            }

            $damageReceived = $player->receiveAttack($ai->attack());
            $fightResult->addDamageReceived($damageReceived);
            if ($this->didPlayerDie($player)) {
                return $this->finishFightResult($fightResult, $ai, $player);
            }
        }
    }

    private function didPlayerDie(Character $player): bool
    {
        return $player->getCurrentHealth() <= 0;
    }

    private function finishFightResult(FightResult $fightResult, Character $winner, Character $loser): FightResult
    {
        $fightResult->setWinner($winner);
        $fightResult->setLoser($loser);

        $this->notify($fightResult);

        return $fightResult;
    }

    public function createCharacter(string $character): Character
    {
        /*
         * Characters building with simple FACTORY not abstract
         * BUILDER follows Single Responsibility principal and helps to keep code cleaner and more maintainable
         */
        return match (strtolower($character)) {
            'fighter' => $this->createCharacterBuilder()
                ->setMaxHealth(90)
                ->setBaseDamage(12)
                ->setAttackType('sword', 'axe')
                ->setArmorType('shield')
                ->buildCharacter(),
            'archer' => $this->createCharacterBuilder()
                ->setMaxHealth(80)
                ->setBaseDamage(10)
                ->setAttackType('bow', 'dagger')
                ->setArmorType('leather_armor')
                ->buildCharacter(),
            'mage' => $this->createCharacterBuilder()
                ->setMaxHealth(70)
                ->setBaseDamage(8)
                ->setAttackType('fire_bolt', 'ice_bolt')
                ->setArmorType('elusion')
                ->buildCharacter(),
            default => throw new RuntimeException('Undefined Character')
        };
    }

    private function createCharacterBuilder(): CharacterBuilder
    {
        return $this->characterBuilderFactory->createBuilder();
    }

    public function getCharactersList(): array
    {
        return [
            'archer',
            'fighter',
            'mage'
        ];
    }

    /*
     * OBSERVER subscribed to let us know when battle has done
     */
    public function subscribe(GameObserverInterface $observer): void
    {
        if (!in_array($observer, $this->observers, true)) {
            $this->observers[] = $observer;
        }
    }

    public function unsubscribe(GameObserverInterface $observer): void
    {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    /*
     * OBSERVER helps Single Responsibility, Open/Closed and Dependency Inversion
     */
    private function notify(FightResult $fightResult): void
    {
        foreach ($this->observers as $observer) {
            $observer->onFightFinished($fightResult);
        }
    }
}
