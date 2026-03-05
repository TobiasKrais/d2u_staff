# D2U Staff - Redaxo Addon

A Redaxo 5 CMS addon for managing staff members and companies. Includes multilingual support for positions, citations, expertise, and areas of responsibility. Provides frontend modules for staff lists and author boxes.

## Tech Stack

- **Language:** PHP >= 8.0
- **CMS:** Redaxo >= 5.10.0
- **Frontend Framework:** Bootstrap 4/5 (via d2u_helper templates)
- **Namespace:** `TobiasKrais\D2UStaff`

## Project Structure

```text
d2u_staff/
├── boot.php               # Addon bootstrap (extension points, permissions)
├── install.php             # Installation (database tables, sprog wildcards)
├── update.php              # Update (calls install.php)
├── uninstall.php           # Cleanup (database tables, sprog wildcards)
├── package.yml             # Addon configuration, version, dependencies
├── assets/
│   └── noavatar.jpg        # Placeholder image for staff without picture
├── lang/                   # Backend translations (de_de, en_gb)
├── lib/                    # PHP classes
│   ├── Staff.php           # Staff model (multilingual)
│   ├── Company.php         # Company model
│   ├── LangHelper.php      # Sprog wildcard provider (4 languages)
│   ├── Module.php          # Module definitions and revisions
│   └── D2UStaff.php        # Deprecated wrapper (backward compatibility)
├── modules/                # 3 module variants in group 22
│   └── 22/
│       ├── 1/              # Staff list
│       ├── 2/              # Author box detailed
│       └── 3/              # Author box compact
└── pages/                  # Backend pages
    ├── index.php           # Page router
    ├── staff.php           # Staff management (CRUD, status)
    ├── company.php         # Company management
    ├── settings.php        # Addon settings (sprog languages)
    └── setup.php           # Module manager + changelog
```

## Coding Conventions

- **Namespace:** `TobiasKrais\D2UStaff` for all classes
- **Deprecated:** Global `Staff` class and `D2U_Staff\Company` alias (backward compatibility)
- **Naming:** camelCase for variables, PascalCase for classes
- **Indentation:** 4 spaces in PHP classes, tabs in module files
- **Comments:** English comments only
- **Frontend labels:** Use `Sprog\Wildcard::get()` backed by `LangHelper`, not `rex_i18n::msg()`
- **Backend labels:** Use `rex_i18n::msg()` with keys from `lang/` files

## Key Classes

| Class | Description |
| ----- | ----------- |
| `Staff` | Staff model: name, gender, picture, position, citation, expertise (knows_about), area of responsibility, company assignment, priority, article link, online status. Implements `ITranslationHelper` |
| `Company` | Company model: name, URL, logo. Associated staff members |
| `LangHelper` | Sprog wildcard provider for 4 languages (DE, EN, FR, ES). Wildcards: `d2u_staff_by`, `d2u_staff_published` |
| `Module` | Module definitions and revision numbers for 3 modules |

## Database Tables

| Table | Description |
| ----- | ----------- |
| `rex_d2u_staff` | Staff (language-independent): name, gender, picture, online status, company ID, article ID, priority |
| `rex_d2u_staff_lang` | Staff (language-specific): lang_name, knows_about, area_of_responsibility, position, citation, translation status |
| `rex_d2u_staff_company` | Companies: name, URL, logo |

## Architecture

### Extension Points

| Extension Point | Location | Purpose |
| --------------- | -------- | ------- |
| `CLANG_DELETED` | boot.php (backend) | Cleans up language-specific staff data |
| `D2U_HELPER_TRANSLATION_LIST` | boot.php (backend) | Registers addon in D2U Helper translation manager |
| `MEDIA_IS_IN_USE` | boot.php (backend) | Prevents deletion of media files used by staff |

### Modules

3 module variants in group 22:

| Module | Name | Description |
| ------ | ---- | ----------- |
| 22-1 | D2U Mitarbeiter - Liste | Staff list output |
| 22-2 | D2U Mitarbeiter - Autorenbox Detailinfo | Detailed author box |
| 22-3 | D2U Mitarbeiter - Autorenbox Kurzinfo | Compact author box |

#### Module Versioning

Each module has a revision number defined in `lib/Module.php` inside the `getModules()` method. When a module is changed:

1. Add a changelog entry in `pages/setup.php` describing the change.
2. Increment the module's revision number in `Module::getModules()` by one.

**Important:** The revision only needs to be incremented **once per release**, not per commit. Check the changelog: if the version number is followed by `-DEV`, the release is still in development and no additional revision bump is needed.

## Settings

Managed via `pages/settings.php` and stored in `rex_config`:

- `lang_wildcard_overwrite` — Preserve custom Sprog translations
- `lang_replacement_{clang_id}` — Language mapping per REDAXO language (4 languages)

## Dependencies

| Package | Version | Purpose |
| ------- | ------- | ------- |
| `d2u_helper` | >= 1.14.0 | Backend/frontend helpers, module manager, translation interface |
| `sprog` | >= 1.0.0 | Frontend translation wildcards |

## Multi-language Support

- **Backend:** de_de, en_gb
- **Frontend (Sprog Wildcards):** DE, EN, FR, ES (4 languages)

## Versioning

This addon follows [Semantic Versioning](https://semver.org/). The version number is maintained in `package.yml`. During development, the changelog uses a `-DEV` suffix.

## Changelog

The changelog is located in `pages/setup.php`.
