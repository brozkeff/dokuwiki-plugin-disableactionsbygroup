# Plans

## Fix global `disableactions` override bug

- Status: implemented in repository working tree on `2026-04-16`
- Affected release: `2026-04-10`
- Problem: the plugin currently replaces DokuWiki's global `disableactions`
  value for the first matching group instead of preserving it and adding only
  group-specific restrictions.
- Required target behavior: any action disabled globally via DokuWiki core
  configuration must stay disabled for all groups. This plugin may only disable
  more actions, never re-enable actions already disabled globally.

## Root cause

- `action.php` assigns `$conf['disableactions'] = $action` for the first
  matching group.
- Because the assignment is destructive, a matching rule such as `admin:` can
  clear the effective disabled-action list instead of preserving the global
  configuration.
- The bug affects all matched groups, including the special failed-login path
  that uses `ALL`.

## Implemented changes

- Capture the original DokuWiki `disableactions` value before this plugin
  applies any group logic.
- Parse the original core action list and the matched group action list into
  normalized action sets.
- Merge the matched group actions additively with the original core-disabled
  actions.
- Preserve first-match group selection semantics.
- Treat empty group rules such as `admin:` as "no additional restrictions".
- Apply the same additive semantics to the `ALL` failed-login handling.
- Avoid duplicate actions in the effective merged list.
- Define deterministic output formatting for the merged action list so tests do
  not depend on incidental ordering.

## Remaining verification gaps

- matched group with non-empty action list preserves global disabled actions and
  adds group-specific ones
- matched group with empty action list preserves global disabled actions
- unmatched user keeps the original global `disableactions` value unchanged
- failed login / `ALL` path preserves global disabled actions and adds `ALL`
  restrictions only
- first matching group still wins when multiple configured groups match
- malformed configuration entries are ignored without corrupting global action
  restrictions

## Documentation updates included with the fix

- `README.md` now states additive restriction semantics and includes an upgrade
  note for `2026-04-10`
- `CHANGELOG.md` records the inherited bug and the maintained-fork fix path
- `docs/decisions/0003 Additive Disableactions Semantics.md` is the normative
  decision record for the new behavior
