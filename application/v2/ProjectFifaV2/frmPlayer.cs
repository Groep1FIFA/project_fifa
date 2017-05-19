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
        const int lengthInnerArray = 2;
        const int lengthOutterArray = 12;

        private Form frmRanking;
        private DatabaseHandler dbh;
        private string userName;
        private DataTable tblUsers;
        private DataRow rowUser;

        List<TextBox> txtBoxList;
        TextBox[,] rows = new TextBox[lengthOutterArray, lengthInnerArray];
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
            //dbh.TestConnection();
            //dbh.OpenConnectionToDB();
            DataTable matches = dbh.FillDT("SELECT * FROM tblGames ORDER BY pouleId");
            foreach (DataRow match in matches.Rows)
            { 
                DataTable team1 = dbh.FillDT("SELECT * FROM tblTeams WHERE pouleId='" + match["pouleId"].ToString() + "' AND teamNr='" + match["HomeTeam"].ToString() + "' ORDER BY pouleId");
                DataTable team2 = dbh.FillDT("SELECT * FROM tblTeams WHERE pouleId='" + match["pouleId"].ToString() + "' AND teamNr='" + match["AwayTeam"].ToString() + "' ORDER BY pouleId");
                dbh.CloseConnectionToDB();
                for (int i = 0; i < team1.Rows.Count; i++)
                {
                    DataRow teamRow1 = team1.Rows[i];
                    DataRow teamRow2 = team2.Rows[i];
                    ListViewItem lstItem = new ListViewItem(teamRow1["teamName"].ToString());
                    lstItem.SubItems.Add(match["HomeTeamScore"].ToString());
                    lstItem.SubItems.Add(match["AwayTeamScore"].ToString());
                    lstItem.SubItems.Add(teamRow2["teamName"].ToString());
                    lstItem.SubItems.Add(teamRow2["pouleId"].ToString());
                    lvOverviewP1.Items.Add(lstItem);
                }
            }
            #region rubble
            //DataTable hometable1 = dbh.FillDT("SELECT tblTeams.teamName, tblGames.HomeTeamScore, tblTeams.teamNr, tblTeams.pouleId FROM tblGames INNER JOIN tblTeams ON tblGames.pouleId = tblTeams.pouleId AND tblGames.HomeTeam = tblTeams.teamNr");
            //DataTable awayTable1 = dbh.FillDT("SELECT tblTeams.teamName, tblGames.AwayTeamScore, tblTeams.teamNr, tblGames.pouleId FROM tblGames INNER JOIN tblTeams ON tblGames.pouleId = tblTeams.pouleId AND tblGames.AwayTeam = tblTeams.teamNr");

            /*dbh.CloseConnectionToDB();
            MessageBox.Show(hometable1.Rows.Count.ToString());
            for (int i = 0; i < hometable1.Rows.Count; i++)
            {
                DataRow dataRowHome1 = hometable1.Rows[i];
                DataRow dataRowAway1 = awayTable1.Rows[i];
                ListViewItem lstItem = new ListViewItem(dataRowHome1["teamName"].ToString());
                lstItem.SubItems.Add(dataRowHome1["HomeTeamScore"].ToString());
                lstItem.SubItems.Add(dataRowAway1["AwayTeamScore"].ToString());
                lstItem.SubItems.Add(dataRowAway1["teamName"].ToString());
                lstItem.SubItems.Add(dataRowAway1["pouleId"].ToString());
                #region test
                /*string[,,,,] poole1 = new string[hometable.Rows.Count,1,1,1,1];

                if (dataRowHome["pooleId"].ToString() == "1" )
                {
                    if (dataRowHome["teamNr"].ToString() == "1")
                    {
                        for  (int j = 0; j < awayTable.Rows.Count; j++)
                        {
                            DataRow dataRowAwayNew = awayTable.Rows[j];
                            if (dataRowAwayNew["pooleId"].ToString() == "1")
                            {
                                if (dataRowAwayNew["teamNr"].ToString() == "2")
                                {
                                    poole1 = [ i.ToString(), dataRowHome["teamName"].ToString(), dataRowHome["HomeTeamScore"].ToString(), dataRowAwayNew["AwayTeamScore"].ToString(), dataRowAwayNew["teamName"].ToString() ];
                                }
                            }
                        }
                    }
                }*/
            //}
            #region test
            /*DataTable hometable2 = dbh.FillDT("SELECT tblTeams.teamName, tblGames.HomeTeamScore, tblTeams.teamNr FROM tblGames INNER JOIN tblTeams ON tblGames.HomeTeam = tblTeams.id AND tblGames.pouleId = 2 AND tblTeams.pouleId = 2 WHERE tblTeams.pouleId=2 AND tblGames.pouleId=2 ORDER BY tblTeams.teamNr ASC");
            DataTable awayTable2 = dbh.FillDT("SELECT tblTeams.teamName, tblGames.AwayTeamScore, tblTeams.teamNr FROM tblGames INNER JOIN tblTeams ON tblGames.AwayTeam = tblTeams.id AND tblGames.pouleId = 2 AND tblTeams.pouleId = 2 WHERE tblTeams.pouleId=2 AND tblGames.pouleId=2 ORDER BY tblTeams.teamNr ASC");

            for (int j = 0; j < hometable2.Rows.Count; j++)
            {
                DataRow dataRowHome2 = hometable2.Rows[j];
                DataRow dataRowAway2 = awayTable2.Rows[j];
                ListViewItem lstItem2 = new ListViewItem(dataRowHome2["teamName"].ToString());
                lstItem2.SubItems.Add(dataRowHome2["HomeTeamScore"].ToString());
                lstItem2.SubItems.Add(dataRowAway2["AwayTeamScore"].ToString());
                lstItem2.SubItems.Add(dataRowAway2["teamName"].ToString());
                lvOverviewP2.Items.Add(lstItem2);
            }*/
            #endregion
            #endregion
        }

        private void ShowScoreCard()
        {
            //dbh.TestConnection();
            //dbh.OpenConnectionToDB();
            /*DataTable matches = dbh.FillDT("SELECT * FROM tblGames");
            for (int i = 0; i < matches.Rows.Count; i++)
            {
                DataRow match = matches.Rows[i];
                DataTable team1 = dbh.FillDT("SELECT * FROM tblTeams WHERE pouleId='" + match["pouleId"].ToString() + "' AND teamNr='" + match["HomeTeam"].ToString() + "'");
                DataTable team2 = dbh.FillDT("SELECT * FROM tblTeams WHERE pouleId='" + match["pouleId"].ToString() + "' AND teamNr='" + match["AwayTeam"].ToString() + "'");
                dbh.CloseConnectionToDB();
                for (int j = 0; j < team1.Rows.Count; j++)
                {*/
            DataTable matches = dbh.FillDT("SELECT * FROM tblGames ORDER BY pouleId");
            for (int j = 0; j < matches.Rows.Count; j++)
            {
                DataTable team1 = dbh.FillDT("SELECT * FROM tblTeams WHERE pouleId='" + matches.Rows[j]["pouleId"].ToString() + "' AND teamNr='" + matches.Rows[j]["HomeTeam"].ToString() + "' ORDER BY pouleId");
                DataTable team2 = dbh.FillDT("SELECT * FROM tblTeams WHERE pouleId='" + matches.Rows[j]["pouleId"].ToString() + "' AND teamNr='" + matches.Rows[j]["AwayTeam"].ToString() + "' ORDER BY pouleId");
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

                    pnlPredCard.Controls.Add(lblHomeTeam);
                    pnlPredCard.Controls.Add(txtHomePred);
                    pnlPredCard.Controls.Add(txtAwayPred);
                    pnlPredCard.Controls.Add(lblAwayTeam);
                    if ((matches.Rows[j]["AwayTeamScore"].ToString() != null && matches.Rows[j]["AwayTeamScore"].ToString() != "") || (matches.Rows[j]["HomeTeamScore"].ToString() != null && matches.Rows[j]["HomeTeamScore"].ToString() != ""))
                    {
                        rows[j, 0].ReadOnly = true;
                        rows[j, 1].ReadOnly = true;
                    }
                }
            }
            /*DataTable hometable = dbh.FillDT("SELECT tblTeams.teamName FROM tblGames INNER JOIN tblTeams ON tblGames.homeTeam = tblTeams.id");
            DataTable awayTable = dbh.FillDT("SELECT tblTeams.teamName FROM tblGames INNER JOIN tblTeams ON tblGames.awayTeam = tblTeams.id");

            dbh.CloseConnectionToDB();

            for (int i = 0; i < hometable.Rows.Count; i++)
            {
                
                DataRow dataRowHome = hometable.Rows[i];
                DataRow dataRowAway = awayTable.Rows[i];
                
                Label lblHomeTeam = new Label();
                Label lblAwayTeam = new Label();
                TextBox txtHomePred = new TextBox();
                TextBox txtAwayPred = new TextBox();

                lblHomeTeam.TextAlign = ContentAlignment.BottomRight;
                lblHomeTeam.Text = dataRowHome["teamName"].ToString();
                lblHomeTeam.Location = new Point(15, txtHomePred.Bottom + (i * 30));
                lblHomeTeam.AutoSize = true;

                txtHomePred.Text = "0";
                txtHomePred.Location = new Point(lblHomeTeam.Width, lblHomeTeam.Top - 3);
                txtHomePred.Width = 40;
                rows[i, 0] = txtHomePred;

                txtAwayPred.Text = "0";
                txtAwayPred.Location = new Point(txtHomePred.Width + lblHomeTeam.Width, txtHomePred.Top);
                txtAwayPred.Width = 40;
                rows[i, 1] = txtAwayPred;

                lblAwayTeam.Text = dataRowAway["TeamName"].ToString();
                lblAwayTeam.Location = new Point(txtHomePred.Width + lblHomeTeam.Width + txtAwayPred.Width, txtHomePred.Top + 3);
                lblAwayTeam.AutoSize = true;

                pnlPredCard.Controls.Add(lblHomeTeam);
                pnlPredCard.Controls.Add(txtHomePred);
                pnlPredCard.Controls.Add(txtAwayPred);
                pnlPredCard.Controls.Add(lblAwayTeam);
                //ListViewItem lstItem = new ListViewItem(dataRowHome["TeamName"].ToString());
                //lstItem.SubItems.Add(dataRowHome["HomeTeamScore"].ToString());
                //lstItem.SubItems.Add(dataRowAway["AwayTeamScore"].ToString());
                //lstItem.SubItems.Add(dataRowAway["TeamName"].ToString());
                //lvOverview.Items.Add(lstItem);
            }*/
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
                        if (rows[j, k].Text == "" || rows[j, k].Text == null || game["HomeTeamScore"] == null || game["AwayTeamScore"] == null)
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
                        if (rows[j, k].Text == "" || rows[j, k].Text == null || game["HomeTeamScore"] == null || game["AwayTeamScore"] == null)
                        {
                            away = null;
                        }
                        else
                        {
                            away = rows[j, k].Text;
                        }
                    }
                }
                if (game["HomeTeamScore"] == null && game["AwayTeamScore"] == null)
                {
                    dbh.Execute("UPDATE tblPredictions SET PredictedHomeScore=" + home + ", PredictedAwayScore=" + away + " WHERE (User_id=" +
                    rowUser["id"] + " AND Game_id=" + Convert.ToInt32(j) + ")");
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
                        if (rows[j, k].Text == "" || rows[j, k].Text == null || game["HomeTeamScore"] == null || game["AwayTeamScore"] == null)
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
                        if (rows[j, k].Text == "" || rows[j, k].Text == null || game["HomeTeamScore"] == null || game["AwayTeamScore"] == null)
                        {
                            away = null;
                        }
                        else
                        {
                            away = rows[j, k].Text;
                        }
                    }
                }
                if (game["HomeTeamScore"] != null && game["AwayTeamScore"] != null && (home != null || away != "") && (away != null || away != ""))
                {
                    dbh.Execute("Insert Into tblPredictions (User_id, Game_id, PredictedHomeScore, PredictedAwayScore) VALUES ('" + rowUser["id"] + "', " + Convert.ToInt32(j) + ", '" + home + "', '" + away + "')");
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
