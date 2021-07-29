<?php

declare(strict_types=1);

namespace forms\element;

use JetBrains\PhpStorm\Immutable;
use pocketmine\form\FormValidationException;
use function gettype;
use function is_bool;

/** @phpstan-extends BaseElementWithValue<bool> */
class Toggle extends BaseElementWithValue{

	public function __construct(
		string $text,
		#[Immutable] public /*readonly*/ bool $default = false,
	){
		parent::__construct($text);
	}

	public function hasChanged() : bool{
		return $this->default !== $this->getValue();
	}

	protected function getType() : string{ return "toggle"; }

	protected function validateValue(mixed $value) : void{
		if(!is_bool($value)){
			throw new FormValidationException("Expected bool, got " . gettype($value));
		}
	}

	protected function serializeElementData() : array{
		return [
			"default" => $this->default,
		];
	}
}
