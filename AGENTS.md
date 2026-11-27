# AGENTS.md

Rules for every change to this repository, for agents and developers. What the repository is: [README.md](README.md). How it is built and run: the docs under [docs/](docs/).

## Branches and pull requests

- Branches in this repository: `master` is the released code, `staging` is where finished work waits for approval, `feature/LMM-<n>` carries one ticket, `release/<dd.mm.yyyy>` carries a release. A feature branch is cut from `master`.
- When a ticket also changes the base plugin ([leanpay-integration](https://github.com/magebitcom/leanpay-integration)), the feature branch has the same name in both repositories.
- A feature branch goes into `staging` by pull request and stays unmerged into `master` until the release. When the pull request conflicts with `staging`, cut `feature/LMM-<n>-PR_STAGING` from `staging`, merge the feature branch into it and open the pull request from there, so the feature branch stays clean (Magebit developers: the `resolve-merge-conflict` skill runs this flow).
- A release branch is cut from `master`, takes the approved feature branches and goes into `master` by pull request, followed by a version bump, release notes, a tag and a Packagist check: [release](docs/operations/release.md).
- Both `master` and `staging` require a pull request and the Portal's Magebit Review check, and forbid force pushes and deletion.
- Commit subjects in the history are `LMM-<n> <subject in the imperative mood>`; nothing enforces the form.

## Rules for the code

- The module draws what the base plugin computes and adds no payment logic. A calculation, a setting or a server route belongs in the base plugin.
- A call to a base plugin method that a recent base release added is guarded (`method_exists`), and the `leanpay/payment` constraint in `composer.json` is raised in the same change. The two packages are released and deployed separately, and a submodule setup does not enforce the constraint.
- Alpine directive values in templates are bare method or property names, never calls or expressions, and each inline script is registered with Hyva's CSP helper. Hyva's CSP build blocks anything else ([architecture](docs/architecture/README.md), Alpine and CSP).
- Style with Tailwind classes and the base plugin's colour variables. A new class shows only after a Tailwind build of the shop ([troubleshooting](docs/operations/troubleshooting.md)).
- The widget's texts are the base plugin's source phrases, and their translations live in the base plugin. Add a new text there.
- The module has no automated tests. Check a change on a Hyva shop before the pull request ([testing](docs/testing/README.md)).
- A Hyva test shop runs `master` of this module, so a change on `staging` or a feature branch is not there ([testing](docs/testing/README.md), Manual testing).

## Documentation

Docs under `docs/` must stay up to date with the code, and this is enforced: the
PR gate fails when changed code is covered by a doc that
was not updated with it (`doc.untouched`), when a covered file changed after
the doc was last checked (`review.stale`), or when a module belongs to no doc
(`covers.unclaimed`). Update the docs on the same branch as the code. The PR
gate runs on pull requests into `staging` and `master`. No hook manager is
set up: a developer who wants the same check on every local commit creates the
commit hook as [development](docs/operations/development.md), Hooks and checks, says.
The checks a change meets are in [testing](docs/testing/README.md).

Every change to `docs/`, a one-line fix included, goes through the
`write-project-docs` skill (magebit-skills), which picks its mode from the
request, or, without the skill, through `docs/MAINTAINING.md`. Do either
once the code change is done, before you commit; until then, leave `docs/`
alone. Never edit `docs/` any other way, and add `Docs-Reviewed` lines only
once the doc has been checked against the change.
Check with `docs/.health-checker/run`.

The release notes under `docs/releases/` are kept history: a release adds a file there, and the docs check skips the folder.

Where a fact goes, before you save it anywhere:

| A fact that is... | Goes to |
|---|---|
| A business rule, or what the client can change | The guide under `docs/functionality/` |
| How the module hooks into Hyva, or what it replaces from the base plugin | `docs/architecture/README.md` |
| What the base plugin or Leanpay sends, receives or owns | The base plugin's repository ([leanpay-integration](https://github.com/magebitcom/leanpay-integration)); the Outside systems table of `docs/architecture/README.md` for what reaches this module |
| A development step or a trap in working on the module | `docs/operations/development.md` |
| A release step | `docs/operations/release.md` |
| A symptom, its cause and its fix | `docs/operations/troubleshooting.md` |
| How a change is tested, and which shop runs which branch | `docs/testing/README.md` |
| What a past release changed | `docs/releases/` |
| A fact true for every Magebit project | The company skill that owns it, or the Magebit Brain |
| A personal preference | Your own memory |
| The state of a task | The ticket |
