<?php
class Typo3FileHashTask extends Task {
	const HASH_SHA1 = 'sha1';
	const HASH_MD5 = 'md5';
	const SEPARATOR = '  ';

	private $dir = null;
	private $method = self::HASH_MD5;
	private $returnProperty = null;

	public function setDir($dir) {
		$dir = rtrim($dir, '/') . '/';
		if (!is_dir($dir)) {
			throw new BuildException('Directory "' . $dir . '" does not exist.', $this->location);
		}
		$this->dir = $dir;
	}

	public function setMethod($method) {
		if ($method !== self::HASH_SHA1 && $method !== self::HASH_MD5) {
			throw new BuildException('Hash method "' . $method . '" is not supported.', $this->location);
		}
		$this->methos = $method;
	}

	public function setReturnProperty($returnProperty) {
		$this->returnProperty = $returnProperty;
	}

	public function main() {
		if ($this->dir === null) {
			throw new BuildException('No directory given.', $this->location);
		}

		$result = null;

		foreach (glob($this->dir . '*') as $fileName) {
			$result .= ($this->method === self::HASH_MD5 ? md5_file($fileName) : sha1_file($fileName)) . self::SEPARATOR . basename($fileName) . PHP_EOL;
		}

		if ($this->returnProperty !== null && $result !== null) {
			$result = (strtoupper($this->method)) . ' file hashes:' . PHP_EOL . $result;
			$this->project->setProperty($this->returnProperty, $result);
		}
	} 
}
?>