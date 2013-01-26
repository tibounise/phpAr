<?php
/* Copyright or © or Copr. TiBounise (2013)

contact@tibounise.com

This software is a computer program whose purpose is to [describe
functionalities and technical features of your software].

This software is governed by the CeCILL-C license under French law and
abiding by the rules of distribution of free software.  You can  use, 
modify and/ or redistribute the software under the terms of the CeCILL-C
license as circulated by CEA, CNRS and INRIA at the following URL
"http://www.cecill.info". 

As a counterpart to the access to the source code and  rights to copy,
modify and redistribute granted by the license, users are provided only
with a limited warranty  and the software's author,  the holder of the
economic rights,  and the successive licensors  have only  limited
liability. 

In this respect, the user's attention is drawn to the risks associated
with loading,  using,  modifying and/or developing or reproducing the
software by the user in light of its specific status of free software,
that may mean  that it is complicated to manipulate,  and  that  also
therefore means  that it is reserved for developers  and  experienced
professionals having in-depth computer knowledge. Users are therefore
encouraged to load and test the software's suitability as regards their
requirements in conditions enabling the security of their systems and/or 
data to be ensured and,  more generally, to use and operate it in the 
same conditions as regards security. 

The fact that you are presently reading this means that you have had
knowledge of the CeCILL-C license and that you accept its terms. */

/**
 * 
 * @author TiBounise <contact@tibounise.com>
 * @version 1.1b
 */
class phpAr {
	private $fileHandler;
	private $filesize;

	/**
	 * Constructor - import the file
	 * 
	 * @access public
	 * @param string $file File adress
	 */
	public function __construct($file) {
		$this->fileHandler = fopen($file,'rb');
		if (!$this->fileHandler) {
			throw new Exception('The archive can\'t be opened');
		} elseif (!preg_match('#^\!<arch>#',fread($this->fileHandler, 8))) {
			throw new Exception('Invalid archive file');
		}
		$this->filesize = filesize($file);
	}

	/**
	 * Destructor - closes the file handler
	 * 
	 * @access public
	 */
	public function __destruct() {
		fclose($this->fileHandler);
	}

	/**
	 * Lists the files in the archive
	 * 
	 * @access public
	 * @return array The list of the files in the archive
	 */
	public function listfiles() {
		$list_files = array();
		fseek($this->fileHandler,8);
		while (ftell($this->fileHandler) < $this->filesize - 1) {
			$list_files[] = trim(fread($this->fileHandler, 16));
			fread($this->fileHandler, 32);
			$size = trim(fread($this->fileHandler, 10));
			fseek($this->fileHandler, 2 + ftell($this->fileHandler) + $size);
		}
		return $list_files;
	}

	/**
	 * Gets a file in the archive
	 * 
	 * @access public
	 * @param string $name Name of the file
	 * @return ArrayObject
	 */
	public function getfile($name) {
		$file = new ArrayObject(array(),ArrayObject::STD_PROP_LIST);
		fseek($this->fileHandler,8);
		while (ftell($this->fileHandler) < $this->filesize-1) {
			$filename = trim(fread($this->fileHandler, 16));
			if ($filename == $name) {
				$file->timestamp = trim(fread($this->fileHandler, 12));
				$file->owner_id = trim(fread($this->fileHandler, 6));
				$file->group_id = trim(fread($this->fileHandler, 6));
				$file->mode = trim(fread($this->fileHandler, 8));
				$file->size = trim(fread($this->fileHandler, 10));
				fread($this->fileHandler, 2);
				$file->content = fread($this->fileHandler, $file->size);
			} else {
				fread($this->fileHandler, 32);
				$size = trim(fread($this->fileHandler, 10));
				fseek($this->fileHandler, 2 + ftell($this->fileHandler) + $size);
			}
		}
		return $file;
	} 
}