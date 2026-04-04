<?php

namespace apvp;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\Server;

class apvp1 extends PluginBase implements Listener {
	public $EconomyAPI;
	private $config;
	public function onEnable() {
		if (!is_dir($this->getDataFolder())) {
			@mkdir($this->getDataFolder());
		}
		$this->saveResource("config.yml");
		$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info(TextFormat::GREEN . "เปิดทำงานปลั๊กอิน fix");
		$this->EconomyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	}
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		if ($cmd == "buyfix") {
			$money = $this->EconomyAPI->myMoney($sender);
			$item = $sender->getInventory()->getItemInHand();
			$item->setDamage(0);
			$amount = $this->config->get("amount");;
			if ($money >= $amount) {
				$this->EconomyAPI->reduceMoney($sender, $amount);
				$sender->sendMessage($this->config->get("Msg1"));
				$sender->getInventory()->setItemInHand($item);
			} else {
				$sender->sendMessage($this->config->get("Msg2"));
			}
		}
	}
}
