# 4.x changelog

## 4.3.x

### 4.3.0

#### :globe_with_meridians: More i18n

- Translated by @TERAMMII: Korean reworked, English improved
- Translated by @DragonfireHD98: Added German

#### :arrow_heading_up: following PMMP changes

- Added support for PMMP 5.11.x (Minecraft 1.20.60~)

## 4.2.x

### 4.2.3

#### ✔ Changes

- Now loads only the worlds you need when you need them (#151)

### 4.2.2

#### :bug: Bug fixes

- Fixed a bug that WaterdogPE could not process packets properly (#148)

### 4.2.1

#### :arrow_heading_up: follow PMMP changes

- Added support for PMMP 5.3.x (Minecraft 1.20.10~)

### 4.2.0

#### :arrow_heading_up: follow PMMP changes

- Added support for PMMP 5.1.x (Minecraft 1.20.0~)

## 4.1.x

### 4.1.10

#### :arrow_heading_up: follow PMMP changes

- Added support for PMMP 4.20.x (Minecraft 1.19.80~)

#### :bug: bug fixes

- Fixed a server crash when a player entered the server.

### 4.1.9

#### :arrow_heading_up: follow PMMP changes

- Added support for PMMP 4.14.x (Minecraft 1.19.60~) (#137)

### 4.1.8

#### :arrow_heading_up: follow PMMP changes

- Added support for PMMP 4.10.x (Minecraft 1.19.40~)

### 4.1.7

#### :bug: bug fixes

- Fixed a server crash with type mismatch when editing floating texts ([CrashArchive#7465119](https://crash.pmmp.io/view/7465119))
- Fixed a server crash when using uninitialized properties when moving floating texts ([CrashArchive#7479342](https://crash.pmmp.io/view/7479342))

### 4.1.6

#### :bug: bug fixes

- Fixed server crash when editing floating texts (#130)

### 4.1.5

#### :bug: bug fixes

- Fixed an issue where unique identifiers could be changed when editing floating texts (#129)

### 4.1.4

#### :globe_with_meridians: More i18n

- Translated by @SuperYYT: Fixed Chinese(Simplified)

#### :arrow_heading_up: follow PMMP changes

- Added support for PMMP 4.6.x (Minecraft 1.19.10~)

### 4.1.3

#### :bug: bug fixes

- Fixed an issue where floating characters would be sent to a non-existent player when the player joined the server and immediately left
- Fixed an issue where a type mismatch would cause the server to crash when using `/txt move`

### 4.1.2

#### :bug: bug fixes

- Fixed a server crash when using `/txt move {text name} here`
- Fixed a bug that floating text was displayed at different coordinates than the actual coordinates when using `/txt move`

#### :arrow_heading_up: follow PMMP changes

- Added support for PMMP 4.5.x (Minecraft 1.19.0.5~)  
  The `mcpe-protocol` is no longer used, as the minor version of PMMP represents the protocol (in most cases)

#### :globe_with_meridians: internationalization

- Translated by @IvanCraft623: Added Spanish (Mexico)

### 4.1.1

#### :bug: Bug fix

- Fixed a crash when using forms

### 4.1.0

#### :arrow_heading_up: following PMMP changes

- Following up on PMMP 4.x changes
- Just in.

***

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