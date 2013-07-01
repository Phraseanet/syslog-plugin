<?php

namespace Alchemy\Tests;

use Alchemy\Phrasea\Application;
use Alchemy\SyslogPluginService;
use Monolog\Logger;

class SyslogPluginServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testItShouldBeRegistered()
    {
        $app = new Application();
        $app->register(SyslogPluginService::create($app));

        $configuration = $this->getMockBuilder('Alchemy\Phrasea\Core\Configuration\Configuration')
            ->disableOriginalConstructor()
            ->getMock();
        $configuration->expects($this->any())
            ->method('offsetGet')
            ->with('plugins')
            ->will($this->returnValue(array('syslog-plugin' => Logger::DEBUG)));

        $app['phraseanet.configuration'] = $configuration;

        $logger = $this->getMockBuilder('Monolog\\Logger')
            ->disableOriginalConstructor()
            ->getMock();
        $logger->expects($this->once())
            ->method('pushHandler')
            ->with($this->isInstanceOf('Monolog\Handler\SyslogHandler'));

        $app['task-manager.logger'] = $app->share(function () use ($logger) {
            return $logger;
        });

        $app->boot();
        $app['task-manager.logger'];
    }
}