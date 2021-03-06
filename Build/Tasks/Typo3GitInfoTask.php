<?php
class Typo3GitInfoTask extends GitBaseTask {
	const TYPE_Snapshot = 'snapshot';
	const TYPE_Regular = 'regular';
	const TYPE_Alpha = 'alpha';
	const TYPE_Beta = 'beta';
	const TYPE_RC = 'rc';
	const TAG_Prefix = 'TYPO3_';
	const TAG_Delimiter = '-';
	const BRANCH_Pattern = 'TYPO3_\d+-\d+';
	const VERSION_Pattern = '\d+-\d+';
	const VERSION_Delimiter = '.';
	const COMPOSER_BaseVersion = '(\d+\.\d+\.\d+)([^\d].+)';
	const COMPOSER_BaseVersionDelimiter = '-';

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

		$tags = $this->getGitTags();
		$branches = $this->getGitBranches();

		$info = $this->getInfo(
			$tags,
			$branches,
			$this->getStableBranches($tags, $branches)
		);

		foreach ($info as $key => $value) {
			$this->project->setProperty($this->property . '.' . $key, $value);
		}
	}

	private function getGitTags() {
		$client = $this->getGitClient(false, $this->getRepository());
		$command = $client->getCommand('ls-remote');
		$command->setOption('tags', TRUE);
		$result = preg_replace('#^[^/]*refs/tags/(' . self::TAG_Prefix . '.+)$#m', '$1', $command->execute());
		$tags = $this->explode($result);
		sort($tags);
		return $tags;
	}

	private function getGitBranches() {
		$client = $this->getGitClient(false, $this->getRepository());
		$command = $client->getCommand('ls-remote');
		$command->setOption('heads', TRUE);
		$result = preg_replace('#^[^/]*refs/heads/(' . self::BRANCH_Pattern . ')$#m', '$1', $command->execute());
		$branches = $this->explode($result);
		sort($branches);
		return $branches;
	}

	/**
	 * @param array $tags
	 * @param array $branches
	 * @return array
	 */
	private function getStableBranches(array $tags, array $branches) {
		$stableBranches = array();

		foreach ($branches as $branch) {
			if (in_array($branch . '-0', $tags)) {
				$stableBranches[] = $branch;
			}
		}

		return $stableBranches;
	}

	private function explode($string) {
		$string = trim($string);
		$elements = explode(PHP_EOL, $string);

		foreach ($elements as $index => $element) {
			if (!preg_match('#^' . self::TAG_Prefix . self::VERSION_Pattern . '#', $element)) {
				unset($elements[$index]);
			}
		}

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

	private function getCurrentSpecialTag(array $tags, $prefix, $type) {
		$tag = NULL;
		$specialTypes = array();

		if ($type === self::TYPE_RC) {
			$specialTypes = array(self::TYPE_RC, self::TYPE_Beta, self::TYPE_Alpha);
		} elseif ($type === self::TYPE_Beta) {
			$specialTypes = array(self::TYPE_Beta, self::TYPE_Alpha);
		} elseif ($type === self::TYPE_Beta) {
			$specialTypes = array(self::TYPE_Alpha);
		}

		foreach ($specialTypes as $specialType) {
			$tag = $this->getCurrentTag($tags, $prefix . $specialType);
			if (is_null($tag) === FALSE) {
				break;
			}
		}

		return $tag;
	}

	private function increment($ref, $delimiter = self::TAG_Delimiter) {
		$parts = explode($delimiter, $ref);
		$parts[count($parts)-1]++;
		return implode($delimiter, $parts);
	}

	private function decrement($ref, $delimiter = self::TAG_Delimiter) {
		$parts = explode($delimiter, $ref);
		$parts[count($parts)-1]--;
		return implode($delimiter, $parts);
	}

	private function decrementBranch(array $branches, $branch) {
		$index = array_search($branch, $branches);
		$last = count($branches) - 1;

		if (is_integer($index) && $index > 0) {
			return $branches[$index - 1];
		} elseif ($last >= 0 && strcmp($branch, $branches[$last])) {
			return $branches[$last];
		} else {
			return $this->decrement($branch);
		}
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

	private function convertToComposer($tag) {
		$version = $this->convertToVersion($tag);

		if (is_null($version) === FALSE) {
			$version = preg_replace(
				'/^' . self::COMPOSER_BaseVersion . '$/',
				'${1}' . self::COMPOSER_BaseVersionDelimiter . '${2}',
				$version
			);
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

	private function getInfo(array $tags, array $branches, array $stableBranches) {
		$info = array(
			'currentVersion' => NULL,
			'currentTag' => NULL,
			'nextVersion' => NULL,
			'nextTag' => NULL,
			'successorVersion' => NULL,
			'lastReference' => NULL,
			'branchName' => 'master',
			'isOutdatedBranch' => FALSE,
		);

		$currentRegularTag = $this->getCurrentTag($tags, $this->branch . self::TAG_Delimiter);

		if ($this->type === self::TYPE_Regular) {
			if (is_null($currentRegularTag)) {
				$info['nextTag'] = $this->branch . self::TAG_Delimiter . '0';
				$info['lastReference'] = $this->decrementBranch($branches, $this->branch) . self::TAG_Delimiter . '0';
			} else {
				$info['currentTag'] = $currentRegularTag;
				$info['nextTag'] = $this->increment($info['currentTag']);
				$info['lastReference'] = $info['currentTag'];
			}

			$info['successorVersion'] = $this->convertToVersion(
				$this->increment($info['nextTag'])
			);
		} elseif ($this->type === self::TYPE_Snapshot) {
			if (is_null($currentRegularTag)) {
				$info['lastReference'] = $this->decrementBranch($branches, $this->branch) . self::TAG_Delimiter . '0';
			} else {
				$info['lastReference'] = $currentRegularTag;
			}
		} else {
			if (is_null($currentRegularTag)) {
				// TYPO3_4-6-0alpha
				$irregularPrefix = $this->branch . self::TAG_Delimiter . '0';
			} else {
				// TYPO3_4-5-3alpha - why not? ;-)
				$irregularPrefix = $this->increment($currentRegularTag);
			}

			$info['currentTag'] = $this->getCurrentTag($tags, $irregularPrefix . $this->type);

			if (is_null($info['currentTag'])) {
				$info['nextTag'] = $irregularPrefix . $this->type . '1';
				$lastSpecialTag = $this->getCurrentSpecialTag($tags, $irregularPrefix, $this->type);

				if (is_null($lastSpecialTag) === FALSE) {
					$info['lastReference'] = $lastSpecialTag;
				} elseif (is_null($currentRegularTag) === FALSE){
					$info['lastReference'] = $currentRegularTag;
				} else {
					$info['lastReference'] = $this->decrementBranch($branches, $this->branch) . self::TAG_Delimiter . '0';
				}
			} else {
				$info['nextTag'] = $this->increment($info['currentTag'], $this->type);
				$info['lastReference'] = $info['currentTag'];
			}

			$info['successorVersion'] = $this->convertToVersion($this->branch);
		}

		if (in_array($this->branch, $branches)) {
			$info['branchName'] = $this->branch;
		}

		if (in_array($this->branch, $stableBranches)) {
			$info['isOutdatedBranch'] = (array_search($this->branch, $stableBranches) < count($stableBranches) - 1);
		}

		$info['currentVersion'] = $this->convertToVersion($info['currentTag']);
		$info['nextVersion'] = $this->convertToVersion($info['nextTag']);
		$info['nextComposerVersion'] = $this->convertToComposer($info['nextTag']);

		return $info;
	}
}
?>