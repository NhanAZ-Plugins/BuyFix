# BuyFix

BuyFix is a legacy PocketMine-MP plugin snapshot intended to charge a player to repair the item in their hand with `/buyfix`. Its manifest declares plugin API 2.0.0, credits Niolz Gamer as author and records a Vietnamese edit by Ghast Noob.

The source looks up an external plugin named `EconomyAPI` and calls its money methods, but `plugin.yml` does not declare that dependency. The default fee and messages are in [the bundled configuration](resources/config.yml). The command implementation compares the command object with a string and does not check that the sender is a player before accessing an inventory. These behaviors need review before any deployment.

This snapshot uses the historical `pocketmine\Player` class and item damage API. It has not been ported or tested on Axolotl-PM 5.x. There is no current Axolotl-PM 5 build, runtime test or stable release.

The repository has no LICENSE file or documented grant to redistribute a modified build. The original source, author credits and translated messages remain unchanged. A modern port or release needs verified rights, a declared and validated economy integration, and compatibility and behavior tests.

See [plugin.yml](plugin.yml) and [the historical source](src/apvp/apvp1.php) for the exact implementation.
