<?php

namespace App\DependencyInjection\Compiler;

use App\Service\OAuth2;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideOAuthServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('fos_oauth_server.server');
        $definition->setClass(OAuth2::class);
    }
}
