# Crontab Bundle ![CI Workflow](https://github.com/bpolaszek/crontab-bundle/workflows/CI%20Workflow/badge.svg)

An easy way to generate a crontab based on the application path.

Unlike similar Symfony bundles, this one does not provide a worker, it _actually_ replaces the user's crontab.

⚠️ Do not use this if your user's crontab may by altered by another process!

## Installation

This bundle is compatible with Symfony 4/5+. Use `0.1.*` tag for earlier versions compatibility.

```bash
composer require bentools/crontab-bundle 0.2.*
```

With Symfony Flex, you're already done! 

### Usage

Create a sample crontab in `config/crontab.dist`:

```bash
# config/crontab.dist

0 0 * * * php {%kernel.project_dir%}/bin/console your:favorite:command
```

As you can see, `{%kernel.project_dir%}` is a container parameter. 
It will be replaced at runtime with its current value. You can use any container parameter wrapped with curly braces.

### Preview

This will give you a preview of your crontab:

```bash
php bin/console crontab:update --dry-run --dump
```

### Apply

To apply your crontab, run this:

```bash
php bin/console crontab:update
```

Now if you execute `crontab -l` in your shell you should see something like this:
```bash
0 0 * * * php /home/me/my-project/bin/console your:favorite:command
```

## FAQ

#### Can I use any container parameter?

Yes.

#### I don't want the dist file to be `config/crontab.dist`. Can I change that?

Sure: create a `config/packages/bentools_crontab.yaml` and change the `dist_file` parameter:

```yaml
bentools_crontab:
    dist_file: '%env(CRONTAB_SAMPLE_FILE)%' # That's an example.
```

#### What are the command options?
```bash
--no-interaction # Skip confirmation question
--dry-run # Do not update crontab for real
--output-file=/path/to/generated_crontab # Change output file (which is a tmp file by default)
--dump # Show generated crontab content
```

#### I already have a crontab for the user running my app. Will it replace it?

Yes. Use this bundle only if you consider it to be the only crontab entry point.

## Tests

```bash
./vendor/bin/pest
```

## License

MIT
