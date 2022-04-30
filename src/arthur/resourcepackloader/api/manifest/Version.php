<?php

declare(strict_types=1);

namespace arthur\resourcepackloader\api\manifest;

class Version{

	public function __construct(private int $first, private int $second, private int $third){ }

	public function toArray() : array{
		return [$this->first, $this->second, $this->third];
	}
}