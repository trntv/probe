<?php
/**
 * @author Eugene Terentev <eugene@terentev.net>
 */

namespace probe\provider;

/**
 * Interface ProviderInterface
 * @package systeminfo\provider
 */
interface ProviderInterface
{
    /**
     * @return mixed
     */
    public function getOsRelease();

    /**
     * @return mixed
     */
    public function getOsType();

    /**
     * @return mixed
     */
    public function getOsKernelVersion();

    /**
     * @return string
     */
    public function getArchitecture();

    /**
     * @param \PDO $connection
     * @return mixed
     */
    public function getDbVersion(\PDO $connection);

    /**
     * @param \PDO $connection
     * @return mixed
     */
    public function getDbInfo(\PDO $connection);

    /**
     * @param \PDO $connection
     * @return mixed
     */
    public function getDbType(\PDO $connection);

    /**
     * @return bool|int
     */
    public function getTotalMem();

    /**
     * @return mixed
     */
    public function getFreeMem();

    /**
     * @return mixed
     */
    public function getTotalSwap();

    /**
     * @return mixed
     */
    public function getFreeSwap();

    /**
     * @return string
     */
    public function getHostname();

    /**
     * @return bool
     */
    public function isLinuxOs();

    /**
     * @return bool
     */
    public function isWindowsOs();

    /**
     * @return bool
     */
    public function isBsdOs();

    /**
     * @return bool
     */
    public function isMacOs();

    /**
     * @return int|null
     */
    public function getUptime();

    /**
     * @return array|null
     */
    public function getCpuCores();

    /**
     * @return array|null
     */
    public function getCpuModel();

    /**
     * @return mixed
     */
    public function getLoadAverage();

    /**
     * @param int $interval
     * @return array
     */
    public function getCpuUsage($interval = 1);

    /**
     * @return mixed
     */
    public function getServerIP();

    /**
     * @return string
     */
    public function getExternalIP();

    /**
     * @return mixed
     */
    public function getServerSoftware();

    /**
     * @return bool
     */
    public function isISS();

    /**
     * @return bool
     */
    public function isNginx();

    /**
     * @return bool
     */
    public function isApache();

    /**
     * @param int $what
     * @return string
     */
    public function getPhpInfo($what = -1);

    /**
     * @return string
     */
    public function getPhpVersion();

    /**
     * @return array
     */
    public function getPhpDisabledFunctions();

    /**
     * @param array $hosts
     * @param int $count
     * @return array
     */
    public function getPing(array $hosts = null, $count = 2);

    /**
     * Retrieves data from $_SERVER array
     * @param $key
     * @return mixed|null
     */
    public function getServerVariable($key);

    /**
     * @return mixed
     */
    public function getPhpSapiName();

    /**
     * @return bool
     */
    public function isCli();

    /**
     * @return bool
     */
    public function isFpm();
}
