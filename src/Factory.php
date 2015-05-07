<?php

namespace probe;

/**
 * Class Reader
 * @author Eugene Terentev <eugene@terentev.net>
 * @author Semen Kotliarenko <semako.ua@gmail.com>
 * @package probe
 */
class Factory
{
    /**
     * @var
     */
    protected static $provider;

    /**
     * @var array
     */
    public static $providers = [
        'Linux' => 'probe\provider\LinuxProvider',
        'Mac' => 'probe\provider\MacProvider',
    ];

    /**
     * @return provider\AbstractProvider|null
     */
    public static function create()
    {
        if (null === self::$provider) {
            $osType = self::getOsType();
            if (array_key_exists($osType, self::$providers)) {
                self::$provider = new self::$providers[$osType];
            }
        }
        return self::$provider;


    }

    /**
     * @return string
     */
    public static function getOsType()
    {
        $osType = null;
        if (strtolower(substr(PHP_OS, 0, 3)) === 'win') {
            $osType = 'Windows';
        } elseif (strtolower(substr(PHP_OS, 0, 6)) === 'darwin') {
            $osType = 'Mac';
        } elseif (stristr(strtolower(PHP_OS), 'bsd')) {
            $osType = 'BSD';
        } elseif (strtolower(substr(PHP_OS, 0, 5)) === 'linux') {
            $osType = 'Linux';
        }
        return $osType;
    }
}