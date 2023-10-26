<?php

/*
 * This is file is required as long as ImportExportBundle supports Symfony versions lower than 4.1. Before this version, ParameterBag wasn't a service available.
 *
 * See: https://symfony.com/blog/new-in-symfony-4-1-getting-container-parameters-as-a-service
 */

declare(strict_types=1);

namespace LWC\ImportExportBundle\DependencyInjection\ParameterBag;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class ParameterBag extends FrozenParameterBag implements ParameterBagInterface
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return $this->container->getParameterBag()->all();
    }

    /**
     * Gets a parameter.
     *
     * @return array|bool|string|int|float|\UnitEnum|null
     *
     * @throws ParameterNotFoundException if the parameter is not defined
     */
    public function getParameter(string $name)
    {
        return $this->container->getParameter($name);
    }

    /**
     * {@inheritdoc}
     */
    public function has($name): bool
    {
        return $this->container->hasParameter($name);
    }
}