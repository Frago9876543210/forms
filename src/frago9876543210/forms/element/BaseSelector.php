<?php

declare(strict_types=1);

namespace frago9876543210\forms\element;

use pocketmine\form\FormValidationException;
use function gettype;
use function is_int;

/**
 * @phpstan-template TValue
 * @phpstan-extends BaseElement<TValue>
 */
abstract class BaseSelector extends BaseElement{

	/** @phpstan-param list<string> $options */
	public function __construct(
		string $text,
		public /*readonly*/ array $options,
		public /*readonly*/ int $default = 0,
	){
		parent::__construct($text);
	}

	public function getSelectedOption() : string{
		return $this->options[$this->getValue()];
	}

	protected function validateValue(mixed $value) : void{
		if(!is_int($value)){
			throw new FormValidationException("Expected int, got " . gettype($value));
		}
		if(!isset($this->options[$value])){
			throw new FormValidationException("Option $value does not exist");
		}
	}
}
