<?php

namespace HavenShen\Larsign;

use Closure;
use Larsign;

/**
 * HandleLarsign
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class HandleLarsign
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (! Larsign::check($request)) {
            throw new LarsignException();
        }

        return $next($request);
    }
}