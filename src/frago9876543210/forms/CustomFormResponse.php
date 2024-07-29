<?php

declare(strict_types=1);

namespace frago9876543210\forms;

use frago9876543210\forms\element\BaseElement;
use frago9876543210\forms\element\BaseSelector;
use frago9876543210\forms\element\BaseSelectorOutput;
use frago9876543210\forms\element\Dropdown;
use frago9876543210\forms\element\Input;
use frago9876543210\forms\element\Label;
use frago9876543210\forms\element\Slider;
use frago9876543210\forms\element\StepSlider;
use frago9876543210\forms\element\Toggle;
use function array_shift;
use function is_null;

class CustomFormResponse{

	/** @phpstan-param list<element\BaseElement<covariant mixed>> $elements */
	public function __construct(private array $elements){ }

	/**
	 * @template T&element\BaseElement<covariant mixed>
	 * @phpstan-param class-string<T&element\BaseElement<covariant mixed>> $expected
	 * @phpstan-return T&element\BaseElement<covariant mixed>
	 * @throws \RuntimeException
	 */
	public function get(string $expected) : BaseElement{
		$element = array_shift($this->elements);
		return match(true){
			is_null($element) => throw new \RuntimeException("There are no elements in the container"),
			$element instanceof Label => $this->get($expected), //skip labels
			!($element instanceof $expected) => throw new \RuntimeException("Unexpected type of element"),
			default => $element,
		};
	}

	public function getDropdown() : Dropdown{ return $this->get(Dropdown::class); }

	public function getInput() : Input{ return $this->get(Input::class); }

	public function getSlider() : Slider{ return $this->get(Slider::class); }

	public function getStepSlider() : StepSlider{ return $this->get(StepSlider::class); }

	public function getToggle() : Toggle{ return $this->get(Toggle::class); }

	/** @phpstan-return list<mixed> */
	public function getValues(/*BaseSelectorOutput*/ int $output = BaseSelectorOutput::SelectedOption) : array{
		$values = [];

		foreach($this->elements as $element){
			if($element instanceof Label){
				continue;
			}

			$values[] = match(true){
				$output === BaseSelectorOutput::SelectedOption && $element instanceof BaseSelector => $element->getSelectedOption(),
				default => $element->getValue(),
			};
		}

		return $values;
	}
}
