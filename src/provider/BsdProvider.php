<?php

namespace probe\provider;

/**
 * Class BSD
 * @author Eugene Terentev <eugene@terentev.net>
 * @package probe\os
 */
class BsdProvider extends AbstractBSDProvider
{
    public function getOsType()
    {
        return 'BSD';
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
