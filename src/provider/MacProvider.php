<?php

namespace probe\provider;

/**
 * Class Mac
 * @author Eugene Terentev <eugene@terentev.net>
 * @package probe\os
 */
class Mac extends AbstractBsdProvider
{
    public function getOsType()
    {
        return 'Mac';
    }

    /**
     * @inheritdoc
     */
    public function getCpuModel()
    {
        $sysctlinfo = $this->getSysctlInfo();
        return array_key_exists('machdep.cpu.brand_string', $sysctlinfo)
            ? $sysctlinfo['machdep.cpu.brand_string']
            : parent::getCpuModel();
    }

    /**
     * @return mixed
     */
    public function getFreeMem()
    {
        // TODO: Implement getFreeMem() method.
    }

    /**
     * @return array|null
     */
    public function getCpuVendor()
    {
        // TODO: Implement getCpuVendor() method.
    }
}
