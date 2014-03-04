CloudFlare Dynamic DNS Updater
==============================

A simple command to keep a cloudflare's zone record updated with your local ip
address so you can use it instead of dyndns.org or similar services.

Installation
------------

Install it globally with composer:

```shell
$ composer global require flatline/cfddns:dev-master
```

Or clone the repo and use `./bin/cfddns`.

Usage
-----

First you'll need to create a config file using the `init` command and then use
the `update` command to update CloudFlare's record.

For the update, you can set up a cron so it's done automatically. I have it run
every 15 minutes, I think it's more than enough. But the CloudFlare's API rate
limit is 1200 requests every 5 minutes, so, theoretically, you could run it
faster if you need to.

### The `init` command

```shell
$ cfddns init
```

The command will ask you for all the needed data and save the config file to
your home: `~/cfddns.yml`.

_If you want to create it manually, there's a sample config file in the repo
root you can use._

### The `update` command

To update your CloudFlare record, run the update command:

```shell
$ cfddns update
```

This will automatically grab your public ip and update the CloudFlare's record
with it.

The command calls the [`rec_edit`](http://www.cloudflare.com/docs/client-api.html#s5.2)
action on the CloudFlare API. The config sets some of the parameters for this command,
so check the docs if you need more details.

To do
-----

This are some improvements for the future:

* Logging (with monolog)
  This way it can be run in quiet mode and still be able to log errors (useful
  when runing from a cron)

* Automatically add record to a zone if the record does not exist
