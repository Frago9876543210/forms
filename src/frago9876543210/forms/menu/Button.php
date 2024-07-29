<?php

declare(strict_types=1);

namespace frago9876543210\forms\menu;

class Button implements \JsonSerializable{

	public function __construct(public /*readonly*/ string $text, public /*readonly*/ ?Image $image = null, private ?int $value = null){ }

	public function getValue() : int{
		return $this->value ?? throw new \RuntimeException("Trying to access an uninitialized value");
	}

	public function setValue(int $value) : self{
		$this->value = $value;
		return $this;
	}

	/** @phpstan-return array<string, mixed> */
	public function jsonSerialize() : array{
		$ret = ["text" => $this->text];
		if($this->image !== null){
			$ret["image"] = $this->image;
		}

		return $ret;
	}
}
