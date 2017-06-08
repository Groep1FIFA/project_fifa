using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace ProjectFifaV2
{
    public partial class frmScoreInput : Form
    {
        private int lengthInnerArray = 2;
        private int lengthOutterArray;
        private DatabaseHandler dbh;
        private TextBox[,] rows;
        private int[] index;
        public frmScoreInput()
        {
            InitializeComponent();
            dbh = new DatabaseHandler();
            ViewMatches();
        }

        private void ViewMatches()
        {
            string checkup = "SELECT * FROM tblGames WHERE finished = 0";
            DataTable dtCheck = dbh.FillDT(checkup);
            if (dtCheck.Rows.Count > 0)
            {
                string countPoules = "SELECT DISTINCT pouleId FROM tblGames";
                DataTable pouleCount = dbh.FillDT(countPoules);

                string query = "SELECT team1.teamName AS Home, team2.teamName AS Away, game.HomeTeamScore AS HomeScore, game.AwayTeamScore AS AwayScore, game.Game_ID AS Game_ID FROM ((tblGames AS game INNER JOIN tblTeams AS team1 ON game.HomeTeam = team1.teamNr AND game.PouleId = team1.PouleId) INNER JOIN tblTeams AS team2 ON game.AwayTeam = team2.teamNr AND game.PouleID = team2.PouleId) ORDER BY game.PouleId, game.Game_Id ASC";
                DataTable results = dbh.FillDT(query);
                dbh.CloseConnectionToDB();

                lengthOutterArray = results.Rows.Count;
                rows = new TextBox[lengthOutterArray, lengthInnerArray];
                index = new int[lengthOutterArray];
                for (int i = 0; i < results.Rows.Count; i++)
                {

                    DataRow match = results.Rows[i];
                    Label lblHomeTeam = new Label();
                    Label lblAwayTeam = new Label();
                    TextBox txtHomePred = new TextBox();
                    TextBox txtAwayPred = new TextBox();

                    lblHomeTeam.TextAlign = ContentAlignment.BottomRight;
                    lblHomeTeam.Text = match["Home"].ToString();
                    lblHomeTeam.Location = new Point(15, txtHomePred.Bottom + (i * 30));
                    lblHomeTeam.AutoSize = true;

                    txtHomePred.Text = "";
                    txtHomePred.Location = new Point(lblHomeTeam.Width, lblHomeTeam.Top - 3);
                    txtHomePred.Width = 40;
                    rows[i, 0] = txtHomePred;

                    txtAwayPred.Text = "";
                    txtAwayPred.Location = new Point(txtHomePred.Width + lblHomeTeam.Width, txtHomePred.Top);
                    txtAwayPred.Width = 40;
                    rows[i, 1] = txtAwayPred;

                    int.TryParse(match["Game_ID"].ToString(), out index[i]);

                    lblAwayTeam.Text = match["Away"].ToString();
                    lblAwayTeam.Location = new Point(txtHomePred.Width + lblHomeTeam.Width + txtAwayPred.Width, txtHomePred.Top + 3);
                    lblAwayTeam.AutoSize = true;

                    pnlScores.Controls.Add(lblHomeTeam);
                    pnlScores.Controls.Add(txtHomePred);
                    pnlScores.Controls.Add(txtAwayPred);
                    pnlScores.Controls.Add(lblAwayTeam);

                }
            }
            else
            {
                string quarterQuery = "SELECT * FROM tblPlayoffs WHERE playoffRankingA = 0 AND playoffRankingB = 0 AND finished = 0";
                DataTable quarterFinals = dbh.FillDT(quarterQuery);

                string semiQuery = "SELECT * FROM tblPlayoffs WHERE playoffRankingA = 1 AND playoffRankingB = 1 AND finished = 0";
                DataTable semiFinals = dbh.FillDT(semiQuery);

                string finalQuery = "SELECT * FROM tblPlayoffs WHERE playoffRankingA = 2 AND playoffRankingB = 2 AND finished = 0";
                DataTable finals = dbh.FillDT(finalQuery);

                if (quarterFinals.Rows.Count > 0)
                {
                    lengthOutterArray = quarterFinals.Rows.Count;
                    rows = new TextBox[lengthOutterArray, lengthInnerArray];
                    index = new int[lengthOutterArray];

                    for (int i = 0; i < quarterFinals.Rows.Count; i++)
                    {
                        object[,] obj = { { "id", quarterFinals.Rows[i]["pouleIdA"] }, { "poule", quarterFinals.Rows[i]["pouleRankingA"] } };
                        string quarterIntern1a = "SELECT * FROM tblTeams WHERE pouleId=@id AND pouleRanking = @poule";
                        DataTable quarterIntern1b = dbh.FillDT(quarterIntern1a, obj);

                        object[,] obj2 = { { "id", quarterFinals.Rows[i]["pouleIdB"] }, { "poule", quarterFinals.Rows[i]["pouleRankingB"] } };
                        string quarterIntern2a = "SELECT * FROM tblTeams WHERE pouleId=@id AND pouleRanking = @poule";
                        DataTable quarterIntern2b = dbh.FillDT(quarterIntern2a, obj2);



                        //if (quarterFinals.Rows[i]["finished"].ToString() == "0")
                        //{
                        DataRow match = quarterFinals.Rows[i];
                        Label lblHomeTeam = new Label();
                        Label lblAwayTeam = new Label();
                        TextBox txtHomePred = new TextBox();
                        TextBox txtAwayPred = new TextBox();

                        lblHomeTeam.TextAlign = ContentAlignment.BottomRight;
                        lblHomeTeam.Text = quarterIntern1b.Rows[0]["teamName"].ToString();
                        lblHomeTeam.Location = new Point(15, txtHomePred.Bottom + (i * 30));
                        lblHomeTeam.AutoSize = true;

                        txtHomePred.Text = "";
                        txtHomePred.Location = new Point(lblHomeTeam.Width, lblHomeTeam.Top - 3);
                        txtHomePred.Width = 40;
                        rows[i, 0] = txtHomePred;

                        txtAwayPred.Text = "";
                        txtAwayPred.Location = new Point(txtHomePred.Width + lblHomeTeam.Width, txtHomePred.Top);
                        txtAwayPred.Width = 40;
                        rows[i, 1] = txtAwayPred;

                        int.TryParse(match["id"].ToString(), out index[i]);

                        lblAwayTeam.Text = quarterIntern2b.Rows[0]["teamName"].ToString();
                        lblAwayTeam.Location = new Point(txtHomePred.Width + lblHomeTeam.Width + txtAwayPred.Width, txtHomePred.Top + 3);
                        lblAwayTeam.AutoSize = true;

                        pnlScores.Controls.Add(lblHomeTeam);
                        pnlScores.Controls.Add(txtHomePred);
                        pnlScores.Controls.Add(txtAwayPred);
                        pnlScores.Controls.Add(lblAwayTeam);
                    }
                }
                if (quarterFinals.Rows.Count == 0)
                {

                    lengthOutterArray = semiFinals.Rows.Count;
                    rows = new TextBox[lengthOutterArray, lengthInnerArray];
                    index = new int[lengthOutterArray];
                    for (int i = 0; i < semiFinals.Rows.Count; i++)
                    {
                        object[,] obj = { { "id", semiFinals.Rows[i]["pouleIdA"] }, { "rank", semiFinals.Rows[i]["pouleRankingA"] } };
                        string semiIntern1a = "SELECT * FROM tblTeams WHERE pouleId=@id AND pouleRanking = @rank";
                        DataTable semiIntern1b = dbh.FillDT(semiIntern1a, obj);

                        object[,] obj2 = { { "id", semiFinals.Rows[i]["PouleIdB"] }, { "rank", semiFinals.Rows[i]["pouleRankingB"] } };
                        string semiIntern2a = "SELECT * FROM tblTeams WHERE pouleId=@id AND pouleRanking = @rank";
                        DataTable semiIntern2b = dbh.FillDT(semiIntern2a, obj2);

                        DataRow match = semiFinals.Rows[i];
                        Label lblHomeTeam = new Label();
                        Label lblAwayTeam = new Label();
                        TextBox txtHomePred = new TextBox();
                        TextBox txtAwayPred = new TextBox();

                        lblHomeTeam.TextAlign = ContentAlignment.BottomRight;
                        lblHomeTeam.Text = semiIntern1b.Rows[0]["teamName"].ToString();
                        lblHomeTeam.Location = new Point(15, txtHomePred.Bottom + (i * 30));
                        lblHomeTeam.AutoSize = true;

                        txtHomePred.Text = "";
                        txtHomePred.Location = new Point(lblHomeTeam.Width, lblHomeTeam.Top - 3);
                        txtHomePred.Width = 40;
                        rows[i, 0] = txtHomePred;

                        txtAwayPred.Text = "";
                        txtAwayPred.Location = new Point(txtHomePred.Width + lblHomeTeam.Width, txtHomePred.Top);
                        txtAwayPred.Width = 40;
                        rows[i, 1] = txtAwayPred;

                        int.TryParse(match["id"].ToString(), out index[i]);

                        lblAwayTeam.Text = semiIntern2b.Rows[0]["teamName"].ToString();
                        lblAwayTeam.Location = new Point(txtHomePred.Width + lblHomeTeam.Width + txtAwayPred.Width, txtHomePred.Top + 3);
                        lblAwayTeam.AutoSize = true;

                        pnlScores.Controls.Add(lblHomeTeam);
                        pnlScores.Controls.Add(txtHomePred);
                        pnlScores.Controls.Add(txtAwayPred);
                        pnlScores.Controls.Add(lblAwayTeam);
                    }
                }
                if (quarterFinals.Rows.Count == 0 && semiFinals.Rows.Count == 0)
                {
                    lengthOutterArray = finals.Rows.Count;
                    rows = new TextBox[lengthOutterArray, lengthInnerArray];
                    index = new int[lengthOutterArray];

                    for (int i = 0; i < finals.Rows.Count; i++)
                    {
                        object[,] obj = { { "id", finals.Rows[i]["PouleIdA"] }, { "rank", finals.Rows[i]["pouleRankingA"] } };
                        string finalIntern1a = "SELECT * FROM tblTeams WHERE pouleId=@id AND pouleRanking =@rank";
                        DataTable finalIntern1b = dbh.FillDT(finalIntern1a, obj);

                        object[,] obj2 = { { "id", finals.Rows[i]["PouleIdB"] }, { "rank", finals.Rows[i]["pouleRankingB"] } };
                        string finalIntern2a = "SELECT * FROM tblTeams WHERE pouleId=@id AND pouleRanking = @rank";
                        DataTable finalIntern2b = dbh.FillDT(finalIntern2a, obj2);

                        DataRow match = finals.Rows[i];
                        Label lblHomeTeam = new Label();
                        Label lblAwayTeam = new Label();
                        TextBox txtHomePred = new TextBox();
                        TextBox txtAwayPred = new TextBox();

                        lblHomeTeam.TextAlign = ContentAlignment.BottomRight;
                        lblHomeTeam.Text = finalIntern1b.Rows[0]["teamName"].ToString();
                        lblHomeTeam.Location = new Point(15, txtHomePred.Bottom + (i * 30));
                        lblHomeTeam.AutoSize = true;

                        txtHomePred.Text = "";
                        txtHomePred.Location = new Point(lblHomeTeam.Width, lblHomeTeam.Top - 3);
                        txtHomePred.Width = 40;
                        rows[i, 0] = txtHomePred;

                        txtAwayPred.Text = "";
                        txtAwayPred.Location = new Point(txtHomePred.Width + lblHomeTeam.Width, txtHomePred.Top);
                        txtAwayPred.Width = 40;
                        rows[i, 1] = txtAwayPred;

                        int.TryParse(match["id"].ToString(), out index[i]);

                        lblAwayTeam.Text = finalIntern2b.Rows[0]["teamName"].ToString();
                        lblAwayTeam.Location = new Point(txtHomePred.Width + lblHomeTeam.Width + txtAwayPred.Width, txtHomePred.Top + 3);
                        lblAwayTeam.AutoSize = true;

                        pnlScores.Controls.Add(lblHomeTeam);
                        pnlScores.Controls.Add(txtHomePred);
                        pnlScores.Controls.Add(txtAwayPred);
                        pnlScores.Controls.Add(lblAwayTeam);
                    }
                }
            }
        }

        private void SubmitScore()
        {
            string home = "";
            string away = "";

            DataTable tblGames = dbh.FillDT("SELECT * FROM tblGames");

            DataTable playoffs = dbh.FillDT("SELECT * FROM tblPlayoffs");

            if (tblGames.Rows.Count > 0)
            {
                for (int j = 0; j < lengthOutterArray; j++)
                {
                    DataRow game = tblGames.Rows[j];
                    for (int k = 0; k < lengthInnerArray; k++)
                    {
                        if (k == 0)
                        {
                            if (rows[j, k].Text == "")
                            {
                                home = null;
                            }
                            else
                            {
                                home = rows[j, k].Text;
                            }
                        }
                        else
                        {
                            if (rows[j, k].Text == "")
                            {
                                away = null;
                            }
                            else
                            {
                                away = rows[j, k].Text;
                            }
                        }
                    }
                    if ((home != null && home != "") && (away != null && away != ""))
                    {
                        object[,] obj2 = { { "game", index[j] } };
                        string query2 = "SELECT * FROM tblGames WHERE (Game_id = @game)";
                        DataTable checkup = dbh.FillDT(query2, obj2);
                        if (checkup.Rows.Count == 1)
                        {
                            object[,] upd = { { "home", home }, { "away", away }, { "game", index[j] } };
                            dbh.Execute("UPDATE tblGames SET HomeTeamScore=@home, AwayTeamScore=@away WHERE (Game_id=@game)", upd);
                        }
                    }
                }
            }
            else
            {
                DataTable dt = dbh.FillDT("SELECT * FROM tblPlayoffs");
                for (int i = 0; i < dt.Rows.Count; i++)
                {
                    DataRow game = dt.Rows[i];
                    for (int k = 0; k < lengthInnerArray; k++)
                    {
                        if (k == 0)
                        {
                            if (rows[i, k].Text == "")
                            {
                                home = null;
                            }
                            else
                            {
                                home = rows[i, k].Text;
                            }
                        }
                        else
                        {
                            if (rows[i, k].Text == "")
                            {
                                away = null;
                            }
                            else
                            {
                                away = rows[i, k].Text;
                            }
                        }
                    }
                    if ((home != null && home != "") && (away != null && away != ""))
                    {
                        object[,] pla = { { "game", index[i] } };
                        string query2 = "SELECT * FROM tblPlayoffs WHERE (Game_id=@game)";
                        DataTable checkup = dbh.FillDT(query2, pla);
                        if (checkup.Rows.Count == 1)
                        {
                            object[,] upd = { { "home", home }, { "away", away }, { "game", index[i] } };
                            dbh.Execute("UPDATE tblPlayoffs SET scoreHomeTeam=@home, scoreAwayTeam=@away WHERE (Game_id=@game)", upd);
                        }
                    }
                }
            }

            /*string home = "";
            string away = "";

            for (int j = 0; j < lengthOutterArray - 1; j++)
            {
                for (int k = 0; k < lengthInnerArray; k++)
                {
                    if (k == 0)
                    {
                        if (rows[j, k].Text == "")
                        {
                            home = null;
                        }
                        else
                        {
                            home = rows[j, k].Text;
                        }
                    }
                    else
                    {
                        if (rows[j, k].Text == "")
                        {
                            away = null;
                        }
                        else
                        {
                            away = rows[j, k].Text;
                        }
                    }
                }
                if (home != null && away != null)
                {
                    dbh.Execute("UPDATE tblGames SET HomeTeamScore=" + home + ", AwayTeamScore=" + away + " WHERE (Game_id=" + index[j] + ")");
                }
            }*/
        }

        private void processResults()
        {
            DataTable users = dbh.FillDT("SELECT * FROM tblUsers");

            for (int i = 0; i < users.Rows.Count; i++)
            {
                DataRow user = users.Rows[i];
                int score = 0;
                string queryNew = "SELECT pred.PredictedHomeScore AS pred1, pred.PredictedAwayScore AS pred2, game.HomeTeamScore AS HomeTeamScore, game.AwayTeamScore AS AwayTeamScore FROM (tblPredictions AS pred INNER JOIN tblGames AS game ON pred.Game_id = game.Game_id) WHERE pred.User_id = " + user["id"] + "";
                DataTable games = dbh.FillDT(queryNew);
                
                
                for (int j = 0; j < games.Rows.Count; j++)
                {
                    DataRow game = games.Rows[j];

                    int predHome;
                    int predAway;
                    int home;
                    int away;
                    int.TryParse(game["pred1"].ToString(), out predHome);
                    int.TryParse(game["pred2"].ToString(), out predAway);
                    int.TryParse(game["HomeTeamScore"].ToString(), out home);
                    int.TryParse(game["AwayTeamScore"].ToString(), out away);

                    if (home > away)
                    {
                        if (predHome == home && predAway == away)
                        {
                            score += 2;
                        }
                        else if (predHome > predAway)
                        {
                            score += 1;
                        }
                    }
                    else if (away > home)
                    {
                        if (predHome == home && predAway == away)
                        {
                            score += 2;
                        }
                        else if (predHome < predAway)
                        {
                            score += 1;
                        }
                    }
                    else
                    {
                        if (predHome == home && predAway == away)
                        {
                            score += 2;
                        }
                    }
                }
                dbh.Execute("UPDATE tblUsers SET Score='" + score + "' WHERE id='" + user["id"].ToString() + "'");
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            SubmitScore();
        }

        private void calculateScore_Click(object sender, EventArgs e)
        {
            processResults();
        }
    }
}
