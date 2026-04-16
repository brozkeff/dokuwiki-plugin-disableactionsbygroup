<!-- markdownlint-configure-file {"MD024": false} -->
# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project follows a maintained-fork style changelog. Early upstream
history is summarized from the existing git log because older releases were not
documented in a changelog file.

## [Unreleased]

### Added

- `docs/PLANS.md` plan for changing the plugin so DokuWiki core
  `disableactions` remains enforced for all groups.
- `docs/decisions/` ADR set for fork scope, modernization rationale, and the
  planned additive `disableactions` behavior.
- lightweight PHP regression coverage in `tests/regression.php` for additive
  merge behavior, malformed entries, and `ALL` handling

### Changed

- Documentation now describes additive semantics as the maintained-fork
  contract and includes upgrade guidance for deployments coming from
  `2026-04-10`.

### Fixed

- Preserved DokuWiki core `disableactions` as the baseline for all users and
  merged the first matching group rule additively instead of replacing the
  global action list.
- Applied the same additive rule to the `ALL` failed-login path.
- Normalized merged action lists by trimming empty items and removing
  duplicates.

### Known Issues

- No full DokuWiki-integrated automated test harness exists in the repository
  yet. Current coverage is a lightweight PHP regression script plus targeted
  smoke verification.

## [2026-04-10]

### Changed

- Established the maintained fork metadata, licensing refresh, and repository
  documentation baseline.
- Modernized the PHP code style and added defensive parsing for malformed
  `disableactionsbygroup` entries without intentionally changing the original
  first-match behavior.

### Known Issues

- The maintained `2026-04-10` release still inherits a long-standing bug from
  upstream: when a group rule matches, the plugin replaces DokuWiki's global
  `disableactions` value instead of only adding further restrictions.
- Because the bug is inherited, it predates the fork and was already present in
  the original upstream implementation and later upstream maintenance commits.

## Earlier History

### 2016-09-19

- Initial upstream plugin import (`8aa5030`).
- The original implementation already overwrote `disableactions` for the first
  matching group.

### 2018-02-12

- Added support for `ALL` users for non-logged-in handling (`5a6e598`).
- Added plugin URL metadata in the historical upstream line (`fb25a0f`,
  `972525c`).

### 2020-08-28

- Upstream maintenance update to `action.php` (`36e2192`) without changing the
  inherited overwrite semantics.
