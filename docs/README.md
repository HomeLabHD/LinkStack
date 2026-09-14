# Usage

Running the container image. For what LinkStack is and who builds it, see the
[project README](../README.md) and [Community](community.md).

| | |
|---|---|
| [Docker](docker/) | [docker-compose.yaml](docker/docker-compose.yaml) |
| [Kubernetes](k8s/) | [statefulset.yaml](k8s/statefulset.yaml) · [service.yaml](k8s/service.yaml) |
| [Themes](themes.md) | Previews, and how to add themes |
| [Manual install](manual-install.md) | Installing without Docker, and the in-app updater |

## Quick start

```bash
docker run -d --name linkstack \
  -p 8080:80 \
  -v linkstack:/htdocs \
  -e HTTP_SERVER_NAME=links.example.com \
  -e TZ=Etc/UTC \
  docker.io/hlhd/linkstack:latest
```

Browse to the container and complete the first setup page. That page creates the admin
account and writes the initial configuration into the volume — it only appears once.

## Environment

| Variable | Purpose |
|---|---|
| `HTTP_SERVER_NAME` | Hostname Apache answers on over HTTP |
| `HTTPS_SERVER_NAME` | Hostname Apache answers on over HTTPS |
| `SERVER_ADMIN` | Address shown on Apache error pages |
| `TZ` | Container timezone |
| `PUID` / `PGID` | Ownership applied to `/htdocs` at startup |
| `LOG_LEVEL` | Apache log verbosity |
| `PHP_MEMORY_LIMIT` | PHP memory ceiling |
| `UPLOAD_MAX_FILESIZE` | Largest avatar or background upload accepted |
| `ALLOWED_HOSTS` | Host header allow-list — required when serving several domains |
| `ALLOWED_FRAME_ORIGINS` | Origins permitted to embed this instance in a frame |
| `SESSION_SAME_SITE` | Session cookie policy; `none` is needed to embed across sites |

Set the server name to the hostname users actually reach. LinkStack builds absolute URLs
from it, so a mismatch shows up as broken links and failed redirects after login.

## Single sign-on (OIDC)

This fork adds generic OpenID Connect against any compliant IdP. Point it at an issuer and
the rest is read from `{OIDC_ISSUER}/.well-known/openid-configuration`.

| Variable | Purpose |
|---|---|
| `OIDC_ISSUER` | Issuer URL — discovery supplies the endpoints |
| `OIDC_CLIENT_ID` | Client ID from the IdP |
| `OIDC_CLIENT_SECRET` | Client secret from the IdP |
| `OIDC_SCOPES` | Requested scopes; defaults to `openid profile email` |
| `OIDC_REQUIRE_EMAIL` | Refuse logins whose token carries no email |
| `OIDC_REDIRECT_URL` | Pin one redirect URI instead of deriving it |
| `OIDC_POST_LOGOUT_REDIRECT_URL` | Where the IdP returns the user after logout |

Register `https://<your-host>/social-auth/openidconnect/callback` as the redirect URI.

### Serving several domains

Leave `OIDC_REDIRECT_URL` unset and the redirect is derived **per request**, so one instance
can serve several apexes and each sends users back to the domain they arrived on. Register
the callback for every domain with the IdP. Setting `OIDC_REDIRECT_URL` pins a single one
and disables that behaviour.

Because the callback is derived from the incoming request, every domain you serve must be
listed in `ALLOWED_HOSTS` — an unlisted host is **rejected with a 400**, not trusted. That
rejection is the point: it stops a forged `Host` header aiming the authorization redirect
at somebody else's domain.

```
ALLOWED_HOSTS=bio.example.com,bio.example.org,bio.example.net
```

Empty is the single-domain default: the `APP_URL` domain and its subdomains.

## Embedding in a frame

By default no `frame-ancestors` directive is sent and the instance can be embedded by
anyone. Name the origins allowed to embed it and the restriction is applied, with `'self'`
always kept:

```
ALLOWED_FRAME_ORIGINS=https://apps.example.com
```

Entries are **origins** — scheme, host and optional port. `frame-ancestors` matches on
origin and ignores any path, so `https://apps.example.com/dashboard` is no narrower than
the bare origin.

Embedding across sites also needs the session cookie to survive a third-party context:

```
SESSION_SAME_SITE=none
```

Without it the frame renders but the visitor appears logged out, because the browser
withholds a `lax` cookie inside someone else's page. `none` requires a secure cookie, so
serve over HTTPS. It also removes one layer of CSRF defence-in-depth — the token check
still applies — so set it only when you are actually embedding.

## Ports and storage

| | |
|---|---|
| `80` / `443` | HTTP and HTTPS |
| `/htdocs` | Instance state — database, uploads, themes, config. **Back this up.** |

Terminating TLS at a reverse proxy or ingress and publishing only `80` is fine; set
`HTTPS_SERVER_NAME` to the public name so generated URLs stay correct.

## Database

SQLite works out of the box and lives in the volume — enough for a personal instance.

For MySQL or MariaDB, complete the first setup, then switch the database from the admin
panel. The in-app backup covers the instance files but **not** an external database, so
back that up separately.

## Upgrading

Pull a newer tag and recreate the container. `/htdocs` carries the state across, so the
instance comes back as it was.

```bash
docker pull docker.io/hlhd/linkstack:latest
docker stop linkstack && docker rm linkstack
# re-run with the same -v linkstack:/htdocs
```

The in-app updater also works and takes a backup first — see
[manual-install.md](manual-install.md). With a pinned image tag the two will disagree about
the version, so pick one: update by tag, or update in-app and treat the tag as a floor.

## Health

The image ships a `HEALTHCHECK` that requests the site root. A container stuck `unhealthy`
right after first run usually means setup was never completed — the setup page returns a
redirect the check does not follow.
