<?php
class Typo3ExecTask extends ExecTask {
	/**
	 * If the execute command returns an error
	 * there will be as much retries as defined here.
	 * @var integer
	 */
	protected $retry = 0;

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
		$retry = 0;
		$return = $this->execute();

		while ($return != 0 && $retry++ < $this->retry) {
			$this->log('Retry #' . $retry . ' on previous failure...', Project::MSG_INFO);
			$return = $this->execute();
		}
	}
}
?>