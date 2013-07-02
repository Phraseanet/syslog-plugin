# Phraseanet Syslog Plugin

[![Build Status](https://travis-ci.org/Phraseanet/syslog-plugin.png?branch=master)](https://travis-ci.org/Phraseanet/syslog-plugin)

## Installation

First, retrieve the latest version :

```
git clone https://github.com/Phraseanet/syslog-plugin.git
```

Then, use Phraseanet Konsole to install the plugin (please be sure to run
the command with the right user - www-data for instance)

```
bin/console plugin:add /path/to/syslog-plugin
```

## Configuration

Use the following options to configure the plugin in your `configuration.yml`

```yaml
plugins:
    syslog-plugin:
        # values : task_abstract::[LOG_DEBUG | LOG_INFO | LOG_WARNING |
        # LOG_ERROR | LOG_CRITICAL | LOG_ALERT]
        level: task_abstract::LOG_ERROR
        channels:
            task-manager.logger
            monolog
```

 - level: optional, default to `\task_abstract::LOG_DEBUG`
 - channels: optional, array, default to all channels.

## Uninstall

Use Phraseanet Konsole to uninstall the plugin

```
bin/console plugin:remove syslog-plugin
```

## License

Phraseanet Syslog plugin is released under the MIT license
