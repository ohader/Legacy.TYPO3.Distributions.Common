<?php
class Typo3GitInfoTask extends GitBaseTask {
	const TYPE_Snapshot = 'snapshot';
	const TYPE_Regular = 'regular';
	const TYPE_Alpha = 'alpha';
	const TYPE_Beta = 'beta';
	const TYPE_RC = 'rc';
	const TAG_Prefix = 'TYPO3_';
	const TAG_Delimiter = '-';
	const VERSION_Delimiter = '.';

	/**
	 * @var string
	 */
	private $branch;

	/**
	 * @var string
	 */
	private $property;

	/**
	 * @var string
	 */
	private $type = self::TYPE_Regular;

	public function setBranch($branch) {
		$this->branch = $branch;
	}

	public function setProperty($property) {
		$this->property = $property;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function main() {
        if (null === $this->getRepository()) {
            throw new BuildException('Parameter repository is required.');
        }

		if (is_null($this->branch)) {
			throw new BuildException('Parameter branch is required.');
		}

		if (is_null($this->property)) {
			throw new BuildException('Parameter property is required.');
		}

		$info = $this->getInfo(
			$this->getGitTags(),
			$this->getGitBranches()
		);

		foreach ($info as $key => $value) {
			$this->project->setProperty($this->property . '.' . $key, $value);
		}
	}

	private function getGitTags() {
		$client = $this->getGitClient(false, $this->getRepository());
		$command = $client->getCommand('tag');
		$command->setOption('l', TRUE);
		$tags = $this->explode($command->execute());
		return $tags;
	}

	private function getGitBranches() {
		$client = $this->getGitClient(false, $this->getRepository());
		$command = $client->getCommand('branch');
		$command->setOption('r', TRUE);
		$branches = $this->explode($command->execute());
		return $branches;
	}

	private function explode($string) {
		$string = preg_replace('/^(\*|\s+)/m', '', $string);
		$elements = explode(PHP_EOL, $string);
		return $elements;
	}

	private function getCurrentTag(array $tags, $prefix) {
		// TYPO3_4-5_ or TYPO3_4-6-0alpha
		$prefixPattern = preg_quote($prefix, '/');
		$pattern = '/^' . $prefixPattern . '(\d+)$/';

		$max = NULL;
		$matches = array();
		$currentTag = NULL;

		foreach ($tags as $tag) {
			if (preg_match($pattern, $tag, $matches)) {
				if (is_null($max) || $matches[1] > $max) {
					$max = $matches[1];
					$currentTag = $tag;
				}
			}
		}

		return $currentTag;
	}

	private function incrementTag($tag, $delimiter = self::TAG_Delimiter) {
		$parts = explode($delimiter, $tag);
		$parts[count($parts)-1]++;
		return implode($delimiter, $parts);
	}

	private function convertToVersion($tag) {
		$version = NULL;

		if (is_null($tag) === FALSE) {
			$rawTag = preg_replace('/^' . self::TAG_Prefix . '/', '', $tag);
			$parts = explode(self::TAG_Delimiter, $rawTag);
			$version = implode(self::VERSION_Delimiter, $parts);
		}

		return $version;
	}

	private function convertToTag($version) {
		$tag = NULL;

		if (is_null($version) === FALSE) {
			$parts = explode(self::VERSION_Delimiter, $version);
			$tag = self::TAG_Prefix . implode(self::TAG_Delimiter, $parts);
		}

		return $tag;
	}

	private function getInfo(array $tags, array $branches) {
		$info = array(
			'currentVersion' => NULL,
			'currentTag' => NULL,
			'nextVersion' => NULL,
			'nextTag' => NULL,
			'successorVersion' => NULL,
			'branchName' => 'master',
		);

		$currentRegularTag = $this->getCurrentTag($tags, $this->branch . self::TAG_Delimiter);

		if ($this->type === self::TYPE_Regular) {
			if (is_null($currentRegularTag)) {
				$info['nextTag'] = $this->branch . self::TAG_Delimiter . '0';
			} else {
				$info['currentTag'] = $currentRegularTag;
				$info['nextTag'] = $this->incrementTag($info['currentTag']);
			}
			$info['successorVersion'] = $this->convertToVersion(
				$this->incrementTag($info['nextTag'])
			);
		} elseif ($this->type !== self::TYPE_Snapshot) {
			if (is_null($currentRegularTag)) {
				// TYPO3_4-6-0alpha
				$irregularPrefix = $this->branch . self::TAG_Delimiter . '0' . $this->type;
			} else {
				// TYPO3_4-5-3alpha - why not? ;-)
				$irregularPrefix = $currentRegularTag . $this->type;
			}

			$info['currentTag'] = $this->getCurrentTag($tags, $irregularPrefix);

			if (is_null($info['currentTag'])) {
				$info['nextTag'] = $irregularPrefix . '1';
			} else {
				$info['nextTag'] = $this->incrementTag($info['currentTag'], $this->type);
			}
			$info['successorVersion'] = $this->convertToVersion($this->branch);
		}

		if (in_array('origin/' . $this->branch, $branches)) {
			$info['branchName'] = $this->branch;
		}

		$info['currentVersion'] = $this->convertToVersion($info['currentTag']);
		$info['nextVersion'] = $this->convertToVersion($info['nextTag']);

		return $info;
	}
}
?>