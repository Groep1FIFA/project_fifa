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
        const int lengthInnerArray = 2;
        const int lengthOutterArray = 12;
        private DatabaseHandler dbh;
        TextBox[,] rows = new TextBox[lengthOutterArray, lengthInnerArray];

        public frmScoreInput()
        {
            InitializeComponent();
            dbh = new DatabaseHandler();
            ViewMatches();
            
        }

        private void ViewMatches()
        {
            DataTable matches = dbh.FillDT("SELECT * FROM tblGames");
            for (int j = 0; j < matches.Rows.Count; j++)
            {
                DataTable team1 = dbh.FillDT("SELECT * FROM tblTeams WHERE pouleId='" + matches.Rows[j]["pouleId"].ToString() + "' AND teamNr='" + matches.Rows[j]["HomeTeam"].ToString() + "'");
                DataTable team2 = dbh.FillDT("SELECT * FROM tblTeams WHERE pouleId='" + matches.Rows[j]["pouleId"].ToString() + "' AND teamNr='" + matches.Rows[j]["AwayTeam"].ToString() + "'");
                dbh.CloseConnectionToDB();
                for (int i = 0; i < team1.Rows.Count; i++)
                {
                    DataRow dataRowHome = team1.Rows[i];
                    DataRow dataRowAway = team2.Rows[i];

                    Label lblHomeTeam = new Label();
                    Label lblAwayTeam = new Label();
                    TextBox txtHomePred = new TextBox();
                    TextBox txtAwayPred = new TextBox();

                    lblHomeTeam.TextAlign = ContentAlignment.BottomRight;
                    lblHomeTeam.Text = dataRowHome["TeamName"].ToString();
                    lblHomeTeam.Location = new Point(15, txtHomePred.Bottom + (j * 30));
                    lblHomeTeam.AutoSize = true;

                    txtHomePred.Text = "";
                    txtHomePred.Location = new Point(lblHomeTeam.Width, lblHomeTeam.Top - 3);
                    txtHomePred.Width = 40;
                    rows[j, 0] = txtHomePred;

                    txtAwayPred.Text = "";
                    txtAwayPred.Location = new Point(txtHomePred.Width + lblHomeTeam.Width, txtHomePred.Top);
                    txtAwayPred.Width = 40;
                    rows[j, 1] = txtAwayPred;

                    lblAwayTeam.Text = dataRowAway["TeamName"].ToString();
                    lblAwayTeam.Location = new Point(txtHomePred.Width + lblHomeTeam.Width + txtAwayPred.Width, txtHomePred.Top + 3);
                    lblAwayTeam.AutoSize = true;

                    pnlScores.Controls.Add(lblHomeTeam);
                    pnlScores.Controls.Add(txtHomePred);
                    pnlScores.Controls.Add(txtAwayPred);
                    pnlScores.Controls.Add(lblAwayTeam);
                }
            }
        }

        private void SubmitScore()
        {
            string home = "";
            string away = "";

            for (int j = 0; j < lengthOutterArray; j++)
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
                    dbh.Execute("UPDATE tblGames SET HomeTeamScore=" + home + ", AwayTeamScore=" + away + " WHERE (Game_id=" + (Convert.ToInt32(j) + 1) + ")");
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
                DataTable games = dbh.FillDT("SELECT * FROM tblGames");
                

                for (int j = 0; j < games.Rows.Count; j++)
                {
                    DataRow game = games.Rows[j];
                    DataTable predi = dbh.FillDT("SELECT * FROM tblPredictions WHERE User_Id='" + user["Id"].ToString() + "' AND Game_Id='" + j.ToString() + "'");
                    if (predi.Rows.Count > 0)
                    {
                        DataRow prediRow = predi.Rows[0];

                        int predHome = Convert.ToInt32(prediRow["PredictedHomeScore"].ToString());
                        int predAway = Convert.ToInt32(prediRow["PredictedAwayScore"].ToString());
                        if (game["HomeTeamScore"].ToString() != "" && game["HomeTeamScore"] != null)
                        {
                            var home = Convert.ToInt32(game["HomeTeamScore"].ToString());
                            int away = Convert.ToInt32(game["AwayTeamScore"].ToString());
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
                                else
                                {

                                }
                            }
                            else if (home < away)
                            {
                                if (predHome == home && predAway == away)
                                {
                                    score += 2;
                                }
                                else if (predHome > predAway)
                                {
                                    score += 1;
                                }
                                else
                                {

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
