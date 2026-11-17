---
name: Marukka
description: A practical developer notebook in navy, magenta and violet.
colors:
  navy: "#071e2b"
  surface: "#0b2332"
  code: "#1b1932"
  magenta: "#f72eb5"
  violet: "#8426bd"
  ink: "#f4f3fc"
  muted: "#bac5d6"
  border: "#68758e"
typography:
  display:
    fontFamily: "Manrope, sans-serif"
    fontSize: "clamp(2.2rem, 4.4vw, 3.65rem)"
    fontWeight: 700
    lineHeight: 1.2
    letterSpacing: "-0.035em"
  body:
    fontFamily: "Manrope, sans-serif"
    fontSize: "16px"
    fontWeight: 400
    lineHeight: 1.65
  code:
    fontFamily: "IBM Plex Mono, monospace"
    fontSize: "0.85rem"
    fontWeight: 400
    lineHeight: 1.85
rounded:
  none: "0px"
spacing:
  small: "12px"
  medium: "20px"
  section: "clamp(36px, 4vw, 60px)"
components:
  button-primary:
    backgroundColor: "{colors.magenta}"
    textColor: "{colors.navy}"
    rounded: "{rounded.none}"
    padding: "12px 26px"
  snippet-card:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.ink}"
    rounded: "{rounded.none}"
    padding: "18px 22px 14px"
---

## Overview

**Creative North Star: "A practical notebook, on a luminous grid."**

The supplied Marukka mockups set the visual language: broad navy surfaces, purple navigation and footer bands, bright magenta actions, and sharply framed editorial cards. The hero artwork creates the expressive moment; readable HTML remains above it.

## Colors

Navy is the page ground. The slightly lighter surface separates snippets without shadows. Violet anchors the header and footer; magenta marks primary actions, active filters and category labels. Code sits on a muted violet-black panel with bright, readable syntax colors.

**The Accent Rule.** Keep magenta concentrated on actions and navigation cues; do not fill every panel with it.

## Typography

Manrope is self-hosted for headings and body. IBM Plex Mono is used for code and the small hero index. Display headings use tight tracking; article prose keeps a comfortable 72-character measure. Section labels retain the widely spaced uppercase treatment from the mockups.

## Layout

The centered shell has a maximum width of 1280px and fluid side gutters. Snippets move from three columns to two, then one. Category artwork moves from six tiles to three, then two. Below 1000px, navigation opens as a fullscreen navy overlay with large single-column links, a full-width search and a visible Close control. The background is inert and scroll-locked while open. The two lower homepage sections stack below 1000px.

## Elevation & Depth

Borders and subtle tonal separation provide depth. Cards have no shadows. The hero and the editorial note carry the supplied design's restrained color gradients.

## Shapes

**The Square Rule.** Cards, controls, code panels and form fields have square corners. Thin borders and straight dividers carry the geometry.

## Components

Snippet cards share a single template for text and code teasers. Previews are compact editorial panels without browser chrome. Category cards put real headings and descriptions over decorative images. Full article code has a language bar, copy control and keyboard-scrollable overflow. Hover changes are restrained; reduced-motion disables transitions and smooth scrolling.

## Do's and Don'ts

- Use semantic Twig components with explicit input data.
- Use native CSS nesting and central custom properties for visual rules.
- Keep Tailwind strings short and focused on layout.
- Preserve readable text and code when JavaScript is unavailable.
- Do not invent article content, metadata, subscription functionality or legal claims.
- Do not add rounded cards, fake browser screenshots or dashboard patterns.
