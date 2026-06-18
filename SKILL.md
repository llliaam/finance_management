# Frontend Design Skills

Installed from `Leonxlnx/taste-skill` via `npx skills add Leonxlnx/taste-skill`.
Skills live in `.agents/skills/` and are symlinked into Claude Code automatically.

---

## Core Design Skills

### `design-taste-frontend` (default, use this first)
Anti-slop frontend skill for landing pages, portfolios, and redesigns. Reads the brief before touching code, infers the right design direction, and ships interfaces that do not look templated. Enforces three configurable dials (Design Variance / Motion Intensity / Visual Density), a 50-point Pre-Flight Check, and hard bans on AI tells (em-dashes, Inter as default, AI-purple gradients, three equal feature cards, Jane Doe names, etc.).

**Use for:** landing pages, marketing sites, portfolios, redesigns.

### `design-taste-frontend-v1`
The original v1 of the taste-skill, preserved for backward compatibility. Use only when a project specifically depends on its exact behavior. The current default is `design-taste-frontend` (v2).

### `high-end-visual-design`
Teaches the model to design like a high-end agency ($150k+ tier). Defines exact fonts, spacing, shadow systems, nested "Double-Bezel" card architecture, and button-in-button CTA patterns. Bans Inter, Roboto, generic borders, and standard easing curves. Applies a Creative Variance Engine (Ethereal Glass / Editorial Luxury / Soft Structuralism) to ensure non-repeating output.

**Use for:** premium SaaS, luxury/lifestyle brands, agency-tier builds.

### `minimalist-ui`
Clean editorial-style interfaces. Warm monochrome palette, typographic contrast, flat bento grids, muted pastels. No gradients, no heavy shadows. Optimized for calm, airy, content-forward layouts.

**Use for:** editorial sites, personal portfolios, documentation-style pages.

### `industrial-brutalist-ui`
Raw mechanical interfaces fusing Swiss typographic print with military terminal aesthetics. Rigid grids, extreme type-scale contrast, utilitarian color, analog degradation effects.

**Use for:** data-heavy dashboards, portfolios, or editorial sites that need to feel like declassified blueprints.

### `gpt-taste`
Elite UX/UI and advanced GSAP motion engineer. Enforces AIDA page structure, wide editorial typography, gapless bento grids, strict GSAP ScrollTriggers (pinning, stacking, scrubbing), inline micro-images, and massive section spacing.

**Use for:** high-motion SaaS landing pages, scroll-heavy storytelling pages.

---

## Image Generation Skills

### `imagegen-frontend-web`
Generates one separate horizontal design-reference image per section. A landing page with 8 sections produces 8 images. Enforces composition variety, background-image freedom, varied CTAs, and a single consistent palette across all images.

**Use for:** generating web design references before implementation.

### `imagegen-frontend-mobile`
Generates premium mobile app screen concepts and flows. Designed for iOS/Android/cross-platform. Prioritizes clean hierarchy, multi-screen consistency, controlled color palettes, and phone mockup framing. Generates images only — does not write code.

**Use for:** mobile app design references, screen flows.

### `image-to-code`
Generates design images first, deeply analyzes them, then implements the website to match as closely as possible. Prefers large, section-specific images over compressed boards, avoids lazy under-generation, and keeps heroes clean and spacious.

**Use for:** when you want image-first implementation fidelity.

---

## Workflow & Utility Skills

### `redesign-existing-projects`
Upgrades existing websites and apps to premium quality. Audits current design, identifies generic AI patterns, and applies high-end design standards without breaking functionality. Works with any CSS framework or vanilla CSS.

**Use for:** modernizing existing sites rather than greenfield builds.

### `stitch-design-taste`
Generates agent-friendly `DESIGN.md` files that enforce premium, anti-generic UI standards — strict typography, calibrated color, asymmetric layouts, perpetual micro-motion, and hardware-accelerated performance. Designed for Google Stitch agents.

**Use for:** creating a reusable design system spec file for a project.

### `brandkit`
Generates high-end brand-guideline boards, logo systems, identity decks, and visual-world presentations. Optimized for intentional logo concepting, refined composition, sparse typography, strong symbolic meaning, premium mockups, and art-directed imagery.

**Use for:** brand identity work, logo systems, visual identity decks.

### `full-output-enforcement`
Overrides default LLM truncation behavior. Enforces complete code generation, bans placeholder patterns ("// ... rest of component"), and handles token-limit splits cleanly.

**Use for:** any task requiring exhaustive, unabridged code output — apply alongside any other skill.

---

## Quick Reference

| Skill | Best for |
|---|---|
| `design-taste-frontend` | Landing pages, portfolios, redesigns (default) |
| `high-end-visual-design` | Premium/agency-tier builds |
| `minimalist-ui` | Editorial, airy, content-forward |
| `industrial-brutalist-ui` | Data-heavy, brutalist, terminal aesthetic |
| `gpt-taste` | Scroll-heavy, GSAP-driven pages |
| `imagegen-frontend-web` | Web design reference images |
| `imagegen-frontend-mobile` | Mobile screen references |
| `image-to-code` | Image-first implementation |
| `redesign-existing-projects` | Modernizing existing sites |
| `stitch-design-taste` | Generate a DESIGN.md spec |
| `brandkit` | Brand identity and logo systems |
| `full-output-enforcement` | Force complete code output |
| `design-taste-frontend-v1` | Legacy v1 compatibility only |
