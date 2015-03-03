<?php

namespace probe\provider;

/**
 * Class LinuxProvider
 * @package probe\provider
 * @author Eugene Terentev <eugene@terentev.net>
 */
class LinuxProvider extends AbstractUnixProvider
{
    /**
     * @inheritdoc
     */
    public function getUptime()
    {
        $uptime = file_get_contents('/proc/uptime');
        $uptime = explode('.', $uptime);
        return (int) array_shift($uptime);
    }

    /**
     * @inheritdoc
     */
    public function getOsRelease()
    {
        return shell_exec('/usr/bin/lsb_release -ds');
    }

    /**
     * @inheritdoc
     */
    public function getOsKernelVersion()
    {
        return shell_exec('/bin/uname -r');
    }

    /**
     * @inheritdoc
     */
    public function getTotalSwap()
    {
        $meminfo = $this->getMemInfo();
        return array_key_exists('SwapTotal', $meminfo) ? (int) ($meminfo['SwapTotal'] * 1024) : null;
    }

    /**
     * @inheritdoc
     */
    public function getFreeSwap()
    {
        $memInfo = $this->getMemInfo();
        return array_key_exists('SwapFree', $memInfo) ? (int) ($memInfo['SwapFree'] * 1024) : null;
    }

    public function getUsedSwap()
    {
        return $this->getTotalSwap() - $this->getFreeSwap();
    }

    /**
     * @inheritdoc
     */
    public function getTotalMem()
    {
        $meminfo = $this->getMemInfo();
        return array_key_exists('MemTotal', $meminfo) ? (int) ($meminfo['MemTotal'] * 1024) : null;
    }

    /**
     * @inheritdoc
     */
    public function getFreeMem()
    {
        $memInfo = $this->getMemInfo();

        $memFree = array_key_exists('MemFree', $memInfo) ? (int) $memInfo['MemFree'] : null;
        $cached  = array_key_exists('Cached', $memInfo) ? (int) $memInfo['Cached'] : null;

        $result = ($memFree ?: null) + ($cached ?: null);

        return $result ? $result * 1024: null;
    }

    /**
     * @inheritdoc
     */
    public function getUsedMem()
    {
        return $this->getTotalMem() - $this->getUsedMem();
    }

    /**
     * @inheritdoc
     */
    public function getOsType()
    {
        return 'Linux';
    }

    /**
     * @inheritdoc
     */
    public function getCpuinfo()
    {
        if (!$this->cpuInfo) {
            $cpuInfo = file_get_contents('/proc/cpuinfo');
            $cpuInfo = explode("\n", $cpuInfo);
            $values = [];
            foreach ($cpuInfo as $v) {
                $v = array_map('trim', explode(':', $v));
                if (isset($v[0], $v[1])) {
                    $values[$v[0]] = $v[1];
                }
            }
            $this->cpuInfo = $values;
        }
        return $this->cpuInfo;
    }

    /**
     * @inheritdoc
     */
    public function getCpuModel()
    {
        $cu = $this->getCpuinfo();
        return array_key_exists('model name', $cu) ? $cu['model name'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getCpuVendor()
    {
        $cu = $this->getCpuinfo();
        return array_key_exists('vendor_id', $cu) ? $cu['vendor_id'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getCpuCores()
    {
        $cu = $this->getCpuinfo();
        return array_key_exists('siblings', $cu) ? $cu['siblings'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getCpuPhysicalCores()
    {
        $cu = $this->getCpuinfo();
        return array_key_exists('cpu cores', $cu) ? $cu['cpu cores'] : null;
    }
}
