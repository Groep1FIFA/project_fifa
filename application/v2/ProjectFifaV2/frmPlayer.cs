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
        List<TextBox>[,] newRows = new List<TextBox>[2,2];
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

            DataTable hometable1 = dbh.FillDT("SELECT tblTeams.teamName, tblGames.HomeTeamScore, tblTeams.teamNr FROM tblGames INNER JOIN tblTeams ON tblGames.HomeTeam = tblTeams.id WHERE tblGames.pooleId=1");
            DataTable awayTable1 = dbh.FillDT("SELECT tblTeams.teamName, tblGames.AwayTeamScore, tblTeams.teamNr FROM tblGames INNER JOIN tblTeams ON tblGames.AwayTeam = tblTeams.id WHERE tblGames.pooleId=1");

            dbh.CloseConnectionToDB();

            for (int i = 0; i < hometable1.Rows.Count; i++)
            {
                DataRow dataRowHome1 = hometable1.Rows[i];
                DataRow dataRowAway1 = awayTable1.Rows[i];
                ListViewItem lstItem = new ListViewItem(dataRowHome1["teamName"].ToString());
                lstItem.SubItems.Add(dataRowHome1["HomeTeamScore"].ToString());
                lstItem.SubItems.Add(dataRowAway1["AwayTeamScore"].ToString());
                lstItem.SubItems.Add(dataRowAway1["teamName"].ToString());

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
                lvOverviewP1.Items.Add(lstItem);
            }
            DataTable hometable2 = dbh.FillDT("SELECT tblTeams.teamName, tblGames.HomeTeamScore, tblTeams.teamNr FROM tblGames INNER JOIN tblTeams ON tblGames.HomeTeam = tblTeams.id WHERE tblGames.pooleId=2");
            DataTable awayTable2 = dbh.FillDT("SELECT tblTeams.teamName, tblGames.AwayTeamScore, tblTeams.teamNr FROM tblGames INNER JOIN tblTeams ON tblGames.AwayTeam = tblTeams.id WHERE tblGames.pooleId=2");

            for (int j = 0; j < hometable2.Rows.Count; j++)
            {
                DataRow dataRowHome2 = hometable1.Rows[j];
                DataRow dataRowAway2 = awayTable1.Rows[j];
                ListViewItem lstItem = new ListViewItem(dataRowHome2["teamName"].ToString());
                lstItem.SubItems.Add(dataRowHome2["HomeTeamScore"].ToString());
                lstItem.SubItems.Add(dataRowAway2["AwayTeamScore"].ToString());
                lstItem.SubItems.Add(dataRowAway2["teamName"].ToString());
                lvOverviewP2.Items.Add(lstItem);
            }
        }

        private void ShowScoreCard()
        {
            //dbh.TestConnection();
            //dbh.OpenConnectionToDB();

            DataTable hometable = dbh.FillDT("SELECT tblTeams.teamName FROM tblGames INNER JOIN tblTeams ON tblGames.homeTeam = tblTeams.id");
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
            
            for (int j = 0; j < lengthOutterArray; j++)
            {
                for (int k = 0; k < lengthInnerArray; k++)
                {
                    if (k == 0)
                    {
                        home = rows[j, k].Text;
                    }
                    else
                    {
                        away = rows[j, k].Text;
                    }
                }
                dbh.Execute("UPDATE tblPredictions SET PredictedHomeScore=" + home + ", PredictedAwayScore=" + away + " WHERE (User_id=" + 
                    rowUser["id"] + " AND Game_id=" + Convert.ToInt32(j) + ")");
            }

        }

        private void btnInsertPrediction_Click(object sender, EventArgs e)
        {
            DataTable tblUsers = dbh.FillDT("SELECT * from tblUsers WHERE (Username='" + unLbl.Text + "')");
            DataRow rowUser = tblUsers.Rows[0];
            string home = "";
            string away = "";
            for (int j = 0; j < lengthOutterArray; j++)
            {
                for (int k = 0; k < lengthInnerArray; k++)
                {
                    if (k == 0)
                    {
                        home = rows[j, k].Text;
                    }
                    else
                    {
                        away = rows[j, k].Text;
                    }
                }
                dbh.Execute("Insert Into tblPredictions (User_id, Game_id, PredictedHomeScore, PredictedAwayScore) VALUES ('" + rowUser["id"] + "', " + Convert.ToInt32(j) + ", '" + home + "', '" + away + "')");
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
    }
}
