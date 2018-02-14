# Crontab Bundle

An easy way to generate a crontab based on the application path.

Unlike similar Symfony bundles, this one does not provide a worker, it really updates the crontab.

## Installation

```bash
composer require bentools/crontab-bundle 1.0.x-dev
```

Then add the bundle into your kernel (you've done that before).

Configure where your sample crontab will be located:

```yaml
bentools_crontab:
    dist_file: '%kernel.project_dir%/config/crontab.dist'
```

## Usage

Create a sample crontab in your project:

```bash
# app/config/crontab.dist or config/crontab.dist for instance

0 0 * * * php {%kernel.project_dir%}/bin/console app:test --no-interaction -vv >> {%kernel.project_dir%}/var/log/app_test.log 2>&1
```

As you can see, `{%kernel.project_dir%}` is a container parameter. We will replace it with its current value:

```bash
php bin/console crontab:update
```

Now if you execute `crontab -l` in your shell you should see something like this:
```bash
0 0 * * * php /var/www/my_project/bin/console app:test --no-interaction -vv >> /var/www/my_project/var/log/app_test.log 2>&1
```

## FAQ

#### Can I use any container parameter?

Yes.

#### What are the command options?
```bash
--no-interaction # Skip confirmation question
--dry-run # Do not update crontab for real
--output-file=/path/to/generated_crontab # Change output file (defaults to temporary)
--dump # Show generated crontab content
```

#### I already have a crontab for the user running my app. Will it replace it?

Yes. Use this bundle only if you consider it to be the only crontab entry point.

## Tests

Ooops...

I know, there aren't tests for the moment. 

Testing Symfony commands and processes are a real pain and a PR would really help. 

I'm using it with Symfony 4+, but there's no reason it won't work on older versions.

## License

MIT