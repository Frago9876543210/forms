<?php

declare(strict_types=1);

namespace forms\element;

use JetBrains\PhpStorm\Immutable;
use pocketmine\form\FormValidationException;
use function gettype;
use function is_string;

/** @phpstan-extends BaseElementWithValue<string> */
class Input extends BaseElementWithValue{

	public function __construct(
		string $text,
		#[Immutable] public /*readonly*/ string $placeholder,
		#[Immutable] public /*readonly*/ string $default = "",
	){
		parent::__construct($text);
	}

	protected function getType() : string{ return "input"; }

	protected function validateValue(mixed $value) : void{
		if(!is_string($value)){
			throw new FormValidationException("Expected string, got " . gettype($value));
		}
	}

	protected function serializeElementData() : array{
		return [
			"placeholder" => $this->placeholder,
			"default" => $this->default,
		];
	}
}
