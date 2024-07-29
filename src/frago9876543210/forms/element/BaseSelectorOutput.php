<?php

namespace frago9876543210\forms\element;

/**
 * Defines how `BaseSelector` values should be returned. As an index or as string option.
 */
/*enum*/ final class BaseSelectorOutput{
	/*case*/ const SelectedIndex = 0;
	/*case*/ const SelectedOption = 1;

	private function __construct(){
		//NOP
	}
}
