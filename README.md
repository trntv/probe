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
- getTotalSwap();
- getFreeSwap();
- getHostname();
- isLinuxOs();
- isWindowsOs();
- isBsdOs();
- isMacOs();
- getUptime();
- getCpuCores();
- getCpuModel();
- getLoadAverage();
- getCpuUsage($interval = 1);
- getServerIP();
- getExternalIP();
- getServerSoftware();
- isISS();
- isNginx();
- isApache();
- getPhpInfo($what = -1);
- getPhpVersion();
- getPhpDisabledFunctions();
- getPing(array $hosts = null, $count = 2);
- getServerVariable($key);
- getPhpSapiName();
- isFpm();
- isCli();

## Supported OS
- Linux

<!--
**Note**: To get Windows System Information, you hould have `php_com_dotnet.dll` enabled in your `php.ini`.
```php
[COM_DOT_NET] 
extension=php_com_dotnet.dll
```
-->
## Other OS
There are incomplete implementations of other OS providers in the separate branches. If you can help me to implement it 
faster, make pull requests.

## TODO
- disk usage
- rx/tx
- processes list