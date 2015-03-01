<?php

namespace probe\provider;

/**
 * Class AbstractBSD
 * @package probe\provider
 * @author Eugene Terentev <eugene@terentev.net>
 */
abstract class AbstractBsdProvider extends AbstractUnixProvider
{

    /**
     */
    public function getTotalSwap()
    {
        $meminfo = $this->getMemInfo();
        preg_match_all('/=(.*?)M/', $meminfo['vm.swapusage'], $res);
        return isset($res[1][0]) ? (int)($res[1][0]) * 1024 * 1024 : null;
    }

    /**s
     * @return bool|int
     */
    public function getTotalMem()
    {
        $meminfo = $this->getMemInfo();
        return isset($meminfo['net.local.dgram.recvspace'])
            ? intval($meminfo['net.local.dgram.recvspace']) * 1024 * 1024
            : null;
    }

    public function getOsRelease()
    {
        return shell_exec('sw_vers -productVersion');
    }

    /**
     * @inheritdoc string
     */
    public function getKernelVersion()
    {
        return shell_exec('uname -v');
    }

    /**
     * @inheritdoc
     */
    public function getCpuModel()
    {
        $sysctlinfo = $this->getSysctlInfo();
        return array_key_exists('hw.model', $sysctlinfo)
            ? $sysctlinfo['machdep.cpu.brand_string']
            : null;
    }

    /**
     * @inheritdoc
     */
    public function getCpuCores()
    {
        $sysctlinfo = $this->getSysctlInfo();
        return array_key_exists('hw.physicalcpu', $sysctlinfo)
            ? $sysctlinfo['hw.physicalcpu']
            : null;
    }

    /**
     * @inheritdoc
     */
    public function getUptime()
    {
        $uptime = shell_exec("sysctl -n kern.boottime | awk '{print $4}' | sed 's/,//'");
        if ($uptime) {
            return (int)(time() - $uptime);
        }
    }

    /**
     * @return bool|int
     */
    public function getFreeSwap()
    {
        $meminfo = self::getMemInfo();
        preg_match_all('/=(.*?)M/', $meminfo['vm.swapusage'], $res);
        return isset($res[1][2]) ? intval($res[1][2]) * 1024 * 1024 : null;
    }
}
