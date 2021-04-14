# 4.x changelog

## 4.0.x

### 4.0.7

#### :bug: Bug fix

- Fixed a corruption in the Russian translation file

### 4.0.6

#### :globe_with_meridians: More i18n

- Translated by @iteplenky: Added Russian
- Removed some unused texts

### 4.0.5

#### :bug: Bug fix

- Fixed a bug that caused the server to crash due to type inconsistency when migrating data from the previous version if the FloatingText name was only a number (#115)

### 4.0.4

#### :arrow_heading_up: Follow pmmp updates

- Confirmed the operation of [pmmp/PocketMine-MP](https://github.com/pmmp/PocketMine-MP) with the latest release `3.19.0` of Minecraft: Bedrock Edition 1.16.220 release
- Corresponds for changing item stacks (#111)

#### :warning: BCBreak

- Texter now requires PocketMine-MP >= 3.19.0

### 4.0.3

#### :bug: Bug fix

- Fixed a bug that caused the server to crash when performing certain operations.  
  Credits: BlitzGames(UK), MCBEPU(CN), OneMine Хаб(RU), Sulfuritium(FR), YukioLifeServer(JP), GrieferSucht(DE), HayalCraft

### 4.0.2

#### :bug: Bug fix

- Fixed a bug that caused players to be blocked when performing certain command operations.

### 4.0.1

#### :globe_with_meridians: More i18n

- Translated by @TobyDev265: Added Vietnamese

### 4.0.0

#### :sparkles: New Features

##### FloatingText

- Introduced a variable that can be inserted into FloatingTexts.  
  The notation is same as Mineflow; `{variable_name}`.
  - Requires [MineFlow >= 2.0](https://poggit.pmmp.io/p/Mineflow).
  - It can handle [some variables](/README.md#variables) of Mineflow.
    - Manipulation of floating characters using Mineflow may be implemented in 4.1 or later if requested.

##### Command

- You can now use the `/txt move` command to specify the coordinates to move to

##### Developer

- Multiple FloatingText can now be easily handled using the `FloatingTextCluster` described below.

#### :white_check_mark: Changes

##### Command

- Set `texter.command.txt` to `op`.  
If you want to allow non-OPs to use it as before, consider using a permissions plugin such as [PurePerms](https://poggit.pmmp.io/p/PurePerms).
- Removed `/txt list` command.  
  From now on, each command will automatically list the floating text as needed.

##### Configuration

- The following configuration items have been removed from `config.yml`.
  - `can.use.only.op`
  - `char`
  - `feed`
  - `world`

##### Developer

- Changed the role of floating text
  - **FloatingText**.  
    This is the smallest unit of FloatingText. The name is no longer displayed at the bottom of the FloatingText.
  - **FloatingTextCluster**.  
    A cluster of FloatingText consisting of one or more, which can be manipulated together by a command.