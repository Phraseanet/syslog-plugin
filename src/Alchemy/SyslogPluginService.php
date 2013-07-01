<?php

/*
 * This file is part of Phraseanet syslog plugin
 *
 * (c) 2005-2013 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy;

use Alchemy\Phrasea\Application as PhraseaApplication;
use Alchemy\Phrasea\Plugin\PluginProviderInterface;
use Monolog\Logger;
use Monolog\Handler\SyslogHandler;
use Silex\Application;

class SyslogPluginService implements PluginProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['syslog-plugin.logger'] = $app->share(function (Application $app) {
            $syslogLevel = Logger::DEBUG;

            if (isset($app['phraseanet.configuration']['plugins']['syslog-plugin'])) {
                $options = $app['phraseanet.configuration']['plugins']['syslog-plugin'];
                if (isset($options['level']) && null !== constant($options['level'])) {
                    $syslogLevel = constant($options['level']);
                }
            }

            return new SyslogHandler("Phraseanet-TaskManager", "user", $syslogLevel);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        $app['task-manager.logger'] = $app->share(
            $app->extend('task-manager.logger', function($logger, $app) {
                $logger->pushHandler($app['syslog-plugin.logger']);

                return $logger;
            })
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function create(PhraseaApplication $app)
    {
        return new static();
    }
}
