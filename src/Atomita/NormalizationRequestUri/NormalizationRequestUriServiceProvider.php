<?php

namespace Atomita\NormalizationRequestUri;

use Illuminate\Support\ServiceProvider;

class NormalizationRequestUriServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		if (isset($_SERVER) and isset($_SERVER['REQUEST_URI'])) {
			$request_uri = &$_SERVER['REQUEST_URI'];
			
			$q_pos = strpos($request_uri, '?');
			$f_pos = strpos($request_uri, '#');
			if (false !== $q_pos || false !== $f_pos){
				$pos = false === $q_pos ? $f_pos : (false === $f_pos ? $q_pos : ($q_pos < $f_pos ? $q_pos : $f_pos));
				$front = ltrim(substr($request_uri, 0, $pos));
				$back = substr($request_uri, $pos);
			}
			else{
				$front = ltrim($request_uri);
				$back ='';
			}
			
			if (0 !== strpos($front, '/') && 0 !== strpos($front, 'http:') && 0 !== strpos($front, 'https:')) {
				$front = '/' . $front;
			}
			
			$uri = parse_url($front, PHP_URL_PATH);
			$request_uri = $uri . $back;
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return array();
	}

}
