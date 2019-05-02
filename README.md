# RLRankTracker
Rocket League Rank Tracking bot for IRC. This bot can be queried for steam user's Rocket League rankings. It utilizes rltracker.pro to get ranks.
Currently it can map user's to steam profiles and get ranks.

1. [Current Features](current_features)

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

User profiles are stored in a serialized text file. The default is `profiles.txt`, the location is stored in the global $Configs array. When unserialized, you will get an array as follows:
```php
profiles = [
['irc_name', 'steam_id'],
['irc_name', 'steam_id'],
..
];
```
To add to, modify or read from the list of profiles, you can do something like:
```php
Global $Configs;
$profiles = [];
if(file_exists($Configs['default_profiles_file'])) {
    $profiles = unserialize(file_get_contents($Configs['default_profiles_file']));
}
```
Saving changes is done in the same manner.
```php
file_put_contents($Configs['default_profiles_file'], serialize($profiles));
```

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
