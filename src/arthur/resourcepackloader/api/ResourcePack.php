<?php

declare(strict_types=1);

namespace arthur\resourcepackloader\api;

use LogicException;
use pocketmine\resourcepacks\ResourcePack as PMResourcePack;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use ReflectionClass;
use ReflectionException;

abstract class ResourcePack{

	public static function register(string $resourcePackPath) : void{
		$resourcePackManager = Server::getInstance()->getResourcePackManager();
		$newResourcePack = new ZippedResourcePack($resourcePackPath);
		try{
			$resourcePackManagerReflection = new ReflectionClass(get_class($resourcePackManager));
			$resourcePacksProperty = $resourcePackManagerReflection->getProperty("resourcePacks");
			$resourcePacksProperty->setAccessible(true);
			$resourcePacksValue = $resourcePacksProperty->getValue($resourcePackManager);
			$resourcePacksValue[] = $newResourcePack;
			$resourcePacksProperty->setValue($resourcePackManager, $resourcePacksValue);
			$uuidListProperty = $resourcePackManagerReflection->getProperty("uuidList");
			$uuidListProperty->setAccessible(true);
			$uuidListValue = $uuidListProperty->getValue($resourcePackManager);
			$uuidListValue[strtolower($newResourcePack->getPackId())] = $newResourcePack;
			$uuidListProperty->setValue($resourcePackManager, $uuidListValue);
		}catch(ReflectionException){
			throw new LogicException("Caught ReflectionException.");
		}
	}

	public static function unregister(PMResourcePack $resourcePack) : void{
		$resourcePackManager = Server::getInstance()->getResourcePackManager();
		try{
			$resourcePackManagerReflection = new ReflectionClass(get_class($resourcePackManager));
			$resourcePacksProperty = $resourcePackManagerReflection->getProperty("resourcePacks");
			$resourcePacksProperty->setAccessible(true);
			$resourcePacksValue = $resourcePacksProperty->getValue($resourcePackManager);
			$unregisterKeys = [];
			foreach($resourcePacksValue as $key => $item){
				if($item === $resourcePack){
					$unregisterKeys[] = $key;
				}
			}
			foreach($unregisterKeys as $unregisterKey){
				unset($resourcePacksValue[$unregisterKey]);
			}
			$resourcePacksProperty->setValue($resourcePackManager, $resourcePacksValue);
			$uuidListProperty = $resourcePackManagerReflection->getProperty("uuidList");
			$uuidListProperty->setAccessible(true);
			$uuidListValue = $uuidListProperty->getValue($resourcePackManager);
			$unregisterKeys = [];
			foreach($uuidListValue as $key => $item){
				if($item === $resourcePack){
					$unregisterKeys[] = $key;
				}
			}
			foreach($unregisterKeys as $unregisterKey){
				unset($uuidListValue[$unregisterKey]);
			}
			$uuidListProperty->setValue($resourcePackManager, $uuidListValue);
		}catch(ReflectionException){
			throw new LogicException("Caught ReflectionException.");
		}
	}

	/**
	 * @return PMResourcePack[] index => PMResourcePack
	 */
	public static function getPackList() : array{
		$resourcePackManager = Server::getInstance()->getResourcePackManager();
		try{
			$resourcePackManagerReflection = new ReflectionClass(get_class($resourcePackManager));
			$resourcePacksProperty = $resourcePackManagerReflection->getProperty("resourcePacks");
			$resourcePacksProperty->setAccessible(true);
			return $resourcePacksProperty->getValue($resourcePackManager);
		}catch(ReflectionException){
			throw new LogicException("Caught ReflectionException.");
		}
	}

	/**
	 * @return PMResourcePack[] uuid => PMResourcePack
	 */
	public static function getUuidList() : array{
		$resourcePackManager = Server::getInstance()->getResourcePackManager();
		try{
			$resourcePackManagerReflection = new ReflectionClass(get_class($resourcePackManager));
			$uuidListProperty = $resourcePackManagerReflection->getProperty("uuidList");
			$uuidListProperty->setAccessible(true);
			$uuidListValue = $uuidListProperty->getValue($resourcePackManager);
			return $uuidListValue;
		}catch(ReflectionException){
			throw new LogicException("Caught ReflectionException.");
		}
	}

	public static function getPackByIndex(int $index) : ?PMResourcePack{
		return self::getPackList()[$index] ?? null;
	}

	public static function getPackByUuid(string $uuid) : ?PMResourcePack{
		return self::getUuidList()[$uuid] ?? null;
	}
}