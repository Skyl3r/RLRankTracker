# RLRankTracker
Rocket League Rank Tracking bot for IRC. This bot can be queried for steam user's Rocket League rankings. It utilizes rltracker.pro to get ranks.
Currently it can map user's to steam profiles and get ranks.

## Current Features

#### Get Rank
getrank is the reason RLRankTracker was created. It is a simple method that grabs a page from rltracker.pro and scrapes the ranks from it to return them to the user.

#### Profile  Tracking
Via the use of functions setprofile and getprofile, profiles can be tracked. This will provide live updates in channel when a user is promoted or demoted.

#### Get News
RLRankTracker can monitor rltracker.pro/blogposts to check for new posts. If a new post is found, it will update in channel with the post headline and a link to the post.

## Setup

### Dependencies
RLRankTracker depends on the Hoa IRC library as well as DiDom DOM parser library. Both of these can be gotten through composer. In addition, you need PHP extensions mbstring and xml.

### Debian Setup
Clone RLRankTracker:
```
git clone https://github.com/Skyl3r/RLRankTracker
```

Verify you have software dependencies:
```
sudo apt install composer php php-xml php-mbstring -y
```

Use composer to get dependencies:
```
cd RLRankTracker
composer update
```
Edit Config.php and set your preferred default settings.

Run by launching the main.php file with php:
```
php main.php
```



## Known Issues
- The bot currently responds in main channel instead of via PM to private messages
