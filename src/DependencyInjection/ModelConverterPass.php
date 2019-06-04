<?php

namespace App\DependencyInjection;

use App\ViewModel\Converter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ModelConverterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(Converter::class)) {
            return;
        }

        $definition = $container->findDefinition(Converter::class);

        $taggedServices = $container->findTaggedServiceIds('app.converter');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addConverter', [new Reference($id)]);
        }
    }
}
