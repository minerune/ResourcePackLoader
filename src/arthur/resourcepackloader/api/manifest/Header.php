<?php

declare(strict_types=1);

namespace arthur\resourcepackloader\api\manifest;

class Header{

	public function __construct(private string $description, private string $name, private string $uuid, private Version $version){ }

	public function getDescription() : string{
		return $this->description;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getUuid() : string{
		return $this->uuid;
	}

	public function getVersion() : Version{
		return $this->version;
	}

	public function setDescription(string $description) : void{
		$this->description = $description;
	}

	public function setName(string $name) : void{
		$this->name = $name;
	}

	public function setUuid(string $uuid) : void{
		$this->uuid = $uuid;
	}

	public function setVersion(Version $version) : void{
		$this->version = $version;
	}
}