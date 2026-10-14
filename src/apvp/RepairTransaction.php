<?php

declare(strict_types=1);

namespace apvp;

use pocketmine\item\Durable;
use pocketmine\item\Item;

final class RepairTransaction{

	/**
	 * @param callable(): mixed $charge
	 */
	public static function chargeAndRepair(Item $item, callable $charge) : ?Item{
		if(!$item instanceof Durable || $item->getDamage() <= 0){
			return null;
		}
		if($charge() !== 1){
			return null;
		}
		return $item->setDamage(0);
	}
}
