<?php

namespace Project\Core\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Get
{
	public function __construct(?string $route) {

	}

	public const METHOD = 'GET';
}