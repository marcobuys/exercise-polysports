# PolySports — Wholesale Back-office

PolySports is a small **B2B wholesale back-office** for a football-boot brand.
You're acting as an **account manager placing a bulk order for a business
customer**:

1. **Pick the customer** you're ordering for (their pricing tier sets the discount).
2. **Browse the catalogue (PLP)** and enter quantities across EU sizes in the
   order grid — wholesale buyers order many sizes at once.
3. **Review the mini-cart** at the top of the screen.
4. **Checkout** submits the order to the database and shows a confirmation.

The stack mirrors our day-to-day work: **Laravel (PHP) + Vue 3 + MySQL**, all
running in **Docker**.

## Running it

You need Docker Desktop (or Docker Engine) with Compose. Then, from the repo root:

```bash
./up.sh
```

The first run pulls images, builds the container, installs PHP + Node
dependencies and seeds the database — that takes a few minutes. When it's done:

- App: <http://localhost:8000>
- API (example): <http://localhost:8000/api/products>

Handy commands:

```bash
docker compose logs -f app        # tail application logs
docker compose exec app sh        # shell into the app container
docker compose exec app php artisan tinker
```

## Code map

A quick orientation so you can find your way around fast:

- **API routes** — `routes/api.php`
- **Controllers** — `app/Http/Controllers/` (`ProductController`, `OrderController`, `CustomerController`)
- **API output shaping** — `app/Http/Resources/ProductResource.php`
- **Models / relations** — `app/Models/` (`Product`, `ProductVariant`, `Order`, `OrderItem`, `Customer`)
- **Seed data** — `database/seeders/DatabaseSeeder.php`
- **Vue app entry** — `resources/js/app.js` → `resources/js/components/App.vue` (holds the selected customer + cart state)
- **Vue views/components** — `resources/js/components/` (`CustomerSelect`, `ProductList` = PLP, `ProductDetail` = PDP, `OrderGrid`, `MiniCart`, `Checkout`)
- **Money helpers** — `resources/js/money.js`
- **Tests** — `tests/Feature/`

## Tests

```bash
docker compose exec app php artisan test
```

**Some tests fail on purpose** — they describe behaviour the application
*should* have but currently doesn't. Read each failing test to understand the
intended behaviour; getting them green is part of the exercise (but not the
only thing worth fixing).

## What we're looking for

This is a **debugging exercise**. We care about how you work, not a perfect score:

- **Reproduce before fixing** — confirm the problem first.
- **Explain the root cause** — out loud, as if to a teammate or a non-technical
  stakeholder.
- **Fix it, and say how you'd prevent it recurring.**
- **Prioritise** — you almost certainly won't get to everything, and that's fine.
- **Think out loud.** Using docs and web search is completely fine.

Good luck, and enjoy poking around.
