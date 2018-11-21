<?php

namespace ftwr\blogphp\core;

use ftwr\blogphp\core\exceptions\ValidatorException;

class Validator
{
	const TYPE_STRING = 'string';
	const TYPE_INTEGER = 'integer';
	const TYPE_INTEGER_SHORT = 'int';
	const LENGTH_ANY = 'big';

	public $clean = [];
	public $errors = [];
	public $success = false;
	private $rules;

	public function execute(array $fields)
	{
		if (!$this->rules) {
			throw new ValidatorException('Rules for validation not found');
		}

		foreach ($this->rules as $name => $rulesList) {
			// првоерка на обязательные поля
			if (!isset($fields[$name]) && isset($rulesList['required']) && $rulesList['required']) {
				$this->errors[$name][] = sprintf('Field [%s] is required!', $name);
			}

			// если поля нет и оно не обязательно - то переход к проверке следующего поля
			if (!isset($fields[$name]) && (!isset($rulesList['required'])) || !$rulesList['required']) {
				continue;
			}

			// првоерка на соответствие типу
			if (isset($rulesList['type']) && !$this->isTypeMatch($fields[$name], $rulesList['type'])) {
				$this->errors[$name][] = sprintf('Field [%s] must be a type of %s!', $name, $rulesList['type']);
			}

			// проверка на то что поле не пустое
			if (isset($rulesList['notBlank']) && $rulesList['notBlank'] && $this->isBlank($fields[$name])) {
				$this->errors[$name][] = sprintf('Field [%s] should not be empty!', $name);
			}

			// проверка на то что поле не содержит пробелы
			if (isset($rulesList['notContainsSpaces']) && $rulesList['notContainsSpaces']) {
				if (mb_strpos($fields[$name], ' ') !== false) {
					$this->errors[$name][] = sprintf('Field [%s] should not contain empty symbols!', $name);
				}
			}

			// проверка длины пришедших данных
			if (isset($rulesList['length']) && !$this->isLengthMatch($fields[$name], $rulesList['length'])) {
				if (is_array($rulesList['length'])) {
					$this->errors[$name][] = sprintf(
						'Field [%s] has an incorrect length! Expected: from %s to %s. Given: %s.',
						$name,
						$rulesList['length'][0],
						$rulesList['length'][1],
						mb_strlen($fields[$name])
					);
				} elseif (gettype($rulesList['length']) === self::TYPE_INTEGER || ctype_digit($rulesList['length'])) {
					$this->errors[$name][] = sprintf(
						'Length of field [%s] should be less than %s symbols! Given: %s.',
						$name,
						$rulesList['length'],
						mb_strlen($fields[$name])
					);
				} else {
					$this->errors[$name][] = sprintf('Field [%s] has an incorrect length!', $name);
				}
			}

			if (empty($this->errors[$name])) {
				if (isset($rulesList['type']) && $rulesList['type'] === self::TYPE_INTEGER) {
					$this->clean[$name] = (int)$fields[$name];
				} else {
					$this->clean[$name] = trim(htmlspecialchars($fields[$name]));
				}
			}
		}

		if (empty($this->errors)) {
			$this->success = true;
		}

		return $this->clean;
	}

	public function setRules(array $rules)
	{
		$this->rules = $rules;
	}

	public function isBlank($field)
	{
		$field = trim($field);

		return $field === null || $field === '';
	}

	public function isTypeMatch($field, $type)
	{
		switch ($type) {
			case self::TYPE_STRING:
				return is_string($field);
				break;
			case self::TYPE_INTEGER_SHORT:
			case self::TYPE_INTEGER:
				return gettype($field) === self::TYPE_INTEGER || ctype_digit($field);
				break;
			default:
				throw new ValidatorException('Incorrect type given to method isTypeMatch()');
				break;
		}
	}

	public function isLengthMatch($field, $length)
	{
		if ($isArray = is_array($length)) {
			$min = isset($length[0]) ? $length[0] : false;
			$max = isset($length[1]) ? $length[1] : false;
		} elseif ($length === self::LENGTH_ANY) {
			return true;
		} else {
			$min = false;
			$max = $this->isTypeMatch($length, self::TYPE_INTEGER) ? $length : false;
		}

		if ($isArray && (!$max || !$min)) {
			throw new ValidatorException('Incorrect data given to method isLengthMatch()');
		}

		if (!$isArray && !$max) {
			throw new ValidatorException('Incorrect data given to method isLengthMatch()');
		}

		$minIsMatch = $min ? $this->isLengthMinMatch($field, $min) : false;
		$maxIsMatch = $max ? $this->isLengthMaxMatch($field, $max) : false;

		return $isArray ? $minIsMatch && $maxIsMatch : $maxIsMatch;
	}

	public function isLengthMinMatch($field, $length)
	{
		return mb_strlen($field) > $length;
	}

	public function isLengthMaxMatch($field, $length)
	{
		return mb_strlen($field) < $length;
	}
}
