<?php
class Typo3ListTask extends Task {
	private $property;

	private $outputProperty;

	private $delimiter = "\n";

	public function setProperty($property) {
		$this->property = $property;
	}

	public function setOutputProperty($outputProperty) {
		$this->outputProperty = $outputProperty;
	}

	public function setDelimiter($delimiter) {
		$this->delimiter = $delimiter;
	}

	public function main() {
        if (is_null($this->property)) {
            throw new BuildException('Parameter property is required.');
        }

        if (is_null($this->outputProperty)) {
            throw new BuildException('Parameter outputProperty is required.');
        }

		$values = explode(
			$this->delimiter,
			$this->getPropertyValue($this->property)
		);

		foreach ($values as $index => &$value) {
			$value = trim($value);
			if (empty($value)) {
				unset($values[$index]);
			}
		}

		$this->project->setProperty(
			$this->outputProperty,
			implode(',', $values)
		);
	}

	private function getPropertyValue($property) {
		if ($this->project->getUserProperty($property)) {
			$value = $this->project->getUserProperty($property);
		} else {
			$value = $this->project->getProperty($property);
		}

		return $value;
	}
}
?>