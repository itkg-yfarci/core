<?php

namespace Itkg\Core\Command\Provider;

use Itkg\Core\Command\DatabaseUpdate;
use Itkg\Core\Command\DatabaseUpdate\Loader;
use Itkg\Core\Command\DatabaseUpdate\Locator;
use Itkg\Core\Command\DatabaseUpdate\Migration\Factory;
use Itkg\Core\Command\DatabaseUpdate\Query\OutputQueryFactory;
use Itkg\Core\Command\DatabaseUpdate\Query\Formatter;
use Itkg\Core\Command\DatabaseUpdate\Runner;
use Itkg\Core\Command\DatabaseUpdate\Setup;
use Itkg\Core\Command\DatabaseUpdate\Template\Loader as TemplateLoader;
use Itkg\Core\Command\DatabaseUpdateCommand;
use Itkg\Core\Provider\ServiceProviderInterface;

/**
 * Class ServiceCommandProvider
 *
 * A provider for db_update command injection
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ServiceCommandProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param \Pimple $container An Container instance
     */
    public function register(\Pimple $container)
    {
        $container['itkg-core.command.db_update.runner'] = $container->share(
            function ($container) {
                return new Runner($container['doctrine.connection']);
            }
        );

        $container['itkg-core.command.db_update.decorator'] = $container->share(
            function ($container) {
                return new DatabaseUpdate\Query\Decorator(
                    new TemplateLoader(),
                    new DatabaseUpdate\Query\Parser()
                );
            }
        );

        $container['itkg-core.command.db_update.setup'] = $container->share(
            function ($container) {
                return new Setup(
                    $container['itkg-core.command.db_update.runner'],
                    new Loader($container['doctrine.connection']),
                    new Factory(),
                    new Locator()
                );
            }
        );

        $container['itkg-core.command.database_update'] = $container->share(
            function ($container) {
                return new DatabaseUpdateCommand(
                    $container['itkg-core.command.db_update.setup'],
                    new OutputQueryFactory(new Formatter()),
                    $container['itkg-core.command.db_update.decorator'],
                    'itkg-core:database:update'
                );
            }
        );
    }
}
