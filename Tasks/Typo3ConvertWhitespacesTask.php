<?php
class Typo3ConvertWhitespacesTask extends Task {
	private $property = null;

	public function setProperty($property) {
		$this->property = $property;
	}
	public function main() {
		if ($this->property === null) {
			throw new BuildException('Parameter property is required.', $this->location);
		}

		if ($this->project->getUserProperty($this->property)) {
			$value = $this->project->getUserProperty($this->property);
			$value = str_replace(' ', '\\ ', $value);
			$this->project->setInheritedProperty($this->property, $value);
		} else {
			$value = $this->project->getProperty($this->property);
			$value = str_replace(' ', '\\ ', $value);
			$this->project->setProperty($this->property, $value);
		}
	} 
}
?>