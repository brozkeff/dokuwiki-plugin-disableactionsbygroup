# disableactionsbygroup Plugin for DokuWiki

This plugin lets you restrict selected DokuWiki actions for specific user groups.
It extends DokuWiki's generic `disableactions` setting with group-based rules and
applies only the first matching group entry.

Maintained fork:
<https://github.com/brozkeff/dokuwiki-plugin-disableactionsbygroup>

Official documentation:
<https://www.dokuwiki.org/plugin:disableactionsbygroup>

## Usage

Configure a semicolon-separated list of `group:actions` entries in the plugin
settings, for example:

```text
admin:;author:;user:edit,media,diff,source,backlinks
```

This means:

- members of `admin` have no additional restrictions
- members of `author` have no additional restrictions
- members of `user` cannot use `edit`, `media`, `diff`, `source`, or `backlinks`
- users without a matching group keep whatever is configured in DokuWiki's
  generic `disableactions` setting

Syntax:

```text
groupname1:action1,action2,action3,...;groupname2:action1,action2,action3,...
```

The order of groups matters. Only the first matching group applies to a user.

## Fork Notes

This fork keeps the original behavior but adds small maintenance updates since
the upstream release:

- defensive hardening for malformed configuration entries
- PHP and plugin code modernization
- documentation and repository metadata refresh for the maintained fork

## Credits

Copyright (C) 2016-2023 Andreas Hansson
Copyright (C) 2026 Martin Malec

Based on denyactions by Otto Vainio <otto@valjakko.net>

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details.

See the `LICENSE` file in this repository for details.
