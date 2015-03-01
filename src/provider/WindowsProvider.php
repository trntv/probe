<?php

namespace probe\provider;

/**
 * Windows information provider
 * @author Eugene Terentev <eugene@terentev.net>
 */
class WindowsProvider extends AbstractProvider
{
    /**
     * @return string
     * @throws \Exception
     */
    public function getKernelVersion()
    {
        $wmi = $this->getWMI();

        foreach ($wmi->ExecQuery("SELECT WindowsVersion FROM Win32_Process WHERE Handle = 0") as $process) {
            return $process->WindowsVersion;
        }

        return "Unknown";
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getArchitecture()
    {
        $wmi = $this->getWMI();
        foreach ($wmi->ExecQuery("SELECT Architecture FROM Win32_Processor") as $cpu) {
            switch ($cpu->Architecture) {
                case 0:
                    return "x86";
                case 1:
                    return "MIPS";
                case 2:
                    return "Alpha";
                case 3:
                    return "PowerPC";
                case 6:
                    return "Itanium-based systems";
                case 9:
                    return "x64";
            }
        }

        return "Unknown";
    }

    /**
     * @return int|null
     * @throws \Exception
     */
    public function getUptime()
    {
        date_default_timezone_set('UTC');
        $buffer = $this->getWMI()->ExecQuery("SELECT LastBootUpTime, LocalDateTime FROM Win32_OperatingSystem");
        if ($buffer) {
            $byear = (int) substr($buffer[0]['LastBootUpTime'], 0, 4);
            $bmonth = (int) substr($buffer[0]['LastBootUpTime'], 4, 2);
            $bday = (int) substr($buffer[0]['LastBootUpTime'], 6, 2);
            $bhour = (int) substr($buffer[0]['LastBootUpTime'], 8, 2);
            $bminute = (int) substr($buffer[0]['LastBootUpTime'], 10, 2);
            $bseconds = (int) substr($buffer[0]['LastBootUpTime'], 12, 2);
            $lyear = (int) substr($buffer[0]['LocalDateTime'], 0, 4);
            $lmonth = (int) substr($buffer[0]['LocalDateTime'], 4, 2);
            $lday = (int) substr($buffer[0]['LocalDateTime'], 6, 2);
            $lhour = (int) substr($buffer[0]['LocalDateTime'], 8, 2);
            $lminute = (int) substr($buffer[0]['LocalDateTime'], 10, 2);
            $lseconds = (int) substr($buffer[0]['LocalDateTime'], 12, 2);
            $boottime = mktime($bhour, $bminute, $bseconds, $bmonth, $bday, $byear);
            $localtime = mktime($lhour, $lminute, $lseconds, $lmonth, $lday, $lyear);
            $result = $localtime - $boottime;
            return $result;
        } else {
            throw new \RuntimeException;
        }
    }

    /**
     * @param integer|boolean $key
     *
     * @return mixed string|array
     * @throws \Exception
     */
    public function getLoadAverage($key = false)
    {
        $wmi = $this->getWMI();

        $load = [];
        foreach ($wmi->ExecQuery("SELECT LoadPercentage FROM Win32_Processor") as $cpu) {
            $load[] = $cpu->LoadPercentage;
        }

        return round(array_sum($load) / count($load), 2);
    }

    /**
     * @return array|null
     */
    public function getCpuCores()
    {
        $cpuInfo = $this->getCpuInfo();
        return $cpuInfo[0]->NumberOfLogicalProcessors;
    }

    public function getCpuModel()
    {
        $cpuInfo = $this->getCpuInfo();
        return $cpuInfo[0]->Name;
    }

    public function getCpuVendor()
    {
        $cpuInfo = $this->getCpuInfo();
        return $cpuInfo[0]->Manufacturer;
    }

    public function getCpuInfo()
    {
        $wmi = $this->getWMI();


        $object = $wmi->ExecQuery("SELECT Name, Manufacturer, CurrentClockSpeed, NumberOfLogicalProcessors FROM Win32_Processor");

        if (!is_object($object)) {
            $object = $wmi->ExecQuery("SELECT Name, Manufacturer, CurrentClockSpeed FROM Win32_Processor");
        }

        return $object;
    }

    /**
     * @return bool|int
     * @throws \Exception
     */
    public function getTotalMem()
    {
        $wmi = $this->getWMI();
        $total_memory = 0;

        foreach ($wmi->ExecQuery("SELECT TotalPhysicalMemory FROM Win32_ComputerSystem") as $cs) {
            $total_memory = $cs->TotalPhysicalMemory;
            break;
        }

        return $total_memory;
    }

    /**
     * @return bool|int
     * @throws \Exception
     */
    public function getFreeMem()
    {
        $wmi = $this->getWMI();
        $free_memory = 0;

        foreach ($wmi->ExecQuery("SELECT FreePhysicalMemory FROM Win32_OperatingSystem") as $os) {
            $free_memory = $os->FreePhysicalMemory;
            break;
        }

        return $free_memory * 1024;

    }


    protected function getWMI()
    {
        $wmi = new \COM('winmgmts:{impersonationLevel=impersonate}//./root/cimv2');

        if (!is_object($wmi)) {
            throw new \RuntimeException('WMI access error. Please enable DCOM in php.ini and allow the current
                user to access the WMI DCOM object.');
        }

        return $wmi;
    }

    public function getOsRelease()
    {
        // TODO: Implement getOsRelease() method.
    }

    public function getOsType()
    {
        return 'Windows';
    }

    /**
     * @return array|null
     */
    public function getMemoryInfo()
    {
        // TODO: Implement getMemoryInfo() method.
    }

    public function getTotalSwap()
    {
        // TODO: Implement getTotalSwap() method.
    }

    public function getFreeSwap()
    {
        // TODO: Implement getFreeSwap() method.
    }

    /**
     * @param int $interval
     * @return array
     */
    public function getCpuUsage($interval = 1)
    {
        // TODO: Implement getCpuUsage() method.
    }
}
