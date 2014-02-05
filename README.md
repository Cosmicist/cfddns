CloudFlare Dynamic DNS Updater
==============================

A simple command to keep a cloudflare's zone record updated with your local ip
address so you can use it instead of dyndns.org or similar services.

Installation
------------

Installit globally with composer:

```shell
$ composer global require flatline/cfddns:dev-master
```

Or clone the repo and use `bin/cfddns`.

Usage
-----

First you'll need to create a config file and place it in your home:
`~/cfddns.yml`.

There's a sample in the repo root.

Then, if you installed if with composer you can just run:

```shell
$ cfddns update
```

If you cloned the repo, from the repo root directory run:

```shell
$ ./bin/cfddns update
```

You can add the command to a cron to make the update automatically.

To do
-----

This are some improvements for the future:

* Logging (with monolog)
  This way it can be run in quiet mode and still be able to log errors (useful
  when runing from a cron)

* Automatically add record to a zone if the record does not exist

* Add command to initialize the default config
