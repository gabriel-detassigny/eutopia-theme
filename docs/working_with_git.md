Working with Git

:back: [Home](../README.md)

Table of Contents

- [Branch Types and Naming Conventions](#branch-types-and-naming-conventions)
- [Release Branches](#release-branches)
- [Commit Standards](#commit-standards)
- [Merge Conflicts](#merge-conflicts)
- [Pull Requests](#pull-requests)
- [Resources](#resources)

## Branch Types and Naming Conventions

A new branch must be created from the main branch according to their purpose.

Prefix all new branches by the type of work and the Jira issue identificator:

| Branch name prefix | Use case |
|--------------------|----------|
| `feature/` | Use for new features or enhancements. (eg. `feature/XXXX-123-component-name`) |
| `bugfix/`  | Use for fixing bugs in existing code. |
| `hotfix/`  | Use for urgent fixes needed in production. |
| `tooling/` | Use for changes impacting automation, scripting or build tooling. |
| `docs/`    | Use for documentation updates - a Jira ticket is not necessary for them. (eg. `docs/local-env`) |

All commits in a branch should belong to that ticket.

Resource: [Naming conventions for Git branches :arrow_upper_right:](https://medium.com/@abhay.pixolo/naming-conventions-for-git-branches-a-cheatsheet-8549feca2534)

## Release Branches

Create a new branch from the main branch such as `release/{yyyymmdd}`. Change the target branch for all the active pull requests to be included in the release from the main branch to this release branch when they have been approved for deployment. Follow the regular deployment workflow and merge the release branch to the branch that deploys to production (main) when confirmed.

## Commit Standards

Convention for commit messages: We are extending the conventional commits ([official website :arrow_upper_right:](https://www.conventionalcommits.org/en/v1.0.0/#summary)) through the inclusion of the Jira ticket identificator, using this format:

[Ticket] Type(Scope): Description

Examples:

- XXXX-123 feat: add user login endpoint
- XXXX-123 chore!: discard embed helper

Resource: [Conventional Comments :arrow_upper_right:](https://conventionalcomments.org/)

## Merge Conflicts

Use the IDE for merging and handling merge conflicts, avoid using GitHub's UI:

- [3-way merge editor in VSCode :arrow_upper_right:](https://code.visualstudio.com/docs/sourcecontrol/overview#_3way-merge-editor)
- [3-way Diff in JetBrains IDEs :arrow_upper_right:](https://www.jetbrains.com/help/idea/resolving-conflicts.html#distributed-version-control-systems)

## Pull Requests

The template PRs start from is accessible at [pull_request_template.md](./pull_request_template.md).

## Resources

- [Engineering Guide for Git Workflows :arrow_upper_right:](#)
- [Engineering Guide for Code Reviews :arrow_upper_right:](#)
