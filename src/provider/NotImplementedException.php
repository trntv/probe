<?php

namespace probe\provider;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class NotImplementedException extends \Exception
{
    protected $message = 'Method is not implemented in this provider';
}
