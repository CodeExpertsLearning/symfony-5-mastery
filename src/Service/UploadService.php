<?php
namespace App\Service;

class UploadService
{
	private $folder;

	public function __construct(string $uploadDir)
	{
		$this->folder = $uploadDir;
	}

	public function upload()
	{
		return $this->folder;
	}
}