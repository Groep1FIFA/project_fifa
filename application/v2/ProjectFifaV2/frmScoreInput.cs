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
        private int lengthInnerArray;
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
            string query = "SELECT team1.teamName AS Home, team2.teamName AS Away, game.HomeTeamScore AS HomeScore, game.AwayTeamScore AS AwayScore, game.Game_ID AS Game_ID FROM ((tblGames AS game INNER JOIN tblTeams AS team1 ON game.HomeTeam = team1.teamNr AND game.PouleId = team1.PouleId) INNER JOIN tblTeams AS team2 ON game.AwayTeam = team2.teamNr AND game.PouleID = team2.PouleId) ORDER BY game.PouleId, game.Game_Id ASC";
            DataTable results = dbh.FillDT(query);
            dbh.CloseConnectionToDB();

            lengthInnerArray = 2;
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

        private void SubmitScore()
        {
            string home = "";
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
            }
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
