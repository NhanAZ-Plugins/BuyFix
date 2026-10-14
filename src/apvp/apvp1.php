<?php

declare(strict_types=1);

namespace apvp;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Throwable;

final class apvp1 extends PluginBase{

	private Config $config;

	protected function onEnable() : void{
		$this->saveDefaultConfig();
		$this->config = $this->getConfig();
	}

	/**
	 * @param list<string> $args
	 */
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if(strtolower($command->getName()) !== "buyfix"){
			return false;
		}
		if(!($sender instanceof Player)){
			$sender->sendMessage("BuyFix can only be used by a player.");
			return true;
		}

		$item = $sender->getInventory()->getItemInHand();
		if(!($item instanceof Durable) || $item->getDamage() <= 0){
			$sender->sendMessage($this->message("Msg4", "§cHold a damaged tool or armor piece."));
			return true;
		}

		$rawAmount = $this->config->get("amount", 100);
		if(!is_numeric($rawAmount)){
			$this->getLogger()->error("The BuyFix amount must be a positive number with at most two decimal places.");
			$sender->sendMessage($this->message("Msg3", "§cThe repair payment could not be completed."));
			return true;
		}
		$amount = (float) $rawAmount;
		if(!is_finite($amount) || $amount < 0.01 || round($amount, 2) !== $amount){
			$this->getLogger()->error("The BuyFix amount must be at least 0.01 and use no more than two decimal places.");
			$sender->sendMessage($this->message("Msg3", "§cThe repair payment could not be completed."));
			return true;
		}

		$economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		if(!($economy instanceof Plugin) || !method_exists($economy, "myMoney") || !method_exists($economy, "reduceMoney")){
			$sender->sendMessage("§cEconomyAPI is unavailable or incompatible.");
			return true;
		}

		try{
			$balance = $economy->myMoney($sender);
			if((!is_int($balance) && !is_float($balance) && !(is_string($balance) && is_numeric($balance))) || !is_finite((float) $balance)){
				$sender->sendMessage($this->message("Msg3", "§cThe repair payment could not be completed."));
				return true;
			}
			if((float) $balance < $amount){
				$sender->sendMessage($this->message("Msg2", "§cYou do not have enough money to repair this item."));
				return true;
			}

			$repairedItem = RepairTransaction::chargeAndRepair(
				$item,
				static fn() => $economy->reduceMoney($sender, $amount)
			);
		}catch(Throwable $exception){
			$this->getLogger()->error("EconomyAPI failed while processing a BuyFix repair: " . $exception->getMessage());
			$sender->sendMessage($this->message("Msg3", "§cThe repair payment could not be completed."));
			return true;
		}

		if($repairedItem === null){
			$sender->sendMessage($this->message("Msg3", "§cThe repair payment could not be completed."));
			return true;
		}

		$sender->getInventory()->setItemInHand($repairedItem);
		$sender->sendMessage($this->message("Msg1", "§aItem repaired."));
		return true;
	}

	private function message(string $key, string $fallback) : string{
		$value = $this->config->get($key, $fallback);
		return is_string($value) ? $value : $fallback;
	}
}
