<?php

namespace App;

use App\Observer\GameObserverInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(GameApplication::class);
        $taggedObservers = $container->findTaggedServiceIds('game.observer');
        foreach ($taggedObservers as $id => $tags) {
            $definition->addMethodCall('subscribe', [new Reference($id)]);
        }
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(GameObserverInterface::class)->addTag('game.observer');
    }
}
