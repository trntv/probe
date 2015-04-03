System Information Provider
================================
This package provide an easy interface to get information about system it is running on.
```php
$provider = \probe\Factory::create();
$provider->getCpuModel();
$provider->getCpuUsage();
$provider->getFreeMem();
```

## Available methods
- getOsRelease()
- getOsType();
- getOsKernelVersion();
- getArchitecture();
- getDbVersion(\PDO $connection);
- getDbInfo(\PDO $connection);
- getDbType(\PDO $connection);
- getTotalMem();
- getFreeMem();
- getUsedMem();
- getTotalSwap();
- getFreeSwap();
- getUsedSwap();
- getHostname();
- isLinuxOs();
- isWindowsOs();
- isBsdOs();
- isMacOs();
- getUptime();
- getCpuCores();
- getCpuPhysicalCore();
- getCpuModel();
- getCpuUsage();
- getServerIP();
- getExternalIP();
- getServerSoftware();
- isISS();
- isNginx();
- isApache();
- getPhpInfo($what = -1);
- getPhpVersion();
- getPhpDisabledFunctions();
- getPhpModules();
- isPhpModuleLoaded($module);
- getPing(array $hosts = null, $count = 2);
- getServerVariable($key);
- getPhpSapiName();
- isFpm();
- isCli();

## Table of methods supported by different OS
TBD

## Supported OS
- Linux
- Windows

**Note**: To get Windows System Information, you hould have `php_com_dotnet.dll` enabled in your `php.ini`.
```php
[COM_DOT_NET] 
extension=php_com_dotnet.dll
```

## Other OS
There are incomplete implementations of other OS providers in the separate branches. If you can help me to implement it 
faster, make pull requests.

## Contributing
I don't have any special rules for it. Any help in any way will be useful.

## TODO
- disk usage
- rx/tx
- processes list