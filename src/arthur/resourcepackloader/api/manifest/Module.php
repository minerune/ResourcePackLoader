<?php

declare(strict_types=1);

namespace arthur\resourcepackloader\api\manifest;

class Module{

	public const TYPE_RESOURCES = "resources";

	public function __construct(private string $description, private string $type, private string $uuid, private Version $version){ }

	public function getDescription() : string{
		return $this->description;
	}

	public function getType() : string{
		return $this->type;
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

	public function setType(string $type) : void{
		$this->type = $type;
	}

	public function setUuid(string $uuid) : void{
		$this->uuid = $uuid;
	}

	public function setVersion(Version $version) : void{
		$this->version = $version;
	}
}