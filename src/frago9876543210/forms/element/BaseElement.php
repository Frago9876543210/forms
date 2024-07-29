<?php

declare(strict_types=1);

namespace frago9876543210\forms\element;

use pocketmine\form\FormValidationException;

/** @phpstan-template TValue */
abstract class BaseElement implements \JsonSerializable{

	/** @phpstan-param ?TValue $value */
	public function __construct(public /*readonly*/ string $text, private mixed $value = null){ }

	/** @phpstan-return TValue */
	public function getValue() : mixed{
		return $this->value ?? throw new \RuntimeException("Trying to access an uninitialized value");
	}

	/** @phpstan-param TValue $value */
	public function setValue(mixed $value) : void{
		$this->validateValue($value);
		$this->value = $value;
	}

	abstract protected function getType() : string;

	/** @throws FormValidationException */
	abstract protected function validateValue(mixed $value) : void;

	/** @phpstan-return array<string, mixed> */
	abstract protected function serializeElementData() : array;

	/** @phpstan-return array<string, mixed> */
	final public function jsonSerialize() : array{
		$ret = $this->serializeElementData();
		$ret["type"] = $this->getType();
		$ret["text"] = $this->text;

		return $ret;
	}
}
