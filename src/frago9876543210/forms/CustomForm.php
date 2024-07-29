<?php

declare(strict_types=1);

namespace frago9876543210\forms;

use frago9876543210\forms\element\BaseElement;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
use function count;
use function gettype;
use function is_array;
use function is_null;

class CustomForm extends BaseForm{

	/**
	 * @phpstan-param list<element\BaseElement<covariant mixed>> $elements
	 * @phpstan-param \Closure(Player, CustomFormResponse) : mixed $onSubmit
	 * @phpstan-param (\Closure(Player) : mixed)|null $onClose
	 */
	public function __construct(
		string $title,
		private array $elements,
		private /*readonly*/ \Closure $onSubmit,
		private /*readonly*/ ?\Closure $onClose = null,
	){
		Utils::validateCallableSignature(function(Player $player, CustomFormResponse $response){ }, $onSubmit);
		if($onClose !== null){
			Utils::validateCallableSignature(function(Player $player){ }, $onClose);
		}
		parent::__construct($title);
	}

	/** @phpstan-param element\BaseElement<mixed> ...$elements */
	public function appendElements(BaseElement ...$elements) : void{
		foreach($elements as $element){
			$this->elements[] = $element;
		}
	}

	protected function getType() : string{ return "custom_form"; }

	protected function serializeFormData() : array{
		return [
			"content" => $this->elements,
		];
	}

	/** @phpstan-param array<int, mixed> $data */
	private function validateElements(Player $player, array $data) : void{
		if(($actual = count($data)) !== ($expected = count($this->elements))){
			throw new FormValidationException("Expected $expected result data, got $actual");
		}

		foreach($data as $index => $value){
			/** @var BaseElement<mixed> $element */
			$element = $this->elements[$index] ?? throw new FormValidationException("Element at offset $index does not exist");
			try{
				$element->setValue($value);
			}catch(FormValidationException $e){
				throw new FormValidationException("Validation failed for element " . $element::class . ": " . $e->getMessage(), 0, $e);
			}
		}

		$this->onSubmit->__invoke($player, new CustomFormResponse($this->elements));
	}

	final public function handleResponse(Player $player, mixed $data) : void{
		match(true){
			is_null($data) => $this->onClose?->__invoke($player),
			is_array($data) => $this->validateElements($player, $data),
			default => throw new FormValidationException("Expected array or null, got " . gettype($data)),
		};
	}
}
