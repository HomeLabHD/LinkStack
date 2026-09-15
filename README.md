<p align="center">
  <img width="200px" src="https://raw.githubusercontent.com/LinkStackOrg/branding/main/logo/svg/logo_animated.svg"><br>
  <br>
  <picture>
    <source media="(prefers-color-scheme: dark)" width="400px" srcset="https://raw.githubusercontent.com/LinkStackOrg/branding/main/logo/png/wordmark_light.png">
    <img width="400px" src="https://raw.githubusercontent.com/LinkStackOrg/branding/main/logo/png/wordmark_dark.png">
  </picture>
</p>

<h3 align="center"><b>Open-Source Linktree Alternative</b></h3>

<h3 align="center">LinkStack is a highly customizable link sharing platform<br>with an intuitive, easy to use user interface.</h3>

<p align="center">
  <a href="#Function">Function</a> •
  <a href="#Philosophy">Philosophy</a> •
  <a href="#Topics">Topics</a> •
  <a href="#Docker">Docker</a> •
  <a href="#License">License</a>
</p>

<p align="center">
<a href="https://github.com/linkstackorg/linkstack/stargazers"><img alt="GitHub Repo stars" src="https://img.tny.st/github/stars/julianprieber/littlelink-custom?label=Star%20the%20project&logo=GitHub"></a>
<a href="https://mstdn.social/@linkstack"><img alt="Mastodon Follow" src="https://img.tny.st/mastodon/follow/110147874401985724?domain=http%3A%2F%2Fmstdn.social&style=social"></a>
<a href="https://discord.linkstack.org"><img alt="Discord online user count" src="https://img.tny.st/discord/955765706111193118?color=4A55CC&label=Discord&logo=Discord&style=flat"></a>
</p>
<p align="center">
<a href="https://github.com/sponsors/julianprieber"><img alt="GitHub sponsors" src="https://img.tny.st/github/sponsors/JulianPrieber?color=BF4B8A&logo=githubsponsors&style=flat&label=Sponsor%20us%20on%20Github"></a>
<a href="https://patreon.com/julianprieber"><img alt="Patreon" src="https://img.tny.st/endpoint.svg?url=https%3A%2F%2Fshieldsio-patreon.vercel.app%2Fapi%3Fusername%3Djulianprieber%26type%3Dpatrons&style=flat&logo=patreon"></a>
<a href="https://liberapay.com/LittleLink-Custom"><img src="https://img.tny.st/liberapay/patrons/LittleLink-Custom?logo=liberapay&label=LiberaPay patrons"></a>
</p>

---

<!-- sf:project:start -->
[![GitHub](https://img.shields.io/badge/GitHub-mirror-181717?logo=github)](https://github.com/HomeLabHD/LinkStack) [![GitLab](https://img.shields.io/badge/GitLab-source-FC6D26?logo=gitlab)](https://gitlab.prplanit.com/HomeLabHD/LinkStack) [![license](https://raw.githubusercontent.com/HomeLabHD/LinkStack/main/.stagefreight/scribe/license.svg)](https://github.com/HomeLabHD/LinkStack/blob/main/LICENSE) [![Open Issues](https://img.shields.io/github/issues/HomeLabHD/LinkStack)](https://github.com/HomeLabHD/LinkStack/issues) [![Open PRs](https://img.shields.io/github/issues-pr/HomeLabHD/LinkStack)](https://github.com/HomeLabHD/LinkStack/pulls) [![Contributors](https://img.shields.io/github/contributors/HomeLabHD/LinkStack)](https://github.com/HomeLabHD/LinkStack/graphs/contributors) [![donate](https://img.shields.io/badge/donate-FF5E5B?logo=ko-fi&logoColor=white)](https://ko-fi.com/T6T41IT163) [![sponsor](https://img.shields.io/badge/sponsor-EA4AAA?logo=githubsponsors&logoColor=white)](https://github.com/sponsors/HomeLabHD)
<!-- sf:project:end -->
<!-- sf:badges:start -->
[![release](https://raw.githubusercontent.com/HomeLabHD/LinkStack/main/.stagefreight/scribe/release.svg)](https://github.com/HomeLabHD/LinkStack/releases) [![build](https://raw.githubusercontent.com/HomeLabHD/LinkStack/main/.stagefreight/scribe/build.svg)](https://gitlab.prplanit.com/HomeLabHD/LinkStack/-/pipelines) [![Last Commit](https://img.shields.io/github/last-commit/HomeLabHD/LinkStack)](https://github.com/HomeLabHD/LinkStack/commits) [![StageFreight](https://img.shields.io/badge/StageFreight-0.12.0--dev+d802e9c-310937?logo=readthedocs&logoColor=white)](https://stagefreight.prplanit.com)
<!-- sf:badges:end -->
<!-- sf:image:start -->
[![GHCR](https://img.shields.io/badge/GHCR-homelabhd%2Flinkstack-181717?logo=github&logoColor=white)](https://github.com/HomeLabHD/LinkStack/pkgs/container/linkstack) [![Docker](https://img.shields.io/badge/Docker-hlhd%2Flinkstack-2496ED?logo=docker&logoColor=white)](https://hub.docker.com/r/hlhd/linkstack) [![pulls](https://raw.githubusercontent.com/HomeLabHD/LinkStack/main/.stagefreight/scribe/pulls.svg)](https://hub.docker.com/r/hlhd/linkstack) [![Harbor](https://img.shields.io/badge/Harbor-hlhd%2Flinkstack-60b932)](https://cr.pcfae.com/harbor/projects)

[![latest](https://raw.githubusercontent.com/HomeLabHD/LinkStack/main/.stagefreight/scribe/release-latest.svg)](https://github.com/HomeLabHD/LinkStack/pkgs/container/linkstack) ![updated](https://raw.githubusercontent.com/HomeLabHD/LinkStack/main/.stagefreight/scribe/release-updated.svg) [![size](https://raw.githubusercontent.com/HomeLabHD/LinkStack/main/.stagefreight/scribe/release-size.svg)](https://github.com/HomeLabHD/LinkStack/pkgs/container/linkstack) [![latest-dev](https://raw.githubusercontent.com/HomeLabHD/LinkStack/main/.stagefreight/scribe/dev-latest.svg)](https://github.com/HomeLabHD/LinkStack/pkgs/container/linkstack) ![updated](https://raw.githubusercontent.com/HomeLabHD/LinkStack/main/.stagefreight/scribe/dev-updated.svg) [![size](https://raw.githubusercontent.com/HomeLabHD/LinkStack/main/.stagefreight/scribe/dev-size.svg)](https://github.com/HomeLabHD/LinkStack/pkgs/container/linkstack)
<!-- sf:image:end -->

> **About this fork.** This is a downstream fork of [LinkStack](https://github.com/LinkStackOrg/LinkStack)
> that exists for two additions: **generic OpenID Connect SSO** against any IdP via discovery,
> and **multi-apex hosting**, where one instance serves several domains and derives the OAuth
> redirect per request. Everything else tracks upstream.
>
> The intent is to offer both upstream. **If they are adopted there, this fork has no reason to
> continue** and maintenance will likely stop — so prefer upstream if it covers your needs.

<a name="Function"></a>
## Function

LinkStack: The Ultimate Link Management Solution

LinkStack is a unique platform that provides an efficient solution for managing and sharing links online. Our platform offers a website similar to Linktree, which allows users to overcome the limitation of only being able to add one link on social media platforms.

With LinkStack, users can easily link to their own custom page and provide their followers with access to all the links they need in one convenient location. What sets LinkStack apart from other link management platforms is its flexibility, which allows users to host their links on their own web server or web hosting provider. This provides users with complete control over their online presence and ensures that their links are easily accessible.

Additionally, LinkStack allows other users to register and create their own links, making it an ideal solution for businesses and organizations looking to manage multiple links. With our user-friendly Admin Panel, managing and accessing other users' links is easy.

<br>

<a name="Philosophy"></a>
## Philosophy

With LinkStack, our mission is to provide users with a free and privacy-focused solution for managing and sharing links online. We believe that everyone should have access to a customizable link-sharing platform without sacrificing their privacy and control over their data.

To achieve this mission, we offer a self-hosted option for users who want complete control over their data without having it sold to third-party companies. Our platform can be easily implemented through a simple **drag and drop** process, eliminating the need for complex terminal commands or source code manipulation.

For those who may not have the technical expertise to self-host, we also offer free instances of our platform while still prioritizing their privacy. Our platform offers many of the same features and options as commercial link-sharing platforms while maintaining the values of privacy and autonomy.

Our goal is to provide a free version of a link-sharing service, similar to Linktree, while empowering users to take ownership of their data. We will never sell user data and believe in providing a trustworthy and transparent solution for managing and sharing links online.

<br>

<a name="Topics"></a>
## Topics

| | |
|-------|-|
| [Usage](docs/README.md) | Environment variables, storage, database, upgrading, health |
| [Docker](docs/docker/) | [docker-compose.yaml](docs/docker/docker-compose.yaml) |
| [Kubernetes](docs/k8s/) | [statefulset.yaml](docs/k8s/statefulset.yaml) · [service.yaml](docs/k8s/service.yaml) |
| [Themes](docs/themes.md) | Previews, and how to add themes to your instance |
| [Community](docs/community.md) | Instances, Discord, supporters, contributors and credits |

<br>

<a name="Docker"></a>
## Docker

<p align="center">
<a href="https://github.com/linkstackorg/linkstack-docker">
<picture>
  <source media="(prefers-color-scheme: dark)" width="600px" srcset="https://raw.githubusercontent.com/LinkStackOrg/branding/main/marketing/docker_edition_dark.png">
  <img width="600px" src="https://raw.githubusercontent.com/LinkStackOrg/branding/main/marketing/docker_edition_light.png">
</picture>
</a>
</p>

The docker version of LinkStack retains all the features and customization options of the [original version](https://github.com/linkstackorg/linkstack). It is based on [Alpine Linux](https://www.alpinelinux.org), a Linux distribution designed to be small, simple and secure. The web server is running [Apache2](https://www.apache.org), a free and open-source cross-platform web server software. The docker comes with [PHP 8.3](https://www.php.net/releases/8_3_0.php) for high compatibility and performance.

#### Using the docker is as simple as pulling and deploying.

```bash
docker pull docker.io/hlhd/linkstack:latest
```

Then browse to the container and complete the first setup page.

[Learn more about the upstream Docker version](https://github.com/LinkStackOrg/linkstack-docker)

<br>

<a name="License"></a>
## License

[![License: AGPL v3](https://img.tny.st/badge/License-AGPL%20v3-blue.svg)](https://www.gnu.org/licenses/agpl-3.0)

As of version 4.0.0, the license for this project has been updated to the GNU Affero General Public License v3.0, which explicitly requires that any modifications made to the project must be made public. This license also requires that a copyright notice and license notice be included in any copies or derivative works of the project.

Additionally, any changes made to the project must be clearly stated, and the source code for the modified version must be made available to anyone who receives the modified version. Network use of the project is also considered distribution, and as such, any network use of the project must comply with the terms of the license.

Finally, any derivative works of the project must be licensed under the same license terms as the original project.

[Read more here](https://www.gnu.org/licenses/agpl-3.0)
