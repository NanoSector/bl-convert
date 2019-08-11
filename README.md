# Blocklist conversion scripts and utilities
[![Build Status](https://scrutinizer-ci.com/g/Yoshi2889/bl-convert/badges/build.png)](https://scrutinizer-ci.com/g/Yoshi2889/bl-convert/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Yoshi2889/bl-convert/badges/quality-score.png)](https://scrutinizer-ci.com/g/Yoshi2889/bl-convert/?branch=master)
[![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/Yoshi2889/bl-convert/badges/coverage.png)](https://scrutinizer-ci.com/g/Yoshi2889/bl-convert/code-structure/master/code-coverage)

Documentation TBD. Bl-convert consists of a framework which can convert between various blocklist formats.

# Requirements
This script may use *a lot* of memory. Therefore, the standard PHP memory limit likely won't work.
The framework will not attempt to raise this memory limit. Adjust it in your php.ini.

# Concepts
The framework relies on 4 basic concepts:

## HostnameList
This is the in-memory list of hostnames to be blocked. They are immutable; discard the instance if you no longer need it.

Moreover, in-memory lists may be split into multiple sub-lists. This is useful when you want to split a large list into multiple smaller files.

## InputStrategies
These classes read files and convert them into memory-based hostname lists. They are considered the source of the conversion.

Current available strategies include:
- HostsListInput: Reads hosts-based files. Supports formats where multiple hostnames are put on a single line, e.g. `0.0.0.0 facebook.com fbcdn.net`.
- WildCardListInput: Reads wildcard blocklists, e.g. `facebook.com` which blocks all of its subdomains.

## Filters
Filters manipulate in-memory lists.

Current available filters include:
- ListDeduplicator: De-duplicates lists, removing duplicate entries.
- ListGeneralizer: Generalizes lists, that is, makes its hostnames more generic. It does so by cutting off portions of the hostname. For example: `foo.bar.baz` generalized to a depth of 2 returns `bar.baz`.

## OutputStrategies
These classes read in-memory lists and output them in specific formats. They are considered the destination of the conversion.

Current available strategies include:
- LittleSnitchDirOutput: Directory output to Little Snitch rules files. This method is preferred over the ListOutput variant, because this strategy handles large lists beyond what Little Snitch supports by splitting the list into multiple files.
- LittleSnitchListOutput: Single list output to Little Snitch rules files. This outputs an in-memory list to a single Little Snitch rules file. This strategy will return an error when it notices too many rules have been passed, in that case, use LittleSnitchDirOutput instead.