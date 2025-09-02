# Implementation Plan: Resolve Binary Name Conflict

**Reference Issue**: [context/issues/32-name-conflicts-with-an-existing-file.md](../issues/32-name-conflicts-with-an-existing-file.md)

## Problem Analysis

The issue reports a Composer installation conflict:
```
Skipped installation of bin bin/zero-to-prod-data-model-factory for package zero-to-prod/data-model-helper: name conflicts with an existing file
```

**Root Cause**: The package currently declares two binary files in `composer.json`:
- `bin/zero-to-prod-data-model-factory`
- `bin/zero-to-prod-data-model-helper`

The `zero-to-prod-data-model-factory` binary is conflicting with an existing file in the user's system, likely from another package or previous installation.

## Current State Analysis

1. **Binary Files**: Both binaries exist and perform identical functionality (README.md documentation publishing)
2. **Functionality Overlap**: The `zero-to-prod-data-model-factory` binary appears to be redundant as it has the same purpose as `zero-to-prod-data-model-helper`
3. **Naming Issue**: The factory binary name suggests it should be part of a separate factory package, not this helper package

## Implementation Plan

### Step 1: Remove Conflicting Binary
- **Action**: Remove the `zero-to-prod-data-model-factory` binary file
- **Rationale**: This binary is causing the conflict and appears to be misplaced in this package
- **Files to modify**:
  - Delete `/bin/zero-to-prod-data-model-factory`
  - Update `composer.json` to remove the factory binary from the `bin` array

### Step 2: Update Composer Configuration
- **Action**: Modify `composer.json` to declare only the appropriate binary
- **Changes**:
  - Remove `"bin/zero-to-prod-data-model-factory"` from the `bin` array
  - Keep only `"bin/zero-to-prod-data-model-helper"`

### Step 3: Verification
- **Action**: Verify the solution resolves the conflict
- **Tests**:
  - Ensure `composer install` works without conflicts
  - Verify the remaining binary functions correctly
  - Confirm no functionality is lost

## Expected Outcome

After implementation:
1. The binary name conflict will be resolved
2. The package will install cleanly with Composer
3. The core functionality (documentation publishing) will remain intact through the `zero-to-prod-data-model-helper` binary
4. No breaking changes to the intended package functionality

## Risk Assessment

- **Low Risk**: The factory binary appears to be duplicative functionality
- **No Breaking Changes**: The helper binary maintains all necessary functionality
- **Clean Solution**: Removes the conflict without adding complexity

## Alternative Solutions Considered

1. **Rename the conflicting binary**: Could cause confusion and doesn't address the fundamental misplacement
2. **Keep both binaries with different names**: Would perpetuate the redundancy and confusion
3. **Current approach**: Remove the misplaced binary - cleanest and most logical solution