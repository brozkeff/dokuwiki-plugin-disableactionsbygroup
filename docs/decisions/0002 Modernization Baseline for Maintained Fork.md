---
status: Accepted
date: 2026-04-10
deciders:
  - Martin Malec
consulted:
  - ADR 0001
  - action.php
  - plugin.info.txt
informed:
  - fork users
---
# ADR 0002: Modernization Baseline for Maintained Fork

## Context and Problem Statement

By April 2026 the maintained fork needed a clearer maintenance baseline. The
repository had no changelog, minimal fork-specific documentation, and older PHP
coding style in the plugin implementation. Even when behavior should remain
stable, a living fork needs cleaner code, release metadata, and defensive input
handling so later maintenance can be done with lower risk.

## Decision Drivers

- improve readability and maintainability of the PHP plugin code
- refresh repository metadata for maintained-fork use
- harden parsing against malformed plugin configuration entries
- make later bug fixing and review easier
- keep maintenance work distinct from intentional behavior changes

## Considered Options

### Leave the codebase in its inherited state

Avoid touching style, metadata, or defensive parsing to minimize diff size.

### Establish a modernization baseline without changing intended behavior

Refresh formatting, metadata, and defensive parsing while keeping semantics
stable unless a later decision explicitly changes them.

### Combine modernization with semantic redesign

Use the maintenance window to refactor behavior and policy at the same time.

## Decision Outcome

Chosen option: establish a modernization baseline without changing intended
behavior.

Modernization work in this fork may improve readability, formatting, metadata,
and defensive handling, but behavior changes must be called out separately and
must not be hidden inside cleanup work.

## Consequences

- maintenance diffs become easier to review and reason about
- later bug fixes can build on safer parsing and a cleaner code layout
- documentation and release hygiene are treated as part of project maintenance
- semantic changes require their own explicit decision record

## Related Decisions

- [ADR 0001: Maintained Fork Scope and Aims](./0001%20Maintained%20Fork%20Scope%20and%20Aims.md)
- [ADR 0003: Additive Disableactions Semantics](./0003%20Additive%20Disableactions%20Semantics.md)
