<?php

declare(strict_types=1);

namespace arthur\resourcepackloader\api\generator;

use arthur\resourcepackloader\api\manifest\Manifest;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use ZipArchive;

class ResourcePackGenerator{

	private string|null $packIcon = null;

	/** @var string[] ResourcePackPath => PluginResourceRelativePath */
	private array $files = [];

	public function __construct(private PluginBase $plugin, private Manifest $manifest){ }

	protected function getPlugin() : Plugin{
		return $this->plugin;
	}

	public function getManifest() : Manifest{
		return $this->manifest;
	}

	public function getPackIcon() : ?string{
		return $this->packIcon;
	}

	public function setPackIcon(?string $resourcePath) : void{
		$this->packIcon = $resourcePath;
	}

	/**
	 * NOTE: This function just adds the file path.
	 * Therefore, the stream of the file is linked at the generation timing.
	 *
	 * @param string $resourcePath PluginResourceRelativePath
	 * @param string $packPath ResourcePackPath
	 */
	public function addFile(string $resourcePath, string $packPath) : void{
		$this->files[$packPath] = $resourcePath;
	}

	public function generate(string $generateFilePath, bool $overwrite = true) : void{
		$zip = new ZipArchive();

		$flags = ZipArchive::CREATE;
		if($overwrite) $flags |= ZipArchive::OVERWRITE;

		if($zip->open($generateFilePath, $flags) !== true){
			throw new GenerateException("Failed to open zip.");
		}
		$this->addFileFromString($zip, "manifest.json", json_encode($this->manifest, JSON_PRETTY_PRINT));
		if($this->packIcon !== null){
			$this->addFileFromPluginResource($zip, $this->packIcon, "pack_icon.png");
		}
		foreach($this->files as $resourcePackPath => $pluginResourcePath){
			$this->addFileFromPluginResource($zip, $pluginResourcePath, $resourcePackPath);
		}
		if(!$zip->close()) throw new GenerateException("Failed to close the zip.");
	}

	private function addFileFromString(ZipArchive $zip, string $resourcePackPath, string $contents) : void{
		if(!$zip->addFromString($resourcePackPath, $contents)){
			throw new GenerateException("Failed to file addition to zip.");
		}
	}

	private function addFileFromPluginResource(ZipArchive $zip, string $pluginResourcePath, string $resourcePackPath) : void{
		if(($resource = $this->plugin->getResource($pluginResourcePath)) === null){
			throw new GenerateException("Failed to load resource: " . $pluginResourcePath);
		}
		$contents = stream_get_contents($resource);
		if($contents === false) throw new GenerateException("Failed to get the content from the stream.");
		$this->addFileFromString($zip, $resourcePackPath, $contents);
		fclose($resource);
	}
}