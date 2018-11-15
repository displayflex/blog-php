<?php

namespace core\Forms;

class FormBuilder
{
	public $form;

	public function __construct(Form &$form)
	{
		$this->form = $form;
	}

	public function method()
	{
		$method = $this->form->getMethod();

		if (null === $method) {
			$method = 'GET';
		}

		return sprintf('method="%s"', $method);
	}

	public function fields()
	{
		$inputs = [];

		foreach ($this->form->getFields() as $field) {
			$inputs[] = $this->input($field);
		}

		return $inputs;
	}

	public function input(array $attributes)
	{
		$errors = '';
		$label = null;

		if (isset($attributes['errors'])) {
			$class = $attributes['class'] ?? '';
			$attributes['class'] = trim(sprintf('%s input-error', $class));
			$errors = $attributes['errors'];
			unset($attributes['errors']);
			$errors = '<div>' . implode('</div><div>', $errors) . '</div>';
		}

		if (isset($attributes['label'])) {
			$label = $attributes['label'] . ':' ?? '';
			unset($attributes['label']);
		}

		if ($attributes['type'] === 'textarea') {
			$value = $attributes['value'] ?? '';
			unset($attributes['value']);
			$textarea = sprintf('<textarea %s>%s</textarea>%s', $this->buildAttributes($attributes), $value, $errors);

			return $textarea;
		}

		$input = sprintf('<input %s>%s', $this->buildAttributes($attributes), $errors);

		if ($label) {
			$input = sprintf('<label>%s%s</label>', $label, $input);
		}

		return $input;
	}

	public function inputSign()
	{
		return $this->input([
			'type' => 'hidden',
			'name' => 'sign',
			'value' => $this->form->getSign()
		]);
	}

	private function buildAttributes(array $attributes)
	{
		$arr = [];

		foreach ($attributes as $attribute => $value) {
			$arr[] = sprintf('%s="%s"', $attribute, $value);
		}

		return implode(' ', $arr);
	}
}
