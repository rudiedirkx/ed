<?php

namespace rdx\ed;

use rdx\jsdom\Node;

class ArticleLink {

	protected $link;

	public function __construct(Node $link) {
		$this->link = $link;
	}

	public function getLabel() {
		return trim($this->link['title']);
	}

	public function getUrl() {
		return trim($this->link['href']);
	}

	public function getCategory() {
		$path = parse_url($this->getUrl(), PHP_URL_PATH);
		$components = explode('/', trim($path, '/'));

		return $components[0];
	}

	public function save() {
		$title = $this->getLabel();
		$url = $this->getUrl();
		$category = $this->getCategory();

		$exists = Article::first(['url' => $url]);
		if (!$exists) {
			return Article::insert([
				'title' => $title,
				'url' => $url,
				'category' => $category,
				'saved_on' => time(),
			]);
		}
	}

}
