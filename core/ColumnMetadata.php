<?php

namespace Project\Core;

use ReflectionProperty;
use Project\Core\Attributes\Orm\PrimaryColumn;
use Project\Core\Attributes\Orm\Column;

class ColumnMetadata {

	public string $columnType;
	public string $columnConstraint;
	public bool $isPrimaryKey = false;
	private string $name;
	private BaseModel $model;
	private ReflectionProperty $property;

	public function __construct(ReflectionProperty $property, BaseModel $model) {
		$this->name = strtolower($property->getName());
		$this->columnType = $this->getColumnType($property);
		$this->columnConstraint = $this->getColumnConstraint($property);
		$this->model = $model;
		$this->property = $property;
	}

	private function getColumnType(ReflectionProperty $property): string {
		$columnAttr = ($property->getAttributes(Column::class)[0] ?? $property->getAttributes(PrimaryColumn::class)[0])->getArguments();
		$type = strtoupper($columnAttr["type"] ?? null);
		if (!$type) {
			switch ($property->getType()->getName()) {
				case 'string':
					return 'VARCHAR(255)';
				case 'bool':
					return 'TINYINT(1)';
				case 'int':
					return 'BIGINT';
				default:
					return 'VARCHAR(255)';
			}
		} else return $type;
	}

	private function getColumnConstraint(ReflectionProperty $property): string {
		$columnAttr = $property->getAttributes(Column::class)[0] ?? $property->getAttributes(PrimaryColumn::class)[0];
		if ($columnAttr->getName() == PrimaryColumn::class) {
			$this->isPrimaryKey = true;
			return ' UNSIGNED AUTO_INCREMENT PRIMARY KEY';
		}
		else {
			$constraint = "";
			if ($columnAttr->getArguments()["nullable"] ?? null)
				$constraint .= " NOT NULL";
			if ($default = $columnAttr->getArguments()["default"] ?? null)
				$constraint .= " DEFAULT $default";
			return $constraint;
		}
	}

	public function getDefinition() {
		return $this->name . " " . $this->columnType . $this->columnConstraint;
	}

	public function getValue(): mixed {
		$val = $this->model->{$this->property->getName()};
		if (is_bool($val))
			return $val ? 1 : 0;
		else return $val;
	}
	public function getName(): string {
		return $this->name;
	}
}