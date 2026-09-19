# Error Page System

BravePay uses one reusable cute tiger error page for Laravel HTTP errors.

## Supported statuses

400, 401, 403, 404, 408, 409, 419, 422, 429, 500, 502, 503, 504.

## Architecture

- Central mapping: `config/error_pages.php`
- Shared layout and responsive styling: `resources/views/errors/layout.blade.php`
- Reusable local tiger illustration: `resources/views/errors/components/tiger.blade.php`
- Laravel status wrappers: `resources/views/errors/{status}.blade.php`

Laravel automatically renders the matching view for an HTTP exception. The shared layout can also be reused directly with a status code:

```blade
@include('errors.layout', ['statusCode' => 404])
```

The illustration is built locally with CSS and Blade, so it has no external image dependency and can be replaced later by updating the tiger component.
