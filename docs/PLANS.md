# Plans

## Fix global `disableactions` override bug

- Status: completed on `2026-04-16`
- Affected release fixed: `2026-04-10`
- Outcome: the plugin now preserves DokuWiki core `disableactions` for all
  users and only adds further restrictions from the first matching group rule.

Implemented work was moved into the durable records:

- [CHANGELOG.md](../CHANGELOG.md) for release history and versioned changes
- [ADR 0003](./decisions/0003%20Additive%20Disableactions%20Semantics.md) for
  the normative runtime contract and implementation rationale
- [_test/ActionPluginTest.php](../_test/ActionPluginTest.php) for DokuWiki
  plugin-level verification of the additive behavior

No further plan items remain for this bug in this file.
