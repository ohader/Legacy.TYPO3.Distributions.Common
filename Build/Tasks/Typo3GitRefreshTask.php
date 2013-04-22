<?php
class Typo3GitRefreshTask extends GitBaseTask {
	/**
	 * @var string
	 */
	private $expected = null;

	/**
	 * @param string
	 */
	public function setExpected($expected) {
		$this->expected = trim($expected);
	}

	public function main() {
        if (null === $this->getRepository()) {
            throw new BuildException('Parameter repository is required.');
        }

		if (is_null($this->expected)) {
			throw new BuildException('Parameter expected is required.');
		}

		$count = 0;
		$client = $this->getGitClient(false, $this->getRepository());
		$pullCommand = $client->getCommand('pull');
		$logCommand = $client->getCommand('log -1 --pretty=format:%s');

		while (++$count < 30) {
			$this->log('Refreshing #' . $count . ' and waiting for "' . $this->expected . '"...');
			sleep(3);
			$pullCommand->execute();
			if ($this->expected === trim($logCommand->execute())) {
				$this->log('Refreshing done.');
				break;
			}
		}
	}
}
?>