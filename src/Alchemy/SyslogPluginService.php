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
        $app['syslog-plugin.configuration'] = $app->share(function (Application $app) {
            $conf = array();

            if (isset($app['phraseanet.configuration']['plugins']['syslog-plugin'])) {
                $conf = $app['phraseanet.configuration']['plugins']['syslog-plugin'];
            }

            return array_replace(array(
                'level'      => Logger::DEBUG,
                'channels'   => $app['log.channels'],
            ), $conf);
        });

        $app['syslog-plugin.handler'] = $app->share(function (Application $app) {
            $level = $app['syslog-plugin.configuration']['level'];

            if (defined($level)) {
                $level = constant($level);
            }

            return new SyslogHandler("Phraseanet-TaskManager", "user", $level);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        foreach ((array) $app['syslog-plugin.configuration']['channels'] as $channel) {
            $app[$channel] = $app->share(
                $app->extend($channel, function($logger, $app) {
                    $logger->pushHandler($app['syslog-plugin.handler']);

                    return $logger;
                })
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function create(PhraseaApplication $app)
    {
        return new static();
    }
}
