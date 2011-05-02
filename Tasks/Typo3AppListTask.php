<?php
require_once 'phing/Task.php';
include_once 'phing/types/FileSet.php';

/**
 * Task that created the APP-LIST.xml file.
 */
class Typo3AppListTask extends Task {
	private $listElements = array();

	private $file = NULL;

	private $filesets = array();

	public function setFile($file) {
		$this->file = $file;
	}

	/**
	 * Nested creator, adds a set of files (nested fileset attribute).
	 */
	public function createFileSet() {
		$num = array_push($this->filesets, new FileSet());
		return $this->filesets[$num-1];
	}

	/**
	 * Execute the touch operation.
	 * @return void
	 */
	public function main() {
		$list = '';

		if ($this->file === NULL) {
			throw new BuildException('Specify an output file.', $this->location);
		}

		if (empty($this->filesets)) {
			throw new BuildException('Specify a fileset.', $this->location);
		}

		foreach($this->filesets as $fs) {
			$ds = $fs->getDirectoryScanner($this->project);
			$fromDir = $fs->getDir($this->project);
			$srcFiles = $ds->getIncludedFiles();

			foreach ($srcFiles as $srcFile) {
				$filePath = rtrim($fromDir, '/') . '/' . $srcFile;
				$listElement = array(
					'name' => $srcFile,
					'size' => filesize($filePath),
					'sha256' => hash_file('sha256', $filePath),
				);
				$this->listElements[] = $listElement;
				$list .= "\t" . '<file ' . $this->__toList($listElement) . ' />' . PHP_EOL;
			}
		}

		$this->write($list);
	}

	protected function write($list) {
		$list = '<?xml version="1.0"?>' . PHP_EOL .
			'<files xmlns="http://apstandard.com/ns/1">' . PHP_EOL .
			$list . '</files>' . PHP_EOL;

		$handle = fopen($this->file, "w");
		fwrite($handle, $list);
		fclose($handle);
	}

	protected function __toList(array $array) {
		$listElements = array();

		foreach ($array as $key => $value) {
			$listElements[] = $key . '="' . $value . '"';
		}

		return implode(' ', $listElements);
	}
}


