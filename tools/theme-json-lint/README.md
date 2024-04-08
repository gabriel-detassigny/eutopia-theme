# Linting for theme.json

This theme provides linting for `theme.json` properties.

You can lint the `theme.json` file by running `npm run lint:theme-json`

## Schemas

There are various schema files in the `schemas/` directory:

- `theme-json-official-schema.json` — The [official schema](https://schemas.wp.org/trunk/theme.json) as provided by Core ([#](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/#developing-with-theme-json)) to validate theme.json
- `theme-json-extra-validation.json` — Additional validation rules for theme.json

## Validation

The linting process is performed in the following order:

1. Validate default `theme.json` properties as per `theme-json-official-schema.json`
2. Validate extra properties as per `theme-json-extra-validation.json`
3. Custom validation rules declared on `src/theme-json-linter.mjs`

Results from both linting operations are displayed together.