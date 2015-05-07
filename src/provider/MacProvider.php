<?php

namespace probe\provider;

/**
 * Class Mac
 * @author Eugene Terentev <eugene@terentev.net>
 * @author Semen Kotliarenko <semako.ua@gmail.com>
 * @package probe\os
 */
class MacProvider extends AbstractBsdProvider
{
    public function getOsType()
    {
        return 'Mac';
    }
}
