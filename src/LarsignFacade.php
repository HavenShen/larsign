<?php

namespace HavenShen\Larsign;

use HavenShen\Larsign\LarsignService;
use Illuminate\Support\Facades\Facade;

/**
 * LarsignFacade
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class LarsignFacade extends Facade
{
    /**
     * Get the registered component.
     *
     * @return object
     */
    protected static function getFacadeAccessor()
    {
        return LarsignService::class;
    }
}