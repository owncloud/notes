# agents.md -- Notes

## Repository Overview

Distraction-free note-taking app for ownCloud Server with a RESTful API. Licensed under AGPL-3.0. PHP backend with JavaScript frontend.

## Architecture & Key Paths

- `controller/` -- HTTP controllers
- `service/` -- Business logic services
- `db/` -- Database mappers and entities
- `js/` -- Frontend JavaScript
- `css/` -- Stylesheets
- `templates/` -- Server-side templates
- `appinfo/` -- ownCloud app metadata
- `l10n/` -- Translation files
- `tests/` -- Unit and integration tests
- `Makefile` -- Build and test automation
- `composer.json` -- PHP dependencies

## Development Conventions

- PHP code follows ownCloud coding standards (phpcs)
- Static analysis with PHPStan

## Build & Test Commands

```bash
make build                    # Build the app
make test-php-unit            # Run PHP unit tests
make test-php-integration     # Run PHP integration tests
make test-php-style           # Check PHP code style
make dist                     # Create distribution package
```

## Important Constraints

- Licensed under AGPL-3.0 (copyleft). Apache 2.0 migration planned.
- All contributions require a DCO sign-off.


## OSPO Policy Constraints

### GitHub Actions
- **Only** use actions owned by `owncloud`, created by GitHub (`actions/*`), verified on the GitHub Marketplace, or verified by the ownCloud Maintainers.
- Pin all actions to their full commit SHA (not tags): `uses: actions/checkout@<SHA> # vX.Y.Z`
- Never introduce actions from unverified third parties.

### Dependency Management
- Dependabot is configured for automated dependency updates.
- Review and merge Dependabot PRs as part of regular maintenance.
- Do not introduce new dependencies without discussion in an issue first.

### Git Workflow
- **Rebase policy**: Always rebase; never create merge commits. Use `git pull --rebase` and `git rebase` before pushing.
- **Signed commits**: All commits **must** be PGP/GPG signed (`git commit -S -s`).
- **DCO sign-off**: Every commit needs a `Signed-off-by` line (`git commit -s`).
- **Conventional Commits & Squash Merge**: Use the [Conventional Commits](https://www.conventionalcommits.org/) format where the repository enforces it. Many repos use squash merge, where the PR title becomes the commit message on the default branch — apply Conventional Commits format to PR titles as well. A reusable GitHub Actions workflow enforces this.

## Context for AI Agents

Standard OC10 app with controller/service/db architecture. The REST API is documented in the wiki. Notes are stored as plain text files in user cloud storage.
