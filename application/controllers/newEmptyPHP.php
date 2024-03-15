<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Weekly extends CI_Controller {

    public function weeklyEntry() {
        $username = $this->session->userdata("userdata")["name"];
        $data["current_week"] = $this->GetCurrentWeek();
        $data["users"] = $this->admin_model->dbSelect("username", "users", " 1 ");
        $data["InWeeklyPool"] = $this->InWeeklyPool($username, $data["current_week"]);
        $data["GetGameCount"] = $this->GetGameCount($data["current_week"]);
        $data["GetCompletedGameCount"] = $this->GetCompletedGameCount($data["current_week"]);
        $data["GetWeeklyPickScore"] = $this->GetWeeklyPickScore($username, $data["current_week"]);
        $data["GetWeeklyTiebreakerGuess"] = $this->GetWeeklyTiebreakerGuess($username, $data["current_week"]);
        $data["DisplayOpenDates"] = $this->DisplayOpenDates($data["current_week"]);
        //List links to view other weeks.
        $otherParams = "";
        if ($this->session->userdata("userdata")["role"] == "admin") {
            $otherParams = "username=" . $this->session->userdata["userdata"]["name"];
        }
        $data["DisplayGoToNavigation"] = $this->DisplayGoToNavigation("week", $this->GetWeekCount(), $otherParams);
        $this->load->view("weekly_entry", $data);
    }

    public function weeklyResults() {
        $data["week"] = $this->GetRequestedWeek();
        $data["numGames"] = $this->GetGameCount($data["week"]);
        $data["tbTarget"] = $this->GetWeeklyTiebreakerActual($data["week"]);
        $data["tbGuess"] = $this->GetWeeklyTiebreakerGuess($this->session->userdata("userdata")["name"], $data["week"]);
        $data["allLocked"] = IsWeekLocked($data["week"]);
        $data["numEntries"] = $this->GetWeeklyEntryCount($data["week"]);
        $data["pot"] = $this->GetWeeklyPotAmount($data["week"]);
        $username = $this->session->userdata("userdata")["name"];
        if ($username != "") {
            if ($data["tbTarget"] == "" && $data["tbGuess"] != "") {
                $sql = "SELECT COUNT(*) AS Total"
                        . " FROM pl_weekly_picks, pl_schedule"
                        . " WHERE username = '" . $username . "'"
                        . " AND week = " . $data["week"]
                        . " AND pl_weekly_picks.game_id = pl_schedule.game_id"
                        . " AND pick != ''";
                $res = $this->admin_model->dbQuery($sql);
                if (count($res) > 0) {
                    if ($res[0]->Total < $data["numGames"]) {
                        $data["error"] = "Warning: You are missing one or more picks for this week.";
                    }
                }
            }
        }

        //echo $data["week"]; die();

        $data["weeklyWinners"] = $this->GetWeeklyWinnersList($data["week"]);
        $data["completedGames"] = $this->GetCompletedGameCount($data["week"]);
        $data["weeklyPlayers"] = $this->GetWeeklyPlayersList($data["week"]);
        $data["weekCount"] = $this->GetWeekCount();

        //---------users----------//
        $users = array();
        $this->load->model("userobj");
        if (count($data["weeklyPlayers"]) > 0) {
            foreach ($data["weeklyPlayers"] as $key => $value) {
                $u = new userobj();
                $u->name = $value->username;
                $u->pickScore = $this->GetWeeklyPickScore($value->username, $data["week"]);
                $u->confScore = $this->GetWeeklyConfidenceScore($value->username, $data["week"]);
                $u->tbGuess = $this->GetWeeklyTiebreakerGuess($value->username, $data["week"]);
                array_push($users, $u);
            }
        }
        $data["users"] = $users;

        //---------users----------//

        $this->load->view("weekly_results", $data);
    }

    /* -------------------------------------------------------------------------
      Returns the points a player would receive based on the projected outcome
      of a given game.
      ------------------------------------------------------------------------- */

    function GetAdditionalScore($picksRs, $username, $gameID, $correctPick) {

        //dim sql, rs
        //dim pick, conf

        $GetAdditionalScore = 0;

        if ($gameID != "") {

            //Search the picks for the given user and game.
            if (count($picksRs) > 0) {
                foreach ($picksRs as $value) {
                    if ($value->username == $this->session->userdata("userdata")["name"] && $value->game_id) {

                        //Get the score for this pick based on the given
                        //outcome and return it.
                        $pick = $value->pick;
                        $conf = $value->confidence;
                        if (is_numeric($conf)) {
                            if (!ALLOW_TIE_PICKS && $correctPick == TIE_STR) {
                                $GetAdditionalScore = $conf / 2;
                            } else if ($pick == $correctPick) {
                                $GetAdditionalScore = $conf;
                            }
                        }
                    }
                }
            }
        }
        return $GetAdditionalScore;
    }

    /* -------------------------------------------------------------------------
      ' Removes weekly player scoring and winner data stored in the database for
      ' the week specified.
      '
      ' Note: This should be called anytime a change is made to a weekly pool
      ' entry or any time a regular season game is updated. I.e., any change to
      ' the pl_weekly_picks or pl_schedule tables.
      '------------------------------------------------------------------------ */

    function ClearWeeklyResultsCache($week) {
        $this->common_model->update_where("pl_weekly_scoring", array("week" => $week), array("pick_score" => null, "confidence_score" => null));
        //Remove the winners from the WeeklyWinners table.
        $sql = "DELETE FROM pl_weekly_winners WHERE week=" . $week;
        $this->admin_model->dbDelete($sql);
    }

    /* -------------------------------------------------------------------------
      ' Returns the number of users who made picks for the specified week.
      '------------------------------------------------------------------------ */

    function GetWeeklyEntryCount($week) {

        $GetWeeklyEntryCount = 0;
        $res = $this->admin_model->dbSelect("count(*) as total", "weekly_scoring", " week='$week' ");
        if (count($res) > 0) {
            $GetWeeklyEntryCount = $res[0]->total;
        }
        return $GetWeeklyEntryCount;
    }

    /* --------------------------------------------------------------------------
      ' Gets the week number specified in the HTTP request. If no valid number
      ' was specified, the current week is returned instead.
      '------------------------------------------------------------------------- */

    function GetRequestedWeek() {
        //Default to the current week.
        $GetRequestedWeek = $this->GetCurrentWeek();

        //Check the week number specified in the HTTP request.
        $week = "null";
        if (isset($_GET["week"])) {
            $week = $_GET["week"];
        } else {
            $week = $GetRequestedWeek;
        }

        /* if (!is_int($week)){
          return false;
          }else{
          $week = Round($week);
          if ($week < 1 || $week > $this->GetWeekCount()) {
          return false;
          }
          } */
        return $GetRequestedWeek = $week;
    }

    /* -------------------------------------------------------------------------
      ' Returns the number of correct picks made by the specified user for the
      ' given week. If the user did not enter any picks that week, an empty
      ' string is returned.
      '------------------------------------------------------------------------ */

    function Getpl_weekly_pickscore($username, $week) {

        $Getpl_weekly_pickscore = "";

        //If the user made no picks for that week, exit.
        if (!$this->InWeeklyPool($username, $week)) {
            return false;
        }

        //See if we have the score already calculated and saved in the database.
        $res = $this->admin_model->dbSelect("pick_score", "weekly_scoring", " username='$username' AND week='$week' AND pick_score IS NOT NULL ");
        if (count($res) > 0) {
            $Getpl_weekly_pickscore = $res[0]->pick_score;
        }

        //Determine which result field to use.
        $resultField = "result";
        if (USE_POINT_SPREADS) {
            $resultField = "ats_result";
        }

        //Total the number of correct picks.
        $sql = "SELECT COUNT(*) AS Total"
                . " FROM pl_weekly_picks, pl_schedule"
                . " WHERE pl_weekly_picks.username = '" . $username . "'"
                . " AND pl_schedule.Week = " . $week
                . " AND pl_weekly_picks.game_id = pl_schedule.game_id"
                . " AND pl_weekly_picks.pick = pl_schedule." . $resultField
                . " AND IS NOT NULL(pl_weekly_picks." . $resultField . ")";
        $pp = $this->admin_model->dbQuery($sql);

        if (count($pp) > 0) {
            $Getpl_weekly_pickscore = $pp[0]->Total;
        }

        //If tie picks are not allowed, add a half point for any tied games.
        if (!ALLOW_TIE_PICKS) {
            $sql = "SELECT COUNT(*) AS Total"
                    . " FROM pl_weekly_picks, pl_schedule"
                    . " WHERE pl_weekly_picks.username = '" . $username . "'"
                    . " AND pl_schedule.week = " . $week
                    . " AND pl_weekly_picks.game_id = pl_schedule.game_id"
                    . " AND pl_weekly_picks.pick != ''"
                    . " AND pl_schedule." . $resultField . " = '" . TIE_STR . "'";
            $p = $this->admin_model->dbQuery($sql);
            if (count($p) > 0) {
                foreach ($p as $val) {
                    $tieTotal = $val->Total;
                }

                if (is_int($tieTotal)) {
                    $Getpl_weekly_pickscore = $Getpl_weekly_pickscore + $tieTotal / 2;
                }
            }
        }

        //To improve performance, save the score in the database.
        $this->common_model->update_where("pl_weekly_scoring", array("username" => $username, "week" => $week), array("pick_score" => $Getpl_weekly_pickscore));
        return $Getpl_weekly_pickscore;
    }

    /* -------------------------------------------------------------------------
      ' Returns the confidence score for picks made by the specified user for the
      ' given week. If the user did not enter any picks that week, an empty
      ' string is returned.
      '------------------------------------------------------------------------ */

    function GetWeeklyConfidenceScore($username, $week) {

        //dim sql, rs, resultField, tieTotal

        $GetWeeklyConfidenceScore = "";

        //If the user made no picks for that week, exit.
        if (!$this->InWeeklyPool($username, $week)) {
            return false;
        }

        //See if we have the score already calculated and saved in the database.
        $sql = "SELECT confidence_score FROM pl_weekly_scoring"
                . " WHERE username = '" . $username . "'"
                . " AND week = " . $week
                . " AND confidence_score IS NOT NULL ";
        $res = $this->admin_model->dbQuery($sql);

        if (count($res) > 0) {
            $GetWeeklyConfidenceScore = $res[0]->confidence_score;
        }

        //Determine which result field to use.
        $resultField = "result";
        if (USE_POINT_SPREADS) {
            $resultField = "ats_result";
        }

        //Total the confidence points for each correct pick.
        $sql = "SELECT SUM(confidence) AS Total"
                . " FROM pl_weekly_picks, pl_schedule"
                . " WHERE Username = '" . $username . "'"
                . " AND pl_schedule.Week = " . $week
                . " AND pl_weekly_picks.game_id = pl_schedule.game_id"
                . " AND pl_weekly_picks.Pick = pl_schedule." . $resultField
                . " AND pl_schedule." . $resultField . " IS NOT NULL ";
        $ress = $this->admin_model->dbQuery($sql);
        if (count($ress) > 0) {
            $GetWeeklyConfidenceScore = $ress[0]->Total;
            if (!is_int($GetWeeklyConfidenceScore)) {
                $GetWeeklyConfidenceScore = 0;
            }
        }

        //If tie picks are not allowed, add half points for any tied games.
        if (!ALLOW_TIE_PICKS) {
            $sql = "SELECT SUM(confidence) AS Total"
                    . " FROM pl_weekly_picks, pl_schedule"
                    . " WHERE pl_weekly_picks.username = '" . $username . "'"
                    . " AND pl_schedule.week = " . $week
                    . " AND pl_weekly_picks.game_id = pl_schedule.game_id"
                    . " AND pl_schedule." . $resultField . " = '" . TIE_STR . "'";
            $res1 = $this->admin_model->dbQuery($sql);

            if (count($res1) > 0) {
                $tieTotal = $res1[0]->Total;
                if (is_int($tieTotal)) {
                    $GetWeeklyConfidenceScore = $GetWeeklyConfidenceScore + $tieTotal / 2;
                }
            }
        }

        //To improve performance, save the score in the database.
        $this->common_model->update_where("pl_weekly_scoring", array("username" => $username, "week" => $week), array("confidence_score" => $GetWeeklyConfidenceScore));
        return $GetWeeklyConfidenceScore;
    }

    /* -------------------------------------------------------------------------
      ' Returns an array of users currently in the given week's pool.
      '------------------------------------------------------------------------ */

    function GetWeeklyPlayersList($week) {

        $GetWeeklyPlayersList = array();
        $res = $this->admin_model->dbSelect("username", "weekly_scoring", " week='$week' ORDER BY username ");

        if (count($res) > 0) {
            foreach ($res as $val) {
                array_push($GetWeeklyPlayersList, $val);
            }
        }

        return $GetWeeklyPlayersList;
    }

    /* -------------------------------------------------------------------------
      ' Returns the amount in the pot for the given week.
      '------------------------------------------------------------------------ */

    function GetWeeklyPotAmount($week) {

        $GetWeeklyPotAmount = $this->GetWeeklyEntryCount($week) * WEEKLY_ENTRY_FEE;
        if (ENABLE_OVERALL_WEEKLY) {
            $GetWeeklyPotAmount = (1 - OVERALL_WEEKLY_SHARE) * $GetWeeklyPotAmount;
        }
        return $GetWeeklyPotAmount;
    }

    /* -------------------------------------------------------------------------
      ' Returns the actual point total of the last pl_scheduled game of the week
      ' specified. If those scores are not available, an empty string is
      ' returned.
      '------------------------------------------------------------------------ */

    function GetWeeklyTiebreakerActual($week) {

        $GetWeeklyTiebreakerActual = "";
        $res = $this->admin_model->dbSelect("(sum(visitor_score) + sum(home_score)) AS Total", "schedule", " week='$week' ORDER BY date DESC, time DESC ");

        if (count($res) > 0) {
            if (!is_int($res[0]->Total)) {
                return false;
            }
            $GetWeeklyTiebreakerActual = $res[0]->Total;
        }

        return $GetWeeklyTiebreakerActual;
    }

    /* -------------------------------------------------------------------------
      ' Returns the specified user's tiebreaker guess for the given week. An
      ' empty string is returned if no value is found in the database.
      '------------------------------------------------------------------------ */

    function GetWeeklyTiebreakerGuess($username, $week) {

        $GetWeeklyTiebreakerGuess = "";
        $res = $this->admin_model->dbSelect("tiebreaker", "weekly_scoring", " username='$username' AND week='$week' ");

        if (count($res) > 0) {
            $GetWeeklyTiebreakerGuess = $res[0]->tiebreaker;
        }

        return $GetWeeklyTiebreakerGuess;
    }

    /* -------------------------------------------------------------------------
      ' Returns the date and time of the first game pl_scheduled for the given week.
      '------------------------------------------------------------------------ */

    function GetWeeklyStartDateTime($n) {

        $GetWeeklyStartDateTime = now();
        $res = $this->admin_model->dbSelect("date,time", "schedule", " week='$n' ORDER BY date, time");
        if (count($res) > 0) {
            $GetWeeklyStartDateTime = $res[0]->date . " " . $res[0]->time;
        }
    }

    /* -------------------------------------------------------------------------
      ' Determines the winner of the given weekly pool based on picks. An array
      ' of those player names is returned. If the pool has not been concluded, an
      ' empty string is returned instead.
      '------------------------------------------------------------------------ */

    function GetWeeklyWinnersList($week) {

        $list = array();
        $GetWeeklyWinnersList = "";

        //Exit if not all games have been completed.
        if ($this->GetGameCount($week) != $this->GetCompletedGameCount($week)) {
            return false;
        }

        //See if we have the winners already calculated and stored in the
        //database.
        $res = $this->admin_model->dbSelect("*", "weekly_winners", " week='$week' ORDER BY username ");
        if (count($res) > 0) {
            foreach ($res as $val) {
                array_push($list, $val->username);
            }
        }

        if (!empty($list)) {
            return $GetWeeklyWinnersList = $list;
        }

        //Get point total for the tiebreaker, exit if not available.
        $tbTarget = $this->GetWeeklyTiebreakerActual($week);
        if (!is_numeric($tbTarget)) {
            return false;
        }

        //Initialize current high score and tiebreaker difference.
        $highScore = -1;
        $lowDiff = 0;

        //Check each player who has an entry.
        $res1 = $this->admin_model->dbSelect("*", "weekly_scoring", " week='$week' ORDER BY username ");
        if (count($res1) > 0) {
            foreach ($res1 as $val1) {
                $username = $val1->username;

                //Find the player's score and tiebreaker.
                if (USE_CONFIDENCE_POINTS) {
                    $score = $val1->confidence_score;
                    if ($score == NULL) {
                        $score = $this->GetWeeklyConfidenceScore($username, $week);
                    } else {
                        $score = $val1->pick_score;
                        if ($score == NULL) {
                            $score = Getpl_weekly_pickscore($username, $week);
                        }
                    }
                }
                $tb = $val1->Tiebreaker;
                if (is_numeric($score) && is_numeric($tb)) {

                    //Compare this player's score to the current highest.
                    $diff = Abs($tbTarget - $tb);

                    //If this player has a higher score, or the same score and a
                    //closer tiebreaker, make the player the winner.
                    if ($score > $highScore || ($score == $highScore && $diff < $lowDiff)) {
                        $list[0] = $username;
                        $highScore = $score;
                        $lowDiff = $diff;
                    }
                    //Otherwise, if this player has the same score and same
                    //tiebreaker difference, add the player to the winners list.
                    else if ($score == $highScore && $diff == $lowDiff) {
                        $list[sizeof($list) + 1] = $username;
                    }
                }
            }
        }

        //To improve performance, store the winners in the database. A separate
        //table named WeeklyWinners is used for this.
        if ($list != NULL) {
            foreach ($list as $key => $value) {
                $data = array("week" => $week, "username" => $value[$key]);
                $this->common_model->insert("pl_weekly_winners", $data);
            }
        }

        $GetWeeklyWinnersList = $list;
        return $GetWeeklyWinnersList;
    }

    /* -------------------------------------------------------------------------
      ' Returns the number of games scheduled for the specified week.
      '------------------------------------------------------------------------ */

    function GetGameCount($week) {
        $GetGameCount = 0;
        $ci = & get_instance();
        $sql = "SELECT COUNT(*) AS Total FROM pl_schedule WHERE week = " . $week;
        $result = $ci->admin_model->dbQuery($sql);
        if (count($result) > 0) {
            $GetGameCount = $result[0]->Total;
        }
        return $GetGameCount;
    }

    /* -------------------------------------------------------------------------
      ' Returns the number of completed games (i.e., games with a result)
      ' for the specified week.
      '------------------------------------------------------------------------- */

    function GetCompletedGameCount($week) {
        $GetCompletedGameCount = 0;
        $result = $this->admin_model->dbSelect("count(*) as total", "schedule", " week='$week' AND result IS NOT NULL ");
        if (count($result) > 0) {
            $GetCompletedGameCount = $result[0]->total;
        }
        return $GetCompletedGameCount;
    }

    /* -------------------------------------------------------------------------
      ' Returns an array of all users who have at least one weekly pool entry.
      '------------------------------------------------------------------------ */

    function GetAllWeeklyPlayersList() {

        $GetAllWeeklyPlayersList = "";
        $res = $this->admin_model->dbSelect("DISTINCT(username)", "weekly_scoring", " 1 ORDER BY username ");
        if (count($res) > 0) {
            foreach ($res as $val) {
                array_push($list, $val->username);
            }
        }

        return $GetAllWeeklyPlayersList = $list;
    }

    /* -------------------------------------------------------------------------
      ' Determines the winner of the overall weekly pool based on picks. An array
      ' of those player names is returned. If not all weekly pools have been
      ' concluded, an empty string is returned instead.
      '------------------------------------------------------------------------ */

    function GetOverallPickWinnersList() {

        $users = array();
        $score;
        $highScore;
        $list = array();

        $GetOverallPickWinnersList = "";

        //Make sure the overall pool is enabled all games have been completed.
        if (!$this->IsRegularSeasonComplete()) {
            exit;
        }

        //Ensure all weekly scores have been calculated.
        $this->SetAllWeeklyScores();

        //Get a list of users.
        $users = array();
        $users = GetAllWeeklyPlayersList();
        if ($users == NULL) {
            exit;
        }

        //Initialize the current high score.
        $highScore = -1;

        //Find the best total score among the players.
        for ($i = 0; $i < count($users); $i++) {

            //Get the player's total score.
            $res = $this->admin_model->dbSelect("SUM(pick_score) AS Total", "weekly_scoring", " username='$users[$i]' ");
            if (count($res) > 0) {
                $score = $res[0]->Total;

                //If this player has a higher score, make the player the winner.
                if ($score > $highScore) {
                    $list[0] = $users[$i];
                    $highScore = $score;
                }
                //Otherwise, if this player has the same score, add the player
                //to the winners list.
                else if ($score == $highScore) {
                    array_push($list, $users[$i]);
                    //$list(sizeof($list)+1) = $users[$i];
                }
            }
        }

        if ($list != NULL) {
            $GetOverallPickWinnersList = $list;
        }
        return $GetOverallPickWinnersList;
    }

    /* -------------------------------------------------------------------------
      ' Determines the winner of the overall weekly pool based on confidence
      ' points. An array of those player names is returned. If not all weekly
      ' pools have been concluded, an empty string is returned instead.
      '------------------------------------------------------------------------ */

    function GetOverallConfidenceWinnersList() {

        $users = array();
        $score;
        $highScore;
        $list = array();

        $GetOverallConfidenceWinnersList = "";

        //Make sure the overall pool is enabled all games have been completed.
        if (!$this->IsRegularSeasonComplete()) {
            return false;
        }

        //Ensure all weekly scores have been calculated.
        $this->SetAllWeeklyScores();

        //Get a list of users.
        $users = $this->GetAllWeeklyPlayersList();
        if ($users == NULL) {
            return false;
        }

        //Initialize the current high score.
        $highScore = -1;

        //Find the best total score among the players.
        for ($i = 0; $i < count($users); $i++) {

            //Get the player's total score.
            $res = $this->admin_model->dbSelect("SUM(confidence_score) AS Total", "weekly_scoring", " username='$users[$i]' ");

            if (count($res) > 0) {
                $score = $res[0]->Total;

                //If this player has a higher score, make the player the winner.
                if ($score > $highScore) {
                    $list[0] = $users[$i];
                    $highScore = $score;
                }
                //Otherwise, if this player has the same score, add the player
                //to the winners list.
                else if ($score == $highScore) {
                    array_push($list, $users[$i]);
                    //$list(sizeof($list) + 1) = $users[$i];
                }
            }
        }

        if ($list != NULL) {
            $GetOverallConfidenceWinnersList = $list;
        }
        return $GetOverallConfidenceWinnersList;
    }

    /* --------------------------------------------------------------------------
      ' Returns the current week number based on the current (time zone adjusted)
      ' date and time.
      '------------------------------------------------------------------------- */

    public function GetCurrentWeek() {
        $GetCurrentWeek = 0;
        $dateTime = date("Y-m-d h:i:s");
        $result = $this->admin_model->dbSelect("game_id, week, date", "schedule", " 1 ORDER BY date ");


        if (count($result) > 0) {
            $found = false;
            foreach ($result as $res) {
                $d1 = new DateTime($dateTime);
                $d2 = new Datetime($res->date);
                $diff = $d2->diff($d1);

                if ($diff->d <= 0) {
                    $found = true;
                    $GetCurrentWeek = $res->week;
                }
            }
        }


        if ($GetCurrentWeek == 0) {
            $GetCurrentWeek = $this->GetWeekCount();
        }
        return $GetCurrentWeek;
    }

    /* -------------------------------------------------------------------------
      ' Returns the total number of weeks in the schedule.
      '------------------------------------------------------------------------ */

    public function GetWeekCount() {

        $GetWeekCount = 0;
        $result = $this->admin_model->dbSelect("max(week) as total", "schedule", " 1 ");

        if (count($result) > 0) {
            $GetWeekCount = $result[0]->total;
        }
        return $GetWeekCount;
    }

    /* -------------------------------------------------------------------------
      ' Returns the current amount in the pot for the overall weekly pool.
      '------------------------------------------------------------------------ */

    function GetOverallPotAmount() {

        $GetOverallPotAmount = 0;

        //Find the total number of entries so far.
        $week = $this->GetCurrentWeek();
        $res = $this->admin_model->dbSelect("COUNT(*) AS Total", "weekly_scoring", " week='$week' ");
        if (count($res) > 0) {
            $GetOverallPotAmount = OVERALL_WEEKLY_SHARE * WEEKLY_ENTRY_FEE * $res[0]->Total;
        }
        return $GetOverallPotAmount;
    }

    /* -------------------------------------------------------------------------
      ' Returns true if the given user has an entry for the specified week.
      '------------------------------------------------------------------------ */

    function InWeeklyPool($username, $week) {

        $InWeeklyPool = false;
        if ($this->GetWeeklyTiebreakerGuess($username, $week) != "") {
            $InWeeklyPool = true;
        }
        return $InWeeklyPool;
    }

    /* -------------------------------------------------------------------------
      ' Returns true if the all games for the regular season have been completed.
      '------------------------------------------------------------------------ */

    function IsRegularSeasonComplete() {

        $IsRegularSeasonComplete = false;
        $res = $this->admin_model->dbSelect("COUNT(*) AS Total", "schedule", " result IS NULL ");
        if (count($res) > 0) {
            if ($res[0]->Total == 0) {
                $IsRegularSeasonComplete = true;
            }
        }
        return $IsRegularSeasonComplete;
    }

    /* -------------------------------------------------------------------------
      ' This sub routine will ensure that all weekly player scores are calculated
      ' and cached in the pl_weekly_scoring table.
      '------------------------------------------------------------------------ */

    function SetAllWeeklyScores() {

        $res = $this->admin_model->dbSelect("username, week", "weekly_scoring", " pick_score IS NULL ");
        if (count($res) > 0) {
            foreach ($res as $key => $value) {
                $score = $this->Getpl_weekly_pickscore($value->username, $res->week);
                if (USE_CONFIDENCE_POINTS) {
                    $score = $this->GetWeeklyConfidenceScore($value->username, $value->Week);
                }
            }
        }
    }

    /* -------------------------------------------------------------------------
      ' Returns the number of correct picks made by the specified user for the
      ' given week. If the user did not enter any picks that week, an empty
      ' string is returned.
      '------------------------------------------------------------------------ */

    function GetWeeklyPickScore($username, $week) {


        $GetWeeklyPickScore = "";

        //If the user made no picks for that week, exit.
        if (!$this->InWeeklyPool($username, $week)) {
            return false;
        }

        //See if we have the score already calculated and saved in the database.
        $res = $this->admin_model->dbSelect("pick_score", "weekly_scoring", " username='$username' AND week='$week' AND pick_score IS NOT NULL ");
        if (count($res) > 0) {
            $GetWeeklyPickScore = $res[0]->pick_score;
        }

        //Determine which result field to use.
        $resultField = "result";
        if (USE_POINT_SPREADS) {
            $resultField = "ats_result";
        }

        //Total the number of correct picks.
        $sql = "SELECT COUNT(*) AS Total"
                . " FROM pl_weekly_picks, pl_schedule"
                . " WHERE pl_weekly_picks.username = '" . $username . "'"
                . " AND pl_schedule.week = " . $week
                . " AND pl_weekly_picks.game_id = pl_schedule.game_id"
                . " AND pl_weekly_picks.pick = pl_schedule." . $resultField
                . " AND pl_schedule." . $resultField . " IS NOT NULL ";

        $res1 = $this->admin_model->dbQuery($sql);

        if (count($res1) > 0) {
            $GetWeeklyPickScore = $res1[0]->Total;
        }

        //If tie picks are not allowed, add a half point for any tied games.
        if (!ALLOW_TIE_PICKS) {
            $sql = "SELECT COUNT(*) AS Total"
                    . " FROM pl_weekly_picks, pl_schedule"
                    . " WHERE pl_weekly_picks.username = '" . $username . "'"
                    . " AND pl_schedule.week = " . $week
                    . " AND pl_weekly_picks.game_id = pl_schedule.game_id"
                    . " AND pl_weekly_picks.pick != ''"
                    . " AND pl_schedule." . $resultField . " = '" . TIE_STR . "'";
            $res2 = $this->admin_model->dbQuery($sql);
            if (count($res2) > 0) {
                $tieTotal = $res2[0]->Total;
                if (is_numeric($tieTotal)) {
                    $GetWeeklyPickScore = $GetWeeklyPickScore + $tieTotal / 2;
                }
            }
        }

        //To improve performance, save the score in the database.
        $this->common_model->update_where("pl_weekly_scoring", array("username" => $username, "week" => $week), array("pick_score" => $GetWeeklyPickScore));
    }

    /* -------------------------------------------------------------------------
      ' Displays a list of teams with open dates for the specified week.
      '------------------------------------------------------------------------ */

    function DisplayOpenDates($week) {

        $team = array();

        //Build the list of team with open dates.
        $result = $this->admin_model->dbSelect("*", "teams", " 1 ORDER BY city, name ");
        if (count($result) > 0) {
            foreach ($result as $res) {
                $id = $res->team_id;
                $res = $this->admin_model->dbSelect("count(*) as total", "schedule", " home_id='$id' OR visitor_id='$id' AND week='$week' ");
                if (count($res) > 0) {
                    if ($res[0]->total == 0) {
                        array_push($team, $res->city);
                        //$team = $res->city;
                    }
                }
            }
        }


        //Display the list.
        if (count($team) > 0) {
            for ($i = 0; $i < count($team); $i++) {
                echo "<p></p><div class='adjustWidth'><strong>Open dates:</strong> " . $team[$i] . ".</div>";
            }
        }
    }

    /* -------------------------------------------------------------------------
      ' Displays a list of indexed links (week or page). The URLs point to the
      ' calling script passing the specified index parameter in the query string.
      ' Additional parameters may also be specified.
      '------------------------------------------------------------------------ */

    function DisplayGoToNavigation($indexName, $count, $otherParams) {
        $str = "";
        for ($i = 1; $i < $count; $i++) {
            if ($i > 1) {
                $str = $str . "<span class='goToSeparator'>&middot;</span>";
            }
            $str = $str . "<a href='#' Request.ServerVariables('SCRIPT_NAME')?" . $indexName . "=" . $i;
            if ($otherParams != "") {
                $str = $str . "&amp;" . $otherParams;
            }
            $str = $str . ">" . $i . "</a>";
        }

        if (strlen($str) > 0) {
            echo "<p class='goToNavigation'><span class='goToHeader'>Go to " . $indexName . ":</span> " . $str . "</p>";
        }
    }

}
