# How pricing works: Elo, team prices, performance, and player prices

This document explains how the pricing building blocks fit together:

1. **Elo ratings** — how strong is a team, according to the outside world?
2. **Team prices** (`ffb_teamprice`) — what does a player from that team cost as a baseline?
3. **Matchround performance** (`playerstats_round_performance`) — how well did a player do in one round, on a −1…+1 scale?
4. **Recent performance** (`playerprice_recent_performance`) — how well has a player done lately, as a weighted average of recent rounds?
5. **Player price** (`playerprice_price`) — the final per-player price used to build lineups.

Everything is run from **Admin → Preise** (`/admin/playerprice`), which has three tabs: **Team-Preis**, **Spieler-Performance**, and **Recent Performance**. Every tab follows the same rule: **calculate first (preview only), then save.** Nothing is written to the database until you press *Speichern*.

---

## The big picture

```
  eloratings.net                     match results (points per player)
        |                                          |
        v                                          v
  [1] Elo rating per team               [3] Matchround performance
        |                                   rank within position -> -1..+1
        v                                          |
  [2] Team price per matchround                    v
      ffb_teamprice                       [4] Recent performance
        |                                     decayed average of the
        |                                     last N rounds -> -1..+1
        |                                          |
        |  baseline                                |  adjustment
        +-------------------+----------------------+
                            v
        [5] player price = max(1.0, team price + adjustment)
                            |
                            v
            ffb_playerprice -> lineup credits, Preisverlauf chart
```

In one sentence: **Elo decides what a team is worth, the team price is the player's baseline, and recent form pushes the individual player above or below that baseline.**

---

## Where the numbers live

| Table / column | Meaning | Written by |
| --- | --- | --- |
| `ffb_teamprice.teamprice_price` | Price of a team in one matchround | Tab **Team-Preis** → *Preise speichern* |
| `ffb_playerstats.playerstats_round_performance` | One player's result in one round, −1…+1 | Tab **Spieler-Performance** → *Speichern* |
| `ffb_playerprice.playerprice_recent_performance` | Player's recent form, −1…+1 | Tab **Recent Performance** → *Speichern* |
| `ffb_playerprice.playerprice_price` | Final player price | Tab **Recent Performance** → *Speichern* with *Spielerpreise speichern* ticked |

A team price always belongs to one team **and** one matchround. A player price always belongs to one playerteam **and** one matchround. That is what lets prices move from round to round.

---

## Step 1: Elo ratings

Elo ratings come from [eloratings.net](https://www.eloratings.net) and are fetched live by `EloRatingClient`:

- `World.tsv` gives rating per team code.
- `en.teams.tsv` maps team codes to English names and aliases.
- `resources/data/elo/teams.csv` maps those English names to FFB `team_id`s. An exact match on the local `ffb_team.team_name` also works, and the CSV wins on conflict.

Teams with no Elo mapping are **skipped**, not guessed. The preview lists them by name under *"… Team(s) ohne ELO-Zuordnung wurden übersprungen"*. If you see a team there, add an alias line to `resources/data/elo/teams.csv` (this is how e.g. *Nordmazedonien → Macedonia* is resolved).

The feed URLs are configurable in `config/ffb.php` under `ffb.elo`. The historical backfill described below can override the ratings URL per run, which is how a completed tournament gets the ratings of its own era (e.g. `2015.tsv`) instead of today's.

---

## Step 2: Elo → team prices (tab "Team-Preis")

The goal is not "convert Elo points into euros". The goal is: **make the strongest possible lineup unaffordable, and a sensible lineup affordable.** The math works backwards from the budget.

### The formula

For every team in the league (or, if you pick a matchround, every team playing in that round):

1. **Normalise the Elo rating to 0…1** inside this group of teams:

   ```
   normalized = (elo − min_elo) / (max_elo − min_elo)
   ```

   The weakest team gets `0`, the strongest gets `1`. (If all teams have the same rating, everyone gets `0.5`.)

2. **Apply the exponent** to control how steep the price curve is:

   ```
   raw_weight = normalized ^ exponent
   ```

   Exponent `1` is a straight line. The default `2` means top teams cost disproportionately more, and midfield teams stay cheap.

3. **Build the imaginary "Dream-Team"**: greedily take the highest-weight teams, respecting *Max. Spieler / Team*, until the squad is full (`lineup_max_players`, default 11). Sum their raw weights → `dream_raw_cost`.

4. **Decide what the Dream-Team should cost** and scale everything to fit:

   ```
   dream_target_cost = budget × dream_team_ratio      // e.g. 100 × 1.5 = 150
   dream_base_cost   = squad_size × min_price         // e.g. 11 × 1.0  = 11
   variable_budget   = dream_target_cost − dream_base_cost
   scale             = variable_budget / dream_raw_cost
   ```

5. **Price each team:**

   ```
   price = round(min_price + raw_weight × scale, 1)
   ```

Note that the minimum price is a **base offset**, not a clamp applied afterwards. The weakest team lands exactly on `min_price`, and every other team is lifted above it proportionally. A post-hoc clamp would squash many weak teams onto the same price; this way they stay distinguishable.

### Parameters

| Field | Default | Effect |
| --- | --- | --- |
| *Max. Credits / Aufstellung* | from league/matchround options (100) | The budget everything is scaled against |
| *Max. Spieler / Team* | from league/matchround options (3) | Caps how many players the Dream-Team may take from one team |
| *Exponent* | 2.0 | Higher = steeper gap between strong and weak teams |
| *Dream-Team-Ratio* | 1.5 | How unaffordable the best possible team should be (1.5 = 150% of budget) |
| *Mindestpreis* | 1.0 | Price of the weakest team |

### The three sanity checks

The preview runs three simulated lineups and shows whether each lands in its target corridor:

| Check | Lineup | Target | What it tells you |
| --- | --- | --- | --- |
| `dream_team` | Best teams only | **>** budget | The best possible squad must be out of reach |
| `median_lineup` | Mid-table teams | **60–80%** of budget | An average squad should leave room to spend |
| `good_lineup` | Up to 2 players from each of the 2 best teams, rest from the cheapest | **90–100%** of budget | A strong-but-realistic squad should just about fit |

If the Dream-Team is affordable, raise the ratio. If the median lineup is too expensive, lower the ratio or the exponent.

### Saving

*Preise speichern* writes `ffb_teamprice` for:

- the **selected future matchround**, or
- **all future matchrounds** of the league if no matchround is selected.

Past rounds are deliberately not selectable in the UI — team prices for a round that has already been played would rewrite history. For seeding completed tournaments there is a dedicated command:

```bash
php artisan ffb:backfill-em2016-teamprices --league-id=25 --elo-url=https://www.eloratings.net/2015.tsv --min-price=4
```

It uses exactly the same formula but writes **every** matchround of the league, including past ones. It is a dry run unless you pass `--execute`.

---

## Step 3: Matchround performance (tab "Spieler-Performance")

This turns raw fantasy points from one round into a comparable −1…+1 score.

Why not use the points directly? Because points are not comparable across positions: a goalkeeper and a striker earn points in completely different ways. So players are **ranked within their own position**.

The peer set is not limited to the selected round. Every player with minutes > 0 at that position in **all earlier league matchrounds plus the selected one** enters the ranking. That way a final with only two goalkeepers does not automatically map them to −1 and +1 — their scores are judged against the whole tournament so far.

### The formula

Only players with **minutes > 0** are included.

1. Collect all appearances for the position from league rounds with `matchround_startdate` ≤ the selected round.
2. Sort that group by points, worst first, and assign ranks `0 … n−1`.
3. Equal points share the **average** of the ranks they occupy (mid-rank), so ties are never broken arbitrarily.
4. Map the rank onto −1…+1 (only the selected round’s players are shown/saved):

   ```
   round_performance = (rank / (n − 1)) × 2 − 1
   ```

   With only one appearance at a position in the whole window (`n = 1`), the result is `0` — one sample alone cannot be ranked.

Examples with 3 midfielder appearances: worst → `−1`, middle → `0`, best → `+1`. With 4: `−1`, `−0.333`, `+0.333`, `+1`.

### Optional: opponent strength

A great game against the tournament favourite is worth more than a great game against the weakest side. Tick *"Gegnerstärke (ELO) einbeziehen"* to account for that. It uses **Elo from `ffb_teamelo` for the league** (not matchround team prices). `min_elo` / `max_elo` are taken from **all teams in the league**, so a final between two close sides does not inflate the factor to ±1:

```
opponent_factor   = (opponent_elo − own_elo) / (max_elo − min_elo)
round_performance = clamp(raw_round_performance + OPPONENT_WEIGHT × opponent_factor, −1, +1)
```

`opponent_factor` is `+1` only when the lowest-Elo team in the league plays the highest-Elo one (and `−1` in the reverse case). With the default `OPPONENT_WEIGHT = 0.25`, the correction is at most ±0.25.

Example: the worst-ranked defender (`raw = −1`) of the lowest-Elo team faced the highest-Elo team, so `opponent_factor = +1` and the stored value becomes `−1 + 0.25 × 1 = −0.75`.

The checkbox only appears when **all** teams of the league already have `ffb_teamelo` rows — the correction is meaningless otherwise. Fill Elo via Team-Preis *Speichern* or the team-elo backfill.

### Saving

*Speichern* recalculates with the same options and writes `playerstats_round_performance` (rounded to 3 decimals) for every listed player. Players who did not play keep `NULL`; "did not play" is intentionally different from "played badly".

---

## Step 4: Recent performance → player price (tab "Recent Performance")

One round is noise. This step smooths several rounds into a form value and turns it into money.

### Picking the lookback rounds

Take up to `LOOKBACK_ROUNDS` (default 5) matchrounds that started **before** the selected round, newest first.

Optionally tick *"Ligaübergreifende Vor-Runden einbeziehen"*. If the league itself does not have enough earlier rounds (typical at the start of a tournament), earlier rounds in which the **same teams** played in another league are pulled in and merged chronologically. Only external rounds where every minutes > 0 entry already has a `round_performance` are eligible, so a half-processed round cannot pollute the average.

A player's history is looked up by `player_id`, not by `playerteam_id`, so his results carry over even though he has a separate squad row per league.

### The weighting

Round `i` (0 = newest) gets weight `DECAY_FACTOR ^ i`. With the default `0.7` and 5 rounds:

```
1.0, 0.7, 0.49, 0.343, 0.2401
```

The key detail is the denominator:

```
recent_performance = Σ (round_performance[i] × weight[i])  for rounds played
                     ───────────────────────────────────────────────────────
                                 Σ weight[i]  for ALL lookback rounds
```

Missed rounds stay in the denominator (and are shown as `-` in the table). A player who played one great game is therefore **not** rated as highly as one who played five great games — sitting out dilutes your form instead of being ignored.

### From form to price

```
price_adjustment = recent_performance × MAX_PRICE_ADJUSTMENT      // default 2.0
player_price     = max(1.0, round(team_price + price_adjustment, 1))
```

`team_price` is that player's team price **for the selected round**. So a perfect `+1` form adds the full `MAX_PRICE_ADJUSTMENT` on top of the team price, `−1` subtracts it, and `0` leaves the player exactly at his team's price. Prices are rounded to one decimal and never drop below `1.0`.

### Worked example

Three lookback rounds, `DECAY_FACTOR = 0.7`, `MAX_PRICE_ADJUSTMENT = 2.0`, team price `8.0`. The player scored `+1`, `+0.5`, `−1` (newest first):

```
weights            = 1, 0.7, 0.49            → Σ = 2.19
numerator          = 1×1 + 0.5×0.7 + (−1)×0.49 = 0.86
recent_performance = 0.86 / 2.19             = 0.393
price_adjustment   = 0.393 × 2.0             = 0.786
player_price       = max(1.0, round(8.0 + 0.786, 1)) = 8.8
```

### Preconditions

The calculation refuses to run unless:

- every minutes > 0 entry of the **selected** round already has a saved `round_performance` (step 3 must be done first), and
- **all** teams playing in that round have a team price (step 2 must be done first).

Both are hard requirements because the price formula reads both values directly. The error message names which one is missing.

The first condition also means the selected round must be one that has already been played and processed in **Spieler-Performance**. Prices are therefore stored per played round: the price attached to round N reflects the form the player brought *into* round N.

Only active squad players (`playerteam_status = 1`) of teams that actually have a match in the selected round are priced.

### Saving

*Speichern* always writes `playerprice_recent_performance`. The checkbox **"Spielerpreise speichern"** (off by default) additionally writes `playerprice_price`. This separation lets you record form now and publish prices later, once you are happy with `MAX_PRICE_ADJUSTMENT`.

---

## Step 5: How the prices are used

**Lineups.** In leagues with `options_league_pricemode = dynamic`, `LineupService` charges credits per player for the selected matchround:

1. `ffb_playerprice.playerprice_price` if a row exists for that player and round;
2. otherwise `ffb_teamprice.teamprice_price` of the player's team.

That fallback is why a league is playable as soon as team prices exist: everyone from a team simply costs the team price until individual prices are published.

**Player modal, "Preisverlauf" tab.** The chart draws two curves per matchround:

- the black **Preiskurve** from `playerprice_price`;
- the red **Leistungskurve** from `playerstats_round_performance`, mapped onto a percentage scale where `−1 → 0%` and `+1 → 100%` (so `0` sits at 50%). Rounds without a stored value are skipped.

---

## Defaults at a glance

| Constant | Default | Where |
| --- | --- | --- |
| `EXPONENT` | 2.0 | Team price curve steepness |
| `DREAM_TEAM_RATIO` | 1.5 | Dream-Team target cost as a multiple of the budget |
| `MIN_PRICE` | 1.0 | Price of the weakest team; also the hard floor for player prices |
| `SQUAD_SIZE` | 11 | From `lineup_max_players` |
| `OPPONENT_WEIGHT` | 0.25 | Max. shift from opponent strength |
| `LOOKBACK_ROUNDS` | 5 | Rounds included in recent performance |
| `DECAY_FACTOR` | 0.7 | Weight decay per round going back |
| `MAX_PRICE_ADJUSTMENT` | 2.0 | Max. price swing from perfect/terrible form |

---

## Typical run order for a new matchround

1. **Team-Preis** — calculate, check the three corridors, save for the upcoming round(s).
2. Play the round and import results.
3. **Spieler-Performance** — pick the played round, optionally enable opponent strength, calculate, save.
4. **Recent Performance** — pick that same round, calculate, review the table, save (tick *Spielerpreise speichern* when the prices should go live).

Steps 3 and 4 always happen in that order: recent performance is an average of stored round performances, so a round that was never saved simply does not exist for it.

---

## Where the code lives

| Concern | File |
| --- | --- |
| All pricing logic | `app/Services/AdminPlayerpriceService.php` |
| Elo fetching and team-name mapping | `app/Services/EloRatingClient.php`, `resources/data/elo/teams.csv` |
| Admin screen | `app/Http/Controllers/Admin/AdminPlayerpriceController.php`, `resources/views/admin/playerprice.blade.php` |
| Lineup credit resolution | `app/Services/LineupService.php` |
| Preisverlauf data and chart | `app/Services/PlayerPopupService.php`, `public/js/player-modal.js` |
| Historical team price backfill | `app/Console/Commands/Ffb/BackfillEm2016TeampricesCommand.php` |
| Tests | `tests/Feature/AdminPlayerprice*Test.php`, `tests/Feature/PlayerPopupPricesTest.php`, `tests/Feature/EloRatingClientTest.php` |
