<?php
class Typo3BranchListTask extends Task {
	const BRANCH_Pattern = 'TYPO3_\d+-\d+';
	const MASTER_Pattern = 'master';

	/**
	 * @var string
	 */
	private $input;

	/**
	 * @var string
	 */
	private $start;

	/**
	 * @var string
	 */
	private $property;

	public function setInput($input) {
		$this->input = $input;
	}

	public function setStart($start) {
		$this->start = $start;
	}

	public function setProperty($property) {
		$this->property = $property;
	}

	public function main() {
        if (is_null($this->input)) {
            throw new BuildException('Parameter input is required.');
        }

		if (is_null($this->start)) {
			throw new BuildException('Parameter start is required.');
		}

		if (is_null($this->property)) {
			throw new BuildException('Parameter property is required.');
		}

		$this->project->setProperty(
			$this->property,
			implode(',', $this->getBranchesByStart())
		);
	}

	private function getBranches() {
		$result = preg_replace('#^[^/]*refs/heads/(.+)$#m', '$1', $this->input);
		$branches = $this->explode($result);
		sort($branches);
		return $branches;
	}

	private function getBranchesByStart() {
		$foundStart = FALSE;
		$branchesByStart = array();

		foreach ($this->getBranches() as $branch) {
			if ($branch === $this->start) {
				$foundStart = TRUE;
			}

			if ($foundStart) {
				$branchesByStart[] = $branch;
			}
		}

		return $branchesByStart;
	}

	private function explode($string) {
		$string = trim($string);
		$elements = explode(PHP_EOL, $string);

		$pattern = '#^(' . self::BRANCH_Pattern . '|' . self::MASTER_Pattern . ')$#';

		foreach ($elements as $index => $element) {
			if (!preg_match($pattern, $element)) {
				unset($elements[$index]);
			}
		}

		return $elements;
	}
}
?>