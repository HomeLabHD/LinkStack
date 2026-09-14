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
