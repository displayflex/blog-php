<?php

namespace core;

class Validator
{
	const TYPE_STRING = 'string';
	const TYPE_INTEGER = 'integer';

	public $clean = [];
	public $errors = [];
	public $success = false;
	private $rules;

	public function execute(array $fields)
	{
		if (!$this->rules) {
			// error
		}

		foreach ($this->rules as $name => $rulesList) {
			if (!isset($fields[$name]) && isset($rulesList['required'])) {
				$this->errors[$name][] = sprintf('Field [%s] is required!', $name);
			}

			if (!isset($fields[$name]) && (!isset($rulesList['required'])) || !$rulesList['required']) {
				continue;
			}

			if (isset($rulesList['type'])) {
				if ($rulesList['type'] === self::TYPE_STRING) {
					$fields[$name] = trim(htmlspecialchars($fields[$name]));
				} elseif ($rulesList['type'] === self::TYPE_INTEGER) {
					if (!ctype_digit($fields[$name])) {
						$this->errors[$name][] = sprintf('Only numbers are allowed in field [%s]!', $name);
					}
				}
			}

			if (isset($rulesList['notBlank']) && $rulesList['notBlank'] && strlen($fields[$name]) === 0) {
				$this->errors[$name][] = sprintf('Field [%s] should not be empty!', $name);
			}

			if (isset($rulesList['length'])) {
				if (is_array($rulesList['length'])) {
					if (strlen($fields[$name]) < $rulesList['length'][0]) {
						$this->errors[$name][] = sprintf('The length of field [%s] should be more than %s symbols!', $name, $rulesList['length'][0]);
					} elseif (strlen($fields[$name]) > $rulesList['length'][1]) {
						$this->errors[$name][] = sprintf('The length of field [%s] should be less than %s symbols!', $name, $rulesList['length'][1]);
					}
				} elseif (is_int($rulesList['length'])) {
					if (strlen($fields[$name]) > $rulesList['length']) {
						$this->errors[$name][] = sprintf('The length of field [%s] should be less than %s symbols!', $name, $rulesList['length']);
					}
				}
			}

			if (empty($this->errors[$name])) {
				$this->clean[$name] = $fields[$name];
			}
		}

		if (empty($this->errors)) {
			$this->success = true;
		}
	}

	public function setRules(array $rules)
	{
		$this->rules = $rules;
	}
}
