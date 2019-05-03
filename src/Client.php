<?php

namespace rdx\ed;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RedirectMiddleware;
use rdx\jsdom\Node;

class Client {

	public $guzzle;
	public $cookies;

	public function __construct() {
		$this->setUpGuzzle();
	}

	public function getArticleLinks() {
		$rsp = $this->guzzle->get('https://www.ed.nl/accept?url=https://www.ed.nl/');

		$dom = Node::create((string) $rsp->getBody());

		$links = $dom->queryAll('.col--secondary .fjs-autoupdate-widget a');
		$links = array_map(function(Node $link) {
			return new ArticleLink($link);
		}, $links);
		return $links;
	}

	protected function setUpGuzzle() {
		$this->cookies = new CookieJar();

		$stack = HandlerStack::create();
		$this->guzzle = new Guzzle([
			// 'base_uri' => $this->base,
			'handler' => $stack,
			'cookies' => $this->cookies,
			'allow_redirects' => [
				'track_redirects' => true,
			] + RedirectMiddleware::$defaultSettings,
		]);

		$stack->push(Middleware::tap(
			function( $request, $options ) {
				$this->guzzle->log[] = [
					'time' => microtime(1),
					'request' => (string) $request->getUri(),
				];
			},
			function( $request, $options, $response ) {
				$response->then(function( $response ) {
					$log = &$this->guzzle->log[count($this->guzzle->log) - 1];
					$log['time'] = microtime(1) - $log['time'];
					$log['response'] = $response->getStatusCode();
				});
			}
		));

		$this->guzzle->log = [];
	}

}
