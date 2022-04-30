<?php

declare(strict_types=1);

namespace arthur\resourcepackloader\api\manifest;

use JsonSerializable;

class Manifest implements JsonSerializable{

	public const FORMAT_VERSION = 1;

	public function __construct(private Header $header, private array $modules){ }

	public function getHeader() : Header{
		return $this->header;
	}

	/**
	 * @return Module[]
	 */
	public function getModules() : array{
		return $this->modules;
	}

	public function addModule(Module $module) : void{
		$this->modules[] = $module;
	}

	public function removeModule(Module $target) : void{
		foreach($this->modules as $key => $module){
			if($module === $target){
				unset($this->modules[$key]);
			}
		}
	}

	public function removeModuleById(int $id) : void{
		unset($this->modules[$id]);
	}

	public function jsonSerialize() : array{
		$manifest = [
			"format_version" => self::FORMAT_VERSION,
			"header" => [
				"description" => $this->header->getDescription(),
				"name" => $this->header->getName(),
				"uuid" => $this->header->getUuid(),
				"version" => $this->header->getVersion()->toArray(),
			],
			"modules" => [],
		];
		foreach($this->modules as $module){
			$manifest["modules"][] = [
				"description" => $module->getDescription(),
				"type" => $module->getType(),
				"uuid" => $module->getUuid(),
				"version" => $module->getVersion()->toArray(),
			];
		}
		return $manifest;
	}
}