# 4.x changelog

## 4.0.x

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