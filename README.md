cgui
==========

A web interface for cgminer that that supports GPUs and FPGAs (and also ASICs, as soon as i get my BFL Single) on multiple miners.
Here's a [demo](http://faked.org/miner/).

Getting started
----------
Download the files and extract them in a folder in your webserver's root directory.
Copy ```config.php.example``` to ```config.php``` and adjust the settings.

Setting up
----------
Update your cgminer arguments to include ```--api-listen```, and if you are not running the webserver and cgminer on the same machine, also ```--api-allow <IP-of-your-webserver>```. Also remember to edit the timezone with one from [this list](http://php.net/manual/en/timezones.php).

Credits
----------
[Nehal Patel](https://github.com/nehalvpatel/cgui) for the initial project, which unfortunately only supports GPUs and a single miner.