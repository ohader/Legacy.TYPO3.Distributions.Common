<?php
class Typo3ConvertVersionTask extends Task {
	const PREFIX = 'TYPO3_';
	const DELIMITER = '-';

	const TYPE_Version = 'version';
	const TYPE_Branch = 'branch';

	private $value = null;
	private $type = null;
	private $property = null;

	public function setValue($value) {
		$this->value = $value;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setProperty($property) {
		$this->property = $property;
	}
	public function main() {
		if ($this->value === null) {
			throw new BuildException('Parameter value is required.', $this->location);
		}

		if ($this->type === null) {
			throw new BuildException('Parameter type is required.', $this->location);
		}

		if ($this->property === null) {
			throw new BuildException('Parameter property is required.', $this->location);
		}

		$result = null;

		// Branch 4.5
		if (preg_match('/^(\d+)\.(\d+)$/', $this->value, $matches) && $this->type === self::TYPE_Branch) {
			$result = self::PREFIX . $matches[1] . self::DELIMITER . $matches[2];
		// Version 4.5.2
		} elseif (preg_match('/^(\d+)\.(\d+)\.(\d+)$/', $this->value, $matches) && $this->type === self::TYPE_Version) {
			$result = self::PREFIX . $matches[1] . self::DELIMITER . $matches[2] . self::DELIMITER . $matches[3];
		} else {
			throw new BuildException('Given value "' . $this->value . '" did not qualify as valid ' . $this->type, $this->location);
		}

		$this->project->setProperty($this->property, $result);
	} 
}
?>