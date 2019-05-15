# No Deprecated. See new project here:
https://github.com/SaltieRL/discord-bot/tree/v2

# RLRankTracker
Rocket League Rank Tracking bot for IRC. This bot can be queried for steam user's Rocket League rankings. It utilizes rltracker.pro to get ranks.
Currently it can map user's to steam profiles and get ranks.

1. [Current Features](#current-features)
2. [Setup](#setup)
    * [Debian](#debian-setup)
3. [Configuration](#configuration)
4. [Custom Commands](#writing-custom-commands)
5. [Known Issues](#known-issues)

## Current Features

#### Get Rank
getrank is the reason RLRankTracker was created. It is a simple method that grabs a page from rltracker.pro and scrapes the ranks from it to return them to the user.

#### Profile  Tracking
Via the use of functions setprofile and getprofile, profiles can be tracked. This will provide live updates in channel when a user is promoted or demoted.

#### Get News
RLRankTracker can monitor rltracker.pro/blogposts to check for new posts. If a new post is found, it will update in channel with the post headline and a link to the post.

## Setup

#### Dependencies
RLRankTracker depends on the Hoa IRC library as well as DiDom DOM parser library. Both of these can be gotten through composer. In addition, you need PHP extensions mbstring and xml.

#### Debian Setup
Clone RLRankTracker:
```bash
git clone https://github.com/Skyl3r/RLRankTracker
```

Verify you have software dependencies:
```bash
sudo apt install composer php php-xml php-mbstring -y
```

Use composer to get dependencies:
```bash
cd RLRankTracker
composer update
```
Edit Config.php and set your preferred default settings.

Run by launching the main.php file with php:
```bash
php main.php
```

## Configuration

All configuration is done through the `Config.php` file.
Basic settings involve server settings and the ability to change data cache locations.


## Writing Custom Commands

The preferred method for writing custom commands is to add commands in `CustomCommands.php`. All commands need to be formatted as follows:
```php
$IrcCommands['command_name']['help'] = "A helpful help string";
$IrcCommands['command_name']['required_args'] = 1;
$IrcCommands['command_name']['function'] = function(&$bucket, $args) {};
```

- `$args` will be an array where $args[0] is the sender and $args[1 .. ] are the arguments that were passed in.
- `$bucket` is a reference to the client instance so you can send messages. The preffered way to send messages is `$bucket->getSource()->say("your message");`
- You can access data in the global `$Configs` variable by adding `Global $Configs;` to your function before referencing it.


#### Referencing user profiles

User profiles should be accessed via the `ProfileManager` class.  
```php
$profileManager = new ProfileManager();
$steamprofile = $profileManager->getProfile("IrcName");
$profileManager->setProfile("IrcName", "SteamID");
$profileManager->saveProfiles();
```
Setting a profile only adds it to that instance of the `ProfileManager`. To save the change, you must call `saveProfiles()`.

#### The Rank class

The `Rank` class is how rank data is obtained for a profile. The bare minimum for the rank class to work is a steamprofile ID.
```php
$rank = new Rank();
$rank->steamProfile = "someprofileID";
$rank->getRank();
```
When you run `getRank()`, the `ranks` array will be populated with an integer key that correlates to a rank in the global `Ranks` table. To get a human readable rank string:
```php
Global $Ranks;
print($Ranks[$rank->ranks['Duels']]);
```
Ranks are stored in this way to make them easy to compare.


## Known Issues
- The bot currently responds in main channel instead of via PM to private messages
