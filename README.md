# RLRankTracker
Rocket League Rank Tracking bot for IRC. This bot can be queried for steam user's Rocket League rankings. It utilizes rltracker.pro to get ranks.
Currently it can map user's to steam profiles and get ranks.

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

Edit the main.php file to change what default channel the bot should join as well as it's name on line 17.
```
$bucket->getSource()->join('YourUsername', '##YourChannel');
```

Run by launching the main.php file with php:
```
php main.php
```



## Known Issues
- The bot currently responds in main channel instead of via PM to private messages
