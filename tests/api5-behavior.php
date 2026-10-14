<?php

declare(strict_types=1);

namespace pocketmine\item;

class Item{
}

interface Durable{
	public function getDamage() : int;

	public function setDamage(int $damage) : Item;
}

namespace BuyFixTests;

use apvp\RepairTransaction;
use pocketmine\item\Durable;
use pocketmine\item\Item;

require_once __DIR__ . "/../src/apvp/RepairTransaction.php";

final class TestDurableItem extends Item implements Durable{
	public function __construct(private int $damage){
	}

	public function getDamage() : int{
		return $this->damage;
	}

	public function setDamage(int $damage) : Item{
		$this->damage = $damage;
		return $this;
	}
}

function expect(bool $condition, string $message) : void{
	if(!$condition){
		throw new \RuntimeException($message);
	}
}

$damaged = new TestDurableItem(27);
$chargeCalls = 0;
$repaired = RepairTransaction::chargeAndRepair($damaged, static function() use (&$chargeCalls) : int{
	++$chargeCalls;
	return 1;
});
expect($repaired === $damaged && $damaged->getDamage() === 0, "A successful charge must repair the item.");
expect($chargeCalls === 1, "A damaged item must be charged once.");

$cancelled = new TestDurableItem(12);
$failed = RepairTransaction::chargeAndRepair($cancelled, static fn() : int => -2);
expect($failed === null && $cancelled->getDamage() === 12, "A rejected charge must preserve item damage.");

$undamaged = new TestDurableItem(0);
$undamagedCalls = 0;
expect(RepairTransaction::chargeAndRepair($undamaged, static function() use (&$undamagedCalls) : int{
	++$undamagedCalls;
	return 1;
}) === null, "An undamaged item must not be charged.");
expect($undamagedCalls === 0, "An undamaged item must skip the economy call.");

$ordinaryItem = new Item();
$ordinaryCalls = 0;
expect(RepairTransaction::chargeAndRepair($ordinaryItem, static function() use (&$ordinaryCalls) : int{
	++$ordinaryCalls;
	return 1;
}) === null, "A non-durable item cannot be repaired.");
expect($ordinaryCalls === 0, "A non-durable item must skip the economy call.");

fwrite(STDOUT, "BuyFix API 5 transaction checks passed.\n");
