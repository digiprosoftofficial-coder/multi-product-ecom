# Business model

## What this product is

You sell a ready e-commerce website plus hosting and support. Each client gets their own copy of this app on a domain you control.

You are not selling a self-serve SaaS. The client does not install software, pick themes, or manage servers.

## What the client gets

- A live store on their domain (or a domain you register for them)
- SSL and hosting
- A theme you choose for their niche (grocery, gadget, fashion, general)
- An owner login to add products and manage orders
- A short training session
- Monthly support: updates, backups, small text/banner fixes, help with order issues

## What you keep

- The source code
- Theme selection and visual edits
- Server access, `.env`, database, and deployments
- The right to suspend the site if support/hosting fees stop

Write this in the setup agreement so a client cannot demand the repo or move the site without a migration fee.

## Packages (example — set your own prices)

**Setup (one time)**

- Domain/DNS/SSL if needed
- New isolated install (code + database + `.env`)
- Theme + homepage + logo + payment numbers + delivery zones
- Category skeleton or demo products
- Owner account + 30 minute training

Suggested range: BDT 8,000–15,000.

**Support (monthly)**

- Hosting
- App updates (git pull of agreed branch; never overwrite store-specific `.env` or `filesystems` overrides)
- Backups
- Bug fixes
- Small copy/banner changes

Suggested range: BDT 1,500–3,000 / month.

**Bill separately**

- New theme or large homepage redesign
- Extra pages or custom features
- Product data entry if they will not do it themselves
- Moving the site off your hosting

## What the owner does day to day

- Add / edit / hide products and stock
- Process orders (pending → processing → shipped / cancelled)
- Reply to contact messages
- Check reports

They do **not** change theme, colors, homepage layout, payment methods, or shipping rules unless you later decide to expose a tiny subset (store phone / address only).

## Client record (start with a sheet)

Keep one row per store:

- Store name
- Domain
- Theme slug
- Database name
- Super admin email / owner email
- Setup date
- Support expiry
- Notes (filesystem, payment wallets, special CSS)

No extra admin app is required until you have more than ~10 stores.
