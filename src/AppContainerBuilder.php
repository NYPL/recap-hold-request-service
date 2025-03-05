<?php

namespace NYPL\Services;

use Aura\Di\ContainerBuilder;
use Aura\Di\Injection\InjectionFactory;

/**
 * Class AppContainer
 *
 * @package NYPL\OAuthApp
 */
class AppContainerBuilder extends ContainerBuilder
{
    /**
     *
     * Returns a new AppContainer instance.
     *
     * @param bool $autoResolve Use the auto-resolver?
     *
     * @return AppContainer
     *
     */
    public function newInstance(bool $autoResolve = false): AppContainer
    {
        $resolver = $this->newResolver($autoResolve);
        return new AppContainer(new InjectionFactory($resolver));
    }
}
