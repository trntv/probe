<?php

namespace probe\provider;

/**
 * Windows information provider
 * @author Eugene Terentev <eugene@terentev.net>
 */
class WindowsProvider extends AbstractProvider
{
    public $wmiHost;
    public $wmiUsername;
    public $wmiPassword;

    /**
     * @var \COM
     */
    protected $wmiConnection;

    protected $cpuInfo;

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
        $buffer = $this->getWMI()->ExecQuery("SELECT SystemUpTime FROM Win32_PerfFormattedData_PerfOS_System");
        foreach ($buffer as $b){
           return $b->SystemUpTime;
        }
    }

    /**
     * @return mixed string|array
     * @throws \Exception
     */
    public function getLoadAverage()
    {
        $load = [];
        foreach ($this->getCpuInfo() as $cpu) {
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
        foreach($cpuInfo as $obj) {
            return $obj->NumberOfLogicalProcessors;
        }
    }

    public function getCpuModel()
    {
        $cpuInfo = $this->getCpuInfo();
        foreach($cpuInfo as $obj) {
            return $obj->Name;
        }
    }

    public function getCpuVendor()
    {
        $cpuInfo = $this->getCpuInfo();
        foreach($cpuInfo as $obj) {
            return $obj->Manufacturer;
        }
    }

    public function getCpuInfo()
    {
        if ($this->cpuInfo === null) {
            $this->cpuInfo = $this->getWMI()->ExecQuery("SELECT * FROM Win32_Processor");
        }
        return $this->cpuInfo;
    }

    /**
     * @return bool|int
     * @throws \Exception
     */
    public function getTotalMem()
    {
        $total_memory = 0;

        foreach($this->getWMI()->ExecQuery("SELECT TotalPhysicalMemory FROM Win32_ComputerSystem") as $obj) {
            $total_memory = $obj->TotalPhysicalMemory;
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
        $objSet = $this->getWMI()->ExecQuery("SELECT FreePhysicalMemory FROM Win32_OperatingSystem");
        foreach ($objSet as $obj) {
            return $obj->FreePhysicalMemory;
        }
    }

    public function getOsRelease()
    {
        $objSet = $this->getWMI()->ExecQuery("SELECT Name FROM Win32_OperatingSystem");
        foreach ($objSet as $obj) {
            return $obj->name;
        }
    }

    public function getOsType()
    {
        return 'Windows';
    }

    public function getTotalSwap()
    {
        $total = 0;
        $objSet = $this->getWMI()->ExecQuery("SELECT AllocatedBaseSize FROM Win32_PageFileUsage");
        foreach ($objSet as $device) {
            $total += $device->AllocatedBaseSize;
        }
        return $total;
    }

    public function getUsedSwap()
    {
        $used = 0;
        $objSet = $this->getWMI()->ExecQuery("SELECT CurrentUsage FROM Win32_PageFileUsage");
        foreach ($objSet as $device) {
            $used += $device->CurrentUsage;
        }
        return $used;
    }

    public function getFreeSwap()
    {
        return $this->getTotalSwap() - $this->getUsedSwap();
    }

    /**
     * @return array
     */
    public function getCpuUsage()
    {
        $load = [];
        foreach ($this->getWMI()->ExecQuery("SELECT LoadPercentage FROM Win32_Processor") as $obj) {
            $load[] = $obj->LoadPercentage;
        }

        return $load;
    }

    /**
     * @return mixed
     */
    public function getOsKernelVersion()
    {
        $wmi = $this->getWMI();
        $objSet = $wmi->ExecQuery("SELECT BuildNumber FROM Win32_OperatingSystem");
        foreach ($objSet as $obj) {
            return $obj->BuildNumber;
        }
    }

    /**
     * @return \COM
     */
    protected function getWMI()
    {
        if ($this->wmiConnection === null) {
            $wmiLocator = new \COM('WbemScripting.SWbemLocator');
            try {
                $this->wmiConnection = $wmiLocator->ConnectServer($this->wmiHost, 'root\CIMV2', $this->wmiUsername, $this->wmiPassword);
                $this->wmiConnection->Security_->impersonationLevel = 3;
            } catch (\Exception $e) {
                if ($e->getCode() == '-2147352567') {
                    $this->wmiConnection = $wmiLocator->ConnectServer($this->wmiHosthost, 'root\CIMV2', null, null);
                    $this->wmiConnection->Security_->impersonationLevel = 3;
                }
            }
        }
        return $this->wmiConnection;
    }
}
