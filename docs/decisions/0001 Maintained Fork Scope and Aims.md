---
status: Accepted
date: 2026-04-16
deciders:
  - Martin Malec
consulted:
  - README.md
  - upstream git history
informed:
  - fork users
---
# ADR 0001: Maintained Fork Scope and Aims

## Context and Problem Statement

This repository was started as a maintained fork of the upstream
`disableactionsbygroup` plugin because the plugin remained useful, but the fork
needed a local place for ongoing maintenance, release hygiene, documentation
updates, and compatibility work.

The fork should remain conservative. It is not intended as a feature branch for
unrelated experiments. It exists to keep the plugin usable, understandable, and
safe to operate when upstream maintenance is absent, delayed, or insufficient
for local needs.

## Decision Drivers

- keep a useful DokuWiki plugin available in a maintainable local repository
- allow safe maintenance work without waiting on upstream activity
- improve release metadata and user-facing documentation
- preserve the original plugin purpose instead of expanding product scope
- enable bug fixes and compatibility work when they are necessary for safe use

## Considered Options

### Stay on upstream only

Rely entirely on upstream and do not keep a maintained fork.

### Keep a maintained fork with a narrow scope

Maintain a conservative fork focused on compatibility, documentation, release
hygiene, and safe bug fixing.

### Turn the fork into a feature-expansion branch

Use the fork as a place for broader plugin redesign or unrelated new features.

## Decision Outcome

Chosen option: keep a maintained fork with a narrow scope.

This fork may diverge from upstream when that is required for maintainability,
documentation quality, safe bug fixes, and compatibility with current local
needs. The fork should still preserve the plugin's original role: restricting
DokuWiki actions further for selected groups.

## Consequences

- future changes should stay aligned with the plugin's original purpose
- maintenance and safety fixes are explicitly in scope for the fork
- documentation and release tracking become first-class project concerns
- any broader semantic change should be justified separately instead of being
  hidden inside routine maintenance

## Related Decisions

- [ADR 0002: Modernization Baseline for Maintained Fork](./0002%20Modernization%20Baseline%20for%20Maintained%20Fork.md)
- [ADR 0003: Additive Disableactions Semantics](./0003%20Additive%20Disableactions%20Semantics.md)
