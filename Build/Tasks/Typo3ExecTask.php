<?php
class Typo3ExecTask extends ExecTask {
	/**
	 * If the execute command returns an error
	 * there will be as much retries as defined here.
	 * @var integer
	 */
	protected $retry = 0;

	/**
	 * @var integer
	 */
	protected $retryCount = 0;

	/**
	 * @param integer $retry
	 */
	public function setRetry($retry) {
		$this->retry = (int) $retry;
	}

	/**
	 * Main method: wraps execute() command.
	 * @return void
	 */
	public function main() {
		if (!$this->isApplicable()) {
			return;
		}

		$this->prepare();
		$this->buildCommand();
		list($return, $output) = $this->executeCommand();

		while ($return != 0 && $this->retryCount++ < $this->retry) {
			$this->log('(Retry #' . $this->retryCount . ') Executing command: ' . $this->command);
			list($return, $output) = $this->executeCommand();
		}

		$this->cleanup($return, $output);
	}

	/**
	 * Executes retry calls. The execute() method modifies class members,
	 * that is exactly the reason for this additional method...
	 *
	 * @return integer Return code from execution.
	 * @deprecated Not required anymore
	 */
	protected function retry() {
		if ($this->dir !== null) {
			if ($this->dir->isDirectory()) {
				$currdir = getcwd();
				@chdir($this->dir->getPath());
			} else {
				throw new BuildException("Can't chdir to:" . $this->dir->__toString());
			}
		}

		$this->log('(Retry #' . $this->retryCount . ') Executing command: ' . $this->command);

		if ($this->passthru) {
			passthru($this->command, $return);
		} else {
			exec($this->command, $output, $return);
		}

		if ($this->dir !== null) {
			@chdir($currdir);
		}

		foreach($output as $line) {
			$this->log($line,  ($this->logOutput ? Project::MSG_INFO : Project::MSG_VERBOSE));
		}

		if ($this->returnProperty) {
			$this->project->setProperty($this->returnProperty, $return);
		}

		if ($this->outputProperty) {
			$this->project->setProperty($this->outputProperty, implode("\n", $output));
		}

		return $return;
	}
}
?>