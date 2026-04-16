---
status: Accepted
date: 2026-04-16
---

# ADR 0003: Additive Disableactions Semantics

## Context and Problem Statement

The plugin currently replaces DokuWiki's global `disableactions` value for the
first matching group. That behavior is inherited from upstream and predates the
maintained fork, but it conflicts with the intended role of this plugin as an
extra restriction layer.

When global core settings already disable actions, a matching group rule such
as `admin:` can effectively re-enable those actions by replacing the baseline
configuration with an empty or narrower group list.

## Decision Drivers

- DokuWiki core configuration must remain the baseline source of disabled
  actions
- this plugin should only narrow allowed actions for matching groups
- empty group rules should not weaken inherited global restrictions
- the plugin should preserve first-match group selection while preventing
  policy weakening
- the maintained fork needs a coherent and safer runtime contract

## Considered Options

### Keep override semantics

Continue replacing global `disableactions` with the first matching group rule.

### Merge matched group actions additively with core `disableactions`

Preserve the global disabled-action list and add group-specific disabled
actions on top of it.

### Remove group-specific behavior entirely

Rely only on DokuWiki core `disableactions` and stop extending it by group.

## Decision Outcome

Chosen option: merge matched group actions additively with core
`disableactions`.

The architectural boundary is that DokuWiki core defines the baseline disabled
actions, while this plugin may only add further restrictions for the first
matching group. Empty rules such as `admin:` mean no additional restrictions
for that group; they must not remove core restrictions already in force.

Implementation in the maintained fork captures the original core
`disableactions` list before plugin mutation, parses both the core and
group-specific lists into normalized action sets, and writes back a de-duplicated
merged list only when a group rule matches. This pathway was chosen because it
preserves the historical first-match selection model while eliminating the
policy-bypass behavior caused by destructive replacement.

## Consequences

- the plugin becomes monotonic with respect to core `disableactions`
- installations relying on the inherited buggy override behavior may observe
  stricter action restrictions after the fix
- implementation and tests must define normalization, de-duplication, and the
  failed-login `ALL` behavior under the same additive rule
- user documentation should describe this rule as the normative behavior of the
  maintained fork

## Related Decisions

- [ADR 0001: Maintained Fork Scope and Aims](./0001%20Maintained%20Fork%20Scope%20and%20Aims.md)
- [ADR 0002: Modernization Baseline for Maintained Fork](./0002%20Modernization%20Baseline%20for%20Maintained%20Fork.md)
- Operational follow-up: [PLANS.md](../PLANS.md)
