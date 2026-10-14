# BuyFix

BuyFix repairs the damaged tool or armor item a player is holding after EconomyAPI accepts the configured payment. It preserves the original Vietnamese success and insufficient-balance messages and the default price of 100.

## Requirements

- Minimum Axolotl-PM API: 5.0.0. CI checks the exact API 5.0.0 and 5.49.1 source revisions; this does not prove support for every API 5.x revision.
- An API 5-compatible [EconomyAPI](https://github.com/ACM-PocketMine-MP/EconomyAPI) plugin exposing myMoney() and reduceMoney().

## Use

Set the price and optional messages in [resources/config.yml](resources/config.yml). Players can use **/buyfix** while holding a damaged durable item. The configured price must be positive and have no more than two decimal places. BuyFix does not charge for undamaged or non-durable items. It changes the held item only after reduceMoney() returns the EconomyAPI success result.

## Build and verification

GitHub Actions runs the transaction fixture, PHPStan at maximum level, builds and inspects the plugin PHAR against pinned API 5 source revisions, then smoke-tests the API 5.49.1 artifact on the checksum-verified Axolotl-PM server. See the [build workflow](https://github.com/NhanAZ-Plugins/BuyFix/actions/workflows/build.yml) for exact tested revisions and results. The fixture verifies that rejected payments leave item durability unchanged; the runtime smoke verifies startup and does not exercise a connected player or a live economy provider.

Original author: Niolz Gamer. Vietnamese edit: Ghast Noob. This repository has no license file or recorded license grant.
