# BuyFix

BuyFix is a legacy PocketMine-MP plugin snapshot intended to charge a player to repair the item in their hand with `/buyfix`. Its manifest declares plugin API 2.0.0, credits Niolz Gamer as author and records a Vietnamese edit by Ghast Noob.

The source looks up an external plugin named `EconomyAPI` and calls its money methods, but `plugin.yml` does not declare that dependency. The default fee and messages are in [the bundled configuration](resources/config.yml). The command compares a `Command` object with the string `buyfix`, does not verify that the sender is a player before accessing an inventory, and ignores the result from `reduceMoney()` before storing the repaired item. Do not deploy or use it with real player balances.

This snapshot uses the historical `pocketmine\Player` class and item damage API. It has not been ported or tested on Axolotl-PM 5.x. There is no current Axolotl-PM 5 build, runtime test or stable release.

The repository has no LICENSE file or documented grant to redistribute a modified build. The original source, author credits and translated messages remain unchanged. A safe repair or modern port cannot be published until source rights are verified; it would also need a declared economy dependency, checked transaction result, and compatibility and behavior tests.

See [plugin.yml](plugin.yml) and [the historical source](src/apvp/apvp1.php) for the exact implementation.
