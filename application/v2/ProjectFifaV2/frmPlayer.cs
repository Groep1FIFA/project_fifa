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
    public partial class frmPlayer : Form
    {
        private int lengthInnerArray = 2;
        private int lengthOutterArray;

        private Form frmRanking;
        private DatabaseHandler dbh;
        private string userName;
        private DataTable tblUsers;
        private DataRow rowUser;

        TextBox[,] rows;
        int[] index;
        public frmPlayer(Form frm, string un)
        {
            this.ControlBox = false;
            frmRanking = frm;
            dbh = new DatabaseHandler();
            
            InitializeComponent();
            this.unLbl.Text = un;
            tblUsers = dbh.FillDT("select * from tblUsers WHERE (Username='" + unLbl.Text + "')");
            rowUser = tblUsers.Rows[0];
            if (DisableButtons())
            {
                btnEditPrediction.Enabled = false;
                btnClearPrediction.Enabled = false;
            }
            else
            {
                btnInsertPrediction.Enabled = false;
            }
            ShowResults();
            ShowScoreCard();
            this.Text = "Welcome " + un;
            
        }

        private void btnLogOut_Click(object sender, EventArgs e)
        {
            Hide();
        }

        private void btnShowRanking_Click(object sender, EventArgs e)
        {
            frmRanking.Show();
        }

        private void btnClearPrediction_Click(object sender, EventArgs e)
        {
            DialogResult result = MessageBox.Show("Are you sure you want to clear your prediction?", "Clear Predictions", MessageBoxButtons.OKCancel, MessageBoxIcon.Information);
            if (result.Equals(DialogResult.OK))
            {
                DataTable tblUsers = dbh.FillDT("select * from tblUsers WHERE (Username='" + unLbl.Text + "')");
                DataRow rowUser = tblUsers.Rows[0];
                // Clear predections
                // Update DB
                dbh.Execute("DELETE FROM tblPredictions WHERE (User_id=" + rowUser["id"] + ")");
                if (DisableButtons())
                {
                    btnClearPrediction.Enabled = false;
                    btnEditPrediction.Enabled = false;
                    btnInsertPrediction.Enabled = true;
                }
                else
                {
                    btnClearPrediction.Enabled = true;
                    btnEditPrediction.Enabled = true;
                    btnInsertPrediction.Enabled = false;
                }
            }
        }

        private bool DisableButtons()
        {
            bool hasPassed;
            //This is the deadline for filling in the predictions
            DateTime deadline = new DateTime(2020, 06, 12);
            DateTime curTime = DateTime.Now;
            int result = DateTime.Compare(deadline, curTime);
            DataTable predCheck = dbh.FillDT("SELECT * from tblPredictions WHERE (User_id='" + rowUser["id"] + "')");
            if (result < 0 || predCheck.Rows.Count == 0)
            {
                hasPassed = true;
            }
            else
            {
                hasPassed = false;
            }

            return hasPassed;
        }

        private void ShowResults()
        {
            string query = "SELECT team1.teamName AS Home, game.HomeTeamScore AS HomeScore, game.AwayTeamScore AS AwayScore, team2.teamName AS Away, game.PouleId as poule FROM ((tblGames AS game INNER JOIN tblTeams AS team1 ON game.HomeTeam = team1.teamNr AND game.PouleId = team1.PouleId) INNER JOIN tblTeams AS team2 ON game.AwayTeam = team2.teamNr AND game.PouleId = team2.PouleId) ORDER BY game.PouleId, game.Game_Id ASC;";
            DataTable results = dbh.FillDT(query);
            dbh.CloseConnectionToDB();

            

            foreach (DataRow match in results.Rows)
            {
                ListViewItem lstItem = new ListViewItem(match["Home"].ToString());
                lstItem.SubItems.Add(match["HomeScore"].ToString());
                lstItem.SubItems.Add(match["AwayScore"].ToString());
                lstItem.SubItems.Add(match["Away"].ToString());
                lstItem.SubItems.Add(match["poule"].ToString());
                lvOverviewP1.Items.Add(lstItem);
            }
        }

        private void ShowScoreCard()
        {
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

                pnlPredCard.Controls.Add(lblHomeTeam);
                pnlPredCard.Controls.Add(txtHomePred);
                pnlPredCard.Controls.Add(txtAwayPred);
                pnlPredCard.Controls.Add(lblAwayTeam);
                if ((match["HomeScore"].ToString() != null && match["HomeScore"].ToString() != "") || (match["AwayScore"].ToString() != null && match["AwayScore"].ToString() != ""))
                {
                    rows[i, 0].ReadOnly = true;
                    rows[i, 1].ReadOnly = true;
                }
            }

            
        }

        internal void GetUsername(string un)
        {
            userName = un;
        }

        private void btnEditPrediction_Click(object sender, EventArgs e)
        {
            DataTable tblUsers = dbh.FillDT("select * from tblUsers WHERE (Username='" + unLbl.Text + "')");
            DataRow rowUser = tblUsers.Rows[0];
            string home = "";
            string away = "";
            DataTable tblGames = dbh.FillDT("SELECT * FROM tblGames");
            for (int j = 0; j < lengthOutterArray; j++)
            {
                DataRow game = tblGames.Rows[j];
                for (int k = 0; k < lengthInnerArray; k++)
                {
                    if (k == 0)
                    {
                        if (rows[j, k].Text == "" || game["HomeTeamScore"] == null)
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
                        if (rows[j, k].Text == "" || game["AwayTeamScore"] == null)
                        {
                            away = null;
                        }
                        else
                        {
                            away = rows[j, k].Text;
                        }
                    }
                }
                if ((game["HomeTeamScore"] == null || game["HomeTeamScore"].ToString() == "") && (game["AwayTeamScore"] == null || game["AwayTeamScore"].ToString() == "") && (home != null && home != "") && (away != null && away != ""))
                {
                    string query2 = "SELECT * FROM tblPredictions WHERE (User_id = " + rowUser["id"] + " AND Game_id = " + index[j] + ")";
                    DataTable checkup = dbh.FillDT(query2);
                    MessageBox.Show(checkup.Rows.Count.ToString());
                    if (checkup.Rows.Count == 1)
                    {
                        dbh.Execute("UPDATE tblPredictions SET PredictedHomeScore=" + home + ", PredictedAwayScore=" + away + " WHERE (User_id=" +
                        rowUser["id"] + " AND Game_id=" + index[j] + ")");
                    }
                    else
                    {
                        dbh.Execute("Insert Into tblPredictions (User_id, Game_id, PredictedHomeScore, PredictedAwayScore) VALUES ('" + rowUser["id"] + "', " + index[j] + ", '" + home + "', '" + away + "')");
                    }
                }
                else
                {
                }

                
            }

        }

        private void btnInsertPrediction_Click(object sender, EventArgs e)
        {
            DataTable tblUsers = dbh.FillDT("SELECT * from tblUsers WHERE (Username='" + unLbl.Text + "')");
            DataRow rowUser = tblUsers.Rows[0];
            string home = "";
            string away = "";
            DataTable games = dbh.FillDT("SELECT * FROM tblGames");
            for (int j = 0; j < lengthOutterArray; j++)
            {
                DataRow game = games.Rows[j];
                for (int k = 0; k < 2; k++)
                {
                    if (k == 0)
                    {
                        if (rows[j, k].Text == "" || game["HomeTeamScore"] == null)
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
                        if (rows[j, k].Text == "" || game["AwayTeamScore"] == null)
                        {
                            away = null;
                        }
                        else
                        {
                            away = rows[j, k].Text;
                        }
                    }
                }
                if (game["HomeTeamScore"] != null && game["AwayTeamScore"] != null && (home != null && home != "") && (away != null && away != ""))
                {
                    dbh.Execute("Insert Into tblPredictions (User_id, Game_id, PredictedHomeScore, PredictedAwayScore) VALUES ('" + rowUser["id"] + "', " + index[j] + ", '" + home + "', '" + away + "')");
                }
                else
                {
                    
                }
            }
            if (DisableButtons())
            {
                btnClearPrediction.Enabled = false;
                btnEditPrediction.Enabled = false;
                btnInsertPrediction.Enabled = true;
            }
            else
            {
                btnClearPrediction.Enabled = true;
                btnEditPrediction.Enabled = true;
                btnInsertPrediction.Enabled = false;
            }
        }

        private void browserLauncher_Click(object sender, EventArgs e)
        {
            Browser browser = new Browser();
            browser.Show();
        }
    }
}
