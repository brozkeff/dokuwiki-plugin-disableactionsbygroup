# disableactionsbygroup Plugin for DokuWiki

This plugin lets you restrict selected DokuWiki actions for specific user groups.
It extends DokuWiki's generic `disableactions` setting with group-based rules and
applies only the first matching group entry.

Maintained fork:
<https://github.com/brozkeff/dokuwiki-plugin-disableactionsbygroup>

Official documentation:
<https://www.dokuwiki.org/plugin:disableactionsbygroup>

## Project Status

This repository is a maintained fork. Project history and release notes are
tracked in [CHANGELOG.md](./CHANGELOG.md), and the long-term rationale for fork
and behavior decisions is tracked in [docs/decisions](./docs/decisions/).

## Upgrade Note

Version `2026-04-10` had a major inherited bug: when a configured group
matched, the plugin replaced DokuWiki's global `disableactions` setting instead
of only adding further restrictions. That bug is fixed in the current
repository state.

If you upgrade from `2026-04-10`, review deployments that may have relied on
buggy override behavior. Empty rules such as `admin:` or `author:` now mean
"no additional restrictions" for the matching group, while global DokuWiki
`disableactions` remains enforced.

Current behavior:

- any action disabled globally via DokuWiki `disableactions` remains
  disabled for every group
- this plugin only adds further disabled actions for the first matching group
- an empty rule such as `admin:` means "no additional restrictions", not
  "remove global restrictions"
- effective action ordering is deterministic: global disabled actions stay
  first, and first-matching group additions are appended in configured order

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

Additive example:

- global DokuWiki `disableactions=edit`
- plugin rule `user:media`
- effective disabled actions for `user`: `edit,media`

Upgrade verification checklist:

- verify a group with an empty rule such as `admin:` still inherits globally
  disabled actions
- verify a matching non-empty group adds actions instead of replacing the
  global list
- verify any `ALL:` rule also adds restrictions instead of clearing the global
  list

Syntax:

```text
groupname1:action1,action2,action3,...;groupname2:action1,action2,action3,...
```

The order of groups matters. Only the first matching group applies to a user.

## Fork Notes

This fork adds small maintenance updates since the upstream release:

- defensive hardening for malformed configuration entries
- PHP and plugin code modernization
- additive merge semantics for global `disableactions` and group-based rules
- documentation and repository metadata refresh for the maintained fork

## Validation

Run the DokuWiki plugin tests from a DokuWiki checkout `_test` environment, for
example:

```bash
cd dokuwiki/_test
composer run test -- --group plugin_disableactionsbygroup
```

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
