<?php

namespace Reallyli\AB\Middleware;

use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Contracts\Foundation\Application;
use Closure;

/**
 * Class BeforeMiddleware
 * @package Reallyli\AB\Middleware
 */
class BeforeMiddleware implements Middleware
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * BeforeMiddleware constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
       $this->app['ab']->track($request);

       return $next($request);
	}
}
