<?php

/*
 * This file is part of the Itkg\Core package.
 *
 * (c) Interakting - Business & Decision
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Itkg\Core\Provider;

use Itkg\Core\Listener\AjaxRenderResponseListener;
use Itkg\Core\Listener\RequestMatcherListener;
use Itkg\Core\Listener\ResponseExceptionListener;
use Itkg\Core\Listener\ResponsePostRendererListener;
use Itkg\Core\Matcher\RequestMatcher;
use Itkg\Core\Response\Processor\CompressProcessor;
use Itkg\Core\Route\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Pimple $mainContainer An Container instance
     */
    public function register(\Pimple $mainContainer)
    {
        $container = new \Pimple();

        $container['dispatcher'] = $mainContainer->share(function () {
            $dispatcher = new EventDispatcher();
            // Add listeners

            return $dispatcher;
        });

        $mainContainer['core'] = $container;
    }
}
