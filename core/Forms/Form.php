<?php

namespace core\Forms;

use core\Request;
use core\Config;

abstract class Form
{
	protected $formName;
	protected $action;
	protected $method;
	protected $fields;

	public function getName()
	{
		return $this->formName;
	}

	public function getAction()
	{
		return $this->action();
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getFields()
	{
		/**
		 * Тоже самое что и return $this->fields;
		 * При дальнейшем переборе foreach, не будет создаваться копия массива, а будет перебираться исходный массив.
		 * Для экономии памяти, хоть это и не нужно тут.
		 */
		return new \ArrayIterator($this->fields);
	}

	public function handleRequest(Request $request)
	{
		$fields = [];
		$string = '';

		foreach ($this->getFields() as $key => $field) {
			if (!isset($field['name'])) {
				continue;
			}

			$name = $field['name'];

			if ($request->getPOST($name) !== null) {

				if ($this->fields[$key]['type'] === 'checkbox') {
					$this->fields[$key]['checked'] = 'checked';
				}

				$this->setAttribute($key, 'value', $request->getPOST($name));

				$fields[$name] = $request->getPOST($name);
			}
		}

		if ($request->getPOST('sign') !== null && $this->getSign() !== $request->getPOST('sign')) {
			throw new \Exception('Формы не совпадают!');
		}

		return $fields;
	}

	public function setAttribute($key, $attrName, $attrValue)
	{
		$this->fields[$key][$attrName] = $attrValue;
	}

	public function getSign()
	{
		$string = '';

		foreach ($this->getFields() as $field) {
			if (isset($field['name'])) {
				$string .= Config::SALT_FORMS . $field['name'];
			}
		}

		return md5($string);
	}

	public function addErrors(array $errors)
	{
		foreach ($this->fields as $key => $field) {
			$name = $field['name'] ?? null;

			if (isset($errors[$name])) {
				$this->fields[$key]['errors'] = $errors[$name];
			}
		}
	}
}
