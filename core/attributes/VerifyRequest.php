<?php 

namespace Project\Core\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class VerifyRequest
{
	public function __construct(array $requiredKeys) {

	}
}