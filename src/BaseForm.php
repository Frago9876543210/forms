<?php

declare(strict_types=1);

namespace forms;

use JetBrains\PhpStorm\Immutable;
use pocketmine\form\Form;

#[Immutable]
abstract class BaseForm implements Form{

	public function __construct(public /*readonly*/ string $title){ }

	abstract protected function getType() : string;

	/** @phpstan-return array<string, mixed> */
	abstract protected function serializeFormData() : array;

	/** @phpstan-return array<string, mixed> */
	final public function jsonSerialize() : array{
		$ret = $this->serializeFormData();
		$ret["type"] = $this->getType();
		$ret["title"] = $this->title;

		return $ret;
	}
}
