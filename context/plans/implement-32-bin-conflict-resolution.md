# Implementation Plan: Resolve Binary Name Conflict (Issue #32)

## Context
Reference: [context/issues/32-name-conflicts-with-an-existing-file.md](../issues/32-name-conflicts-with-an-existing-file.md)

## Problem
When installing the `zero-to-prod/data-model-helper` package, Composer reports a binary name conflict:
```
Skipped installation of bin bin/zero-to-prod-data-model-factory for package zero-to-prod/data-model-helper: name conflicts with an existing file
```

## Root Cause Analysis
The package includes `bin/zero-to-prod-data-model-factory` in its bin array, but this conflicts with another package or existing binary file in the user's environment.

## Solution Strategy
Remove the conflicting binary from the package configuration since:
1. The main functionality should be provided by `bin/zero-to-prod-data-model-helper`
2. The `zero-to-prod-data-model-factory` binary may be provided by a separate package (`zero-to-prod/data-model-factory`)
3. The conflict prevents proper installation

## Implementation Steps

### 1. Update composer.json
- Remove `bin/zero-to-prod-data-model-factory` from the bin array
- Keep only `bin/zero-to-prod-data-model-helper` as the main binary
- This maintains the core functionality while resolving the conflict

### 2. Remove conflicting binary file
- Delete the `bin/zero-to-prod-data-model-factory` file from the repository
- This prevents future conflicts and clarifies package responsibility

### 3. Update package suggestions
- Ensure `zero-to-prod/data-model-factory` is properly suggested in composer.json
- This guides users to the correct package for factory functionality

## Expected Outcome
- Composer installation will complete without conflicts
- Users can still access factory functionality via the dedicated `zero-to-prod/data-model-factory` package
- Package installation integrates properly with `post-update-cmd` scripts

## Validation
- Test composer installation in a clean environment
- Verify the remaining binary (`zero-to-prod-data-model-helper`) functions correctly
- Confirm no regression in core package functionality