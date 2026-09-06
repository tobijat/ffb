# Player-facing XML AJAX endpoints

Derived from `script/ffb/*.js` and `script/user/*.js`.  
URL form: `{module}/{class}/{event}.xml` → presenter `xml`.

| JS consumer | Method | URL | Auth | Key query/body params | Expected XML tags (JS reads) |
|-------------|--------|-----|------|----------------------|------------------------------|
| login.js | POST | users/login/loginAjax.xml | none | user_nickname, user_password | administration_status; on 200: administration_destination; on error: errors/XML_Serializer_Tag |
| login.js | POST | users/registration/getPassword.xml | none | user_nickname, user_email | user_status; user_answer or errors |
| lineup_v2.js | GET | ffb/options/getLineupOptions.xml | user | — | options: lineup_max_players, lineup_max_credits, lineup_max_players_team, lineup_min_g/d/m/s, lineup_max_g/d/m/s |
| lineup_v2.js | GET | ffb/lineup/getMatchroundAndTeams.xml | user | — | matchround_id, matchround_title, matchround_status, matchround_startdate, matchround_enddate, matchround_deadline, matches, teams |
| lineup_v2.js | GET/POST | ffb/team/getTeamPlayers.xml | user | id, matchround_id | player list tags (playerteam_id, positions, prices) |
| lineup_v2.js | POST | ffb/userteam/getUserteamForRound.xml | user | matchround_id | userteam fields / player tags when lineup exists |
| lineup_v2.js | POST | ffb/teammanagement/saveLineup.xml | user | matchround_id, lineup, sum_price | ffb_status, ffb_answer, ffb_error |
| myteam_v2.js | GET | ffb/matchround/getPastAndRunningMatchrounds.xml | user | — | matchround_id, matchround_title, matchround_startdate, matchround_enddate, matchround_actual, matchround_running, matches |
| myteam_v2.js | POST | ffb/user/getUsersWithTeams.xml | user | matchround_id | XML_Serializer_Tag users |
| myteam_v2.js | POST | ffb/userteam/getUserteamForRound.xml | user | matchround_id, userteam_user_id | userteam + players |
| userscore_v2.js | GET | ffb/matchround/getPastAndRunningMatchrounds.xml | user | — | (same as myteam) |
| userscore_v2.js | POST | ffb/userscore/getUserscoresForRound.xml | user | matchround_id, sort_flag, sort_dir | userscore list tags |
| userscore_v2.js | GET | ffb/userscore/getUserscore.xml | user | — | userscore aggregate tags |
| bestteam_v2.js | GET | ffb/matchround/getPastMatchrounds_v2.xml | user | — | matchround list |
| bestteam_v2.js | POST | ffb/bestteam/getBestTeam.xml | user | matchround_id, type | best-team player tags |
| matchdata(_v2).js | POST | ffb/matchdata/getMatchData.xml | user | match_id | match_data + nested player/goal tags |
| playerinfo.js | GET | ffb/options/getLineupOptions.xml | user | — | (same options) |
| playerinfo.js | POST | ffb/player/getPlayerInfo.xml | user | player params | player info tags |
| playerinfo.js | POST | ffb/player/getPlayerStats.xml | user | player params | stats tags |
| poll.js | GET | ffb/poll/getPolls.xml | user | — | polls |
| poll.js | POST | ffb/poll/getSelectPollById.xml | user | poll_id | poll detail |
| poll.js | POST | ffb/poll/savePollSelectAnswer.xml | user | poll_id, poll_answer_id | status |
| poll.js | POST | ffb/poll/savePollTextAnswer.xml | user | poll_id, poll_answer_id, poll_answer | status |
| news.js | POST | ffb/news/getNewsList.xml | user | selected_site | news list |
| awards.js / userprofile.js | POST | ffb/awards/getAllUserAwards.xml | user | user_id | group, awardGroupCount, award |
| userprofile.js | POST | ffb/user/getUserDetails.xml | user | user_id | user + participations |
| statistics.js | POST | ffb/statistics/getUserStats.xml | user | matchround_id, user_id | user stats |
| statistics.js | POST | ffb/statistics/getRoundStats.xml | user | matchround_id | round stats |
| gamemgmt.js | GET | ffb/game/checkSelectedGame.xml | user | — | administration_status, selected_game_id |
| gamemgmt.js | GET | ffb/game/getGameList.xml / getPastGames.xml | user | — | games / XML_Serializer_Tag |
| gamemgmt.js | POST | ffb/game/setSelectedGame.xml | user | game_id | administration_status, administration_answer |

## Admin endpoints (script/admin)

| JS | URL | Key tags |
|----|-----|----------|
| matchpoints.js | administration/matchround/getList.xml | matchrounds |
| matchpoints.js | administration/match/getMatchesForRound.xml | matches |
| matchpoints.js | administration/matchpoints/getPlayerStatsForTeam.xml | player stats |
| matchpoints.js | administration/matchpoints/setMatchresult.xml / setGoalData.xml / setPlayerStats.xml | administration_status |
| playertoteam.js | administration/team/getList.xml | teams |
| playertoteam.js | administration/playertoteam/getTeamPlayers.xml | players |
| playertoteam.js | administration/player/getPartList.xml | players |
| awards.js | administration/awards/*.xml | userAward, answer/status |
| game.js / gamemgmt.js | administration/game/getGameList.xml, setSelectedGame.xml, checkSelectedGame.xml, getGamesForAdmin.xml | games, status |
| mailservice.js | administration/mailservice/*.xml | numResults, status |
