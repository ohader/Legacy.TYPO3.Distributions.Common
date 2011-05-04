<?php
class Typo3GitChangelogTask extends Task {
	private $property;

	private $fieldDelimiter;

	private $commitDelimiter;

	private $file;

	private $format;

	public function setProperty($property) {
		$this->property = $property;
	}

	public function setFieldDelimiter($fieldDelimiter) {
		$this->fieldDelimiter = $fieldDelimiter;
	}

	public function setCommitDelimiter($commitDelimiter) {
		$this->commitDelimiter = $commitDelimiter;
	}

	public function setFile($file) {
		$this->file = $file;
	}

	public function setFormat($format) {
		$this->format = $format;
	}

	public function main() {
		$value = $this->getPropertyValue($this->property);

		$commits = $this->getCommits($value);
		$output = $this->compile($commits);

		$contents = (file_exists($this->file) ? PHP_EOL . PHP_EOL . file_get_contents($this->file) : '');
		file_put_contents($this->file, $output . $contents);
	}

	private function compile(array $commits) {
		$result = '';

		foreach ($commits as $commit) {
			$result .= $this->substitute($this->format, $commit) . PHP_EOL;
		}

		return $result;
	}

	private function substitute($format, array $commit) {
		$value = $format;
		$matches = array();
		$search = array();
		$replace = array();

		if (preg_match_all('/\{\{([^.}]+)(?:\.([^,}]+))?(?:,(\d+))?\}\}/', $format, $matches)) {
			foreach ($matches[0] as $index => $pattern) {
				$name = $matches[1][$index];
				$search[] = $pattern;

				if (!empty($matches[2][$index])) {
					$replaceValue = $this->getCommitValue($matches[2][$index], $commit[$name]);
				} else {
					$replaceValue = $commit[$name];
				}

				if (!empty($matches[3][$index])) {
					if (strlen($replaceValue) > $matches[3][$index]) {
						$replaceValue = substr($replaceValue, 0, $matches[3][$index]);
					} else {
						$replaceValue = str_pad($replaceValue, $matches[3][$index], ' ');
					}
				}

				$replace[] = $replaceValue;
			}

			$value = str_replace($search, $replace, $value);
		}

		return $value;
	}

	private function getCommitValue($key, $data) {
		$value = '';
		$keyPattern = preg_quote($key, '/');
		$matches = array();

		if (preg_match('/^' . $keyPattern . ':(.+)$/im', $data, $matches)) {
			$value = trim($matches[1]);
		}

		return $value;
	}

	private function getCommits($value) {
		$commits = array();

		$rawCommits = explode($this->commitDelimiter, trim($value));
		foreach ($rawCommits as $rawCommit) {
			$commit = array();
			$rawFields = explode($this->fieldDelimiter, trim($rawCommit));
			foreach ($rawFields as $rawField) {
				$rawPair = explode(':', $rawField, 2);
				if (count($rawPair) === 2) {
					$commit[$rawPair[0]] = $rawPair[1];
				}
			}
			if (count($commit)) {
				$commits[] = $commit;
			}
		}

		return $commits;
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