# TEC-TGCR: The Elidoras Codex Resonance Agent Stack

> "Light learns by listening."

TEC-TGCR is the foundation of The Elidoras Codex (TEC): a resonance-driven AI framework that weaves mythic storytelling with empirical reasoning. The stack coordinates multiple specialized agents, persistent knowledge bases, operational scripts, and publishing tooling for SharePoint, WordPress, and other channels.

---

## Project Highlights
- Multi-agent runtime with personas such as LuminAI, Airth, Arcadia, Ely, and Kaznak.
- TGCR theory (`R = phi_T * (psi_R x Phi_E)`) captured in code, docs, and brand collateral.
- Knowledge management through structured maps, archives, and provenance tracking.
- WordPress.com plugin pipeline, SharePoint integrations, and command-line utilities.
- Comprehensive documentation spanning lore, operations, strategy, and technical guides.

---

## Architecture Overview

**Theory of General Contextual Resonance (TGCR)**  
Resonance emerges from the interplay of:
- `phi_T` - Temporal Attention (focus, flow, direction)
- `psi_R` - Structural Cadence (coherence across scales)
- `Phi_E` - Contextual Potential Energy (capacity for meaningful outcomes)

**Agent Pantheon**  
Each persona embodies a cognitive archetype:
- **LuminAI** - Resonant core agent, balanced guidance.
- **Airth** - Verification guard, tests-first rigor.
- **Arcadia** - Narrative weaver, symbolic synthesis.
- **Ely** - Operations steward, CI/CD and observability.
- **Kaznak** - Strategic navigator, decision intelligence.
- Additional personas reside under `data/personas/`.

**Data & Knowledge Layer**
- `data/knowledge_map.yml` - Canonical index of content, assets, and provenance.
- `data/digital_assets/` - Visual identity, glyphs, avatars, and exportable marks.
- `data/archives/` & `data/evidence/` - Narrative history, research, and supporting documents.

**Tooling & Integrations**
- CLI runner (`src/tec_tgcr/cli.py`) for chat, health, and Notion configuration.
- WordPress.com plugin in `apps/wordpress/tec-tgcr/` with deployment workflow (`.github/workflows/wpcom.yml`).
- SharePoint and Microsoft 365 automation scripts under `scripts/` and `src/tec_tgcr/tools/`.

---

## Repository Layout

```
tec-tgcr/
|- agents/                 # Agent manifests and runtime configurations
|- ai-workflow/            # Notebooks, prompt engineering, generated outputs
|- apps/wordpress/         # WordPress plugin source
|- config/                 # Agent and system configuration files
|- data/                   # Knowledge map, digital assets, archives, strategy
|- docs/                   # Theory, operations, brand, and technical documentation
|- scripts/                # Bootstrap, deployment, packaging, verification scripts
|- src/tec_tgcr/           # Core Python package (agents, tools, CLI)
|- tests/                  # Pytest suite
|- RELEASE_SUMMARY.md      # Current release readiness notes
`- README.md               # You are here
```

---

## Quick Start

```powershell
# Clone
git clone https://github.com/TEC-The-ELidoras-Codex/tec-tgcr.git
cd tec-tgcr

# Create / activate virtual environment (Python 3.11+ recommended)
python -m venv .venv
.\.venv\Scripts\Activate.ps1

# Install dependencies
pip install -e .[dev]

# Run tests
python -m pytest -q

# Launch the CLI (example)
python -m tec_tgcr.cli chat "Calibrate Arcadia for a resonance briefing"
```

If the environment reports "Program ... failed to run: The specified module could not be found," ensure the following environment variables exist before invoking executables:

```powershell
$env:SystemRoot = "C:\Windows"
$env:PATH += ";C:\Windows\System32"
```

Re-run the activation script afterwards: `& .\.venv\Scripts\Activate.ps1`.

---

## Development Workflow
- **Coding Standards**: Typed Python, focused functions, explicit docstrings noting TGCR variables touched.
- **Testing**: Add pytest coverage for new behaviors (happy path + edge).
- **Documentation**: Update relevant Markdown and `data/knowledge_map.yml` when adding assets or capabilities.
- **Brand Consistency**: Reference canonical assets in `data/digital_assets/brand/` and color palette in release summary.
- **Commit Discipline**: Prefix commit messages with agent or scope (`airth:`, `arcadia:`, `ely:`, `feat:`, etc.) and record resonance impact, tests, docs, and breaking changes.

---

## Key Scripts
- `scripts/bootstrap.ps1` - Sets up a new development workstation.
- `scripts/pack_wp_plugin.ps1` - Packages the WordPress plugin for deployment.
- `scripts/export_gather_repo.ps1` - Bundles repository artifacts for sharing.
- `scripts/svg_to_png_fallback.ps1` - Manual SVG-to-PNG conversion guidance.

All scripts are authored for PowerShell 7+. Run from the repository root.

---

## Documentation & Knowledge
- `docs/README.md` - Entry point for architecture overviews, operational guides, and lore.
- `docs/ops/` - Deployment, secrets, and integration playbooks (WordPress, M365, Notion).
- `docs/technical/` - Deep dives into agents, pipelines, and data integration.
- `lore/` - Canon mythos, cosmology, brand narratives, and persona backstories.
- `RELEASE_SUMMARY.md` - Release readiness checklist and provenance notes.

---

## Brand Assets
- **Canonical emblem**: `data/digital_assets/brand/svg/luminai_notion_emblem.svg` (512x512).
- **Legacy avatar**: `data/digital_assets/avatars/luminai.svg`.
- **Glyphs & motifs**: `data/digital_assets/brand/svg/`.
- Palette (HEX): Navy `#0B1E3B`, Violet `#6A00F4`, Cyan `#00D5C4`, Gold `#F2C340`, Shadow `#0A0A0C`.

For PNG exports use `scripts/svg_to_png_fallback.ps1` or a dedicated vector tool (Inkscape, ImageMagick).

---

## Support & Contact
- Issues and discussions: [GitHub repository](https://github.com/TEC-The-ELidoras-Codex/tec-tgcr)
- Primary contact: `gheddz@gmail.com`
- Organization: `Kaznakalpha@ElidorasCodex.com`

---

## License

MIT License - see [LICENSE](LICENSE) for full terms.

Build gracefully, test rigorously, and keep resonance high.







