# Database engine and exports

Local DDEV uses **MariaDB 10.11**, matching the old site's MariaDB 10.11 family. Craft continues to use its `mysql` PDO driver; no application driver change is needed.

The project was migrated from MySQL 8.0 on 17 November 2026 using DDEV's supported database migration utility. An explicit SQL backup was saved first; DDEV also created its own recovery snapshot. Only the database engine and version changed in `.ddev/config.yaml`.

## Current export

`storage/backups/marukka-mariadb.sql` is a full native MariaDB export of the new site, including the imported snippets and current editorial changes. At export time it contains **28 snippets: 3 enabled and 25 disabled**. These are the user's current publication states, not the initial all-disabled import state.

Additional recovery files:

- `storage/backups/marukka-current-mysql.sql`: original MySQL dump immediately before migration.
- `storage/backups/marukka-before-mariadb-mysql.sql`: earlier recovery export from this session.
- `storage/backups/marukka-mariadb-compatible.sql`: the MySQL export adapted for MariaDB by changing table definitions from `utf8mb4_0900_ai_ci` to `utf8mb4_unicode_ci`. INSERT statements were not changed.

Use the **native MariaDB export** for the next database transfer. Select the intended target database before importing and back it up first. Full exports include accounts and configuration as well as content; they replace the selected database's state. They do not contain uploaded asset files. Keep the target `.env` and Craft security key configured separately.

All exports remain ignored by Git and excluded from deployment. A Git push deploys code, not these database files.

## Export and import locally

```sh
ddev export-db --gzip=false --file=storage/backups/marukka-mariadb.sql

# Restore only when you intend to replace the local database.
ddev import-db --file=storage/backups/marukka-mariadb.sql
```

Existing clones still using MySQL need a proper migration; changing the YAML engine alone cannot reuse MySQL's binary data volume:

```sh
ddev export-db --file=storage/backups/before-engine-change.sql.gz
ddev utility migrate-database mariadb:10.11
```

## Migration verification

- Running server: MariaDB 10.11.16.
- All **72 compared application tables** retained identical normalized row counts and checksums. Ephemeral cache, session, queue and resource-path tables were excluded from this comparison.
- Snippet counts and enabled/disabled states were preserved.
- Craft Project Config is compatible and has no pending YAML changes.
- The full Craft integration suite passed on MariaDB; its temporary fixtures were rolled back.
- Homepage and snippet archive responded successfully after migration.
- The final export completed and contains no MySQL `utf8mb4_0900_ai_ci` collation.
