# Name conflicts with an existing file

**Issue #32** | **State:** OPEN | **Created:** 2025-09-02T21:37:24Z

**Labels:** None  
**Assignees:** None

**GitHub URL:** https://github.com/zero-to-prod/data-model-helper/issues/32

## Description

**Describe the bug**
When installing this package I see this error:
```shell
Skipped installation of bin bin/zero-to-prod-data-model-factory for package zero-to-prod/data-model-helper: name conflicts with an existing file
```

**To Reproduce**
Steps to reproduce the behavior:

1. Update this package in an existing project with this in `composer.json`
```json
    "post-update-cmd": [
      "zero-to-prod-package-helper"
    ],
```
2. Run: `composer update`
5. See error: `Skipped installation of bin bin/zero-to-prod-data-model-factory for package zero-to-prod/data-model-helper: name conflicts with an existing file`

**Expected behavior**
Remove confict.