using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Resources;
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
            object[,] obj = { { "username", unLbl.Text } };
            tblUsers = dbh.FillDT("select * from tblUsers WHERE (Username=@username)", obj);
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
                object[,] obj = { { "username", unLbl.Text } };
                DataTable tblUsers = dbh.FillDT("select * from tblUsers WHERE (Username=@username)", obj);
                DataRow rowUser = tblUsers.Rows[0];
                // Clear predections
                // Update DB
                for (int i = 0; i < lengthOutterArray; i++)
                {
                    rows[i, 0].Text = "";
                    rows[i, 1].Text = "";
                }

                object[,] del = { { "id", rowUser["id"] } };

                dbh.Execute("DELETE FROM tblPredictions WHERE (User_id=@id)", del);
                dbh.Execute("DELETE FROM tblPlayoffPredictions WHERE (User_Id=@id)", del);
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
            object[,] obj = { { "id", rowUser["id"] } };
            DataTable predCheck = dbh.FillDT("SELECT * from tblPredictions WHERE (User_id=@id)", obj);
            DataTable playCheck = dbh.FillDT("SELECT * from tblPlayoffPredictions WHERE (User_Id=@id)", obj);
            if (result < 0 || (predCheck.Rows.Count == 0 && playCheck.Rows.Count == 0))
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
            string checkup = "SELECT * FROM tblGames WHERE finished = 0";
            DataTable dtCheck = dbh.FillDT(checkup);
            if (dtCheck.Rows.Count > 0)
            {
                string countPoules = "SELECT DISTINCT pouleId FROM tblGames";
                DataTable pouleCount = dbh.FillDT(countPoules);

                for (int i = 0; i < pouleCount.Rows.Count; i++)
                {
                    DataRow dr = pouleCount.Rows[i];
                    lvOverviewP1.Groups.Add("poule" + i + "Group", "Poule " + dr["pouleId"].ToString());
                }


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
                    int poule;
                    int.TryParse(match["poule"].ToString(), out poule);
                    poule--;
                    lstItem.Group = lvOverviewP1.Groups[poule];
                    lvOverviewP1.Items.Add(lstItem);
                }
            }
            else
            {
                lvOverviewP1.Groups.Add("quarterFinals", "Quarter Finals");
                lvOverviewP1.Groups.Add("semiFinals", "Semi-Finals");
                lvOverviewP1.Groups.Add("finals", "Finals");
                string quarterQuery = "SELECT * FROM tblPlayoffs WHERE playoffRankingA = 0 AND playoffRankingB = 0 AND finished = 0";
                DataTable quarterFinals = dbh.FillDT(quarterQuery);

                string semiQuery = "SELECT * FROM tblPlayoffs WHERE playoffRankingA = 1 AND playoffRankingB = 1 AND finished = 0"; 
                DataTable semiFinals = dbh.FillDT(semiQuery);

                string finalQuery = "SELECT * FROM tblPlayoffs WHERE playoffRankingA = 2 AND playoffRankingB = 2 AND finished = 0";
                DataTable finals = dbh.FillDT(finalQuery);

                foreach (DataRow quarterFinal in quarterFinals.Rows)
                {
                    object[,] quarter1d = { { "quarter", quarterFinal["pouleRankingA"] } };
                    string quarterIntern1a = "SELECT * FROM tblTeams WHERE pouleRanking = @quarter";
                    DataTable quarterIntern1b = dbh.FillDT(quarterIntern1a, quarter1d);
                    int quarterCount1c = quarterIntern1b.Rows.Count;

                    object[,] quarter2d = { { "quarter", quarterFinal["pouleRankingB"] } };
                    string quarterIntern2a = "SELECT * FROM tblTeams WHERE pouleRanking = @quarter";
                    DataTable quarterIntern2b = dbh.FillDT(quarterIntern2a, quarter2d);
                    int quarterCount2c = quarterIntern2b.Rows.Count;

                    if (quarterCount1c > 0 && quarterCount2c > 0)
                    {
                        object[,] team1 = { { "quarter", quarterFinal["pouleIdA"] }, { "rank", quarterFinal["pouleRankingA"] } };
                        string teamAsql = "SELECT * FROM tblTeams WHERE pouleId = @quarter AND pouleRanking = @rank";
                        DataTable teamAdt = dbh.FillDT(teamAsql, team1);

                        object[,] team2 = { { "quarter", quarterFinal["pouleIdB"] }, { "rank", quarterFinal["pouleRankingB"] } };
                        string teamBsql = "SELECT * FROM tblTeams WHERE pouleId = @quarter AND pouleRanking = @rank";
                        DataTable teamBdt = dbh.FillDT(teamBsql, team2);

                        for (int i = 0; i < teamAdt.Rows.Count; i++)
                        {
                            DataRow teamArow = teamAdt.Rows[i];
                            DataRow teamBrow = teamBdt.Rows[i];

                            ListViewItem lstItem = new ListViewItem(teamArow["teamName"].ToString());
                            lstItem.SubItems.Add(quarterFinal["scoreHomeTeam"].ToString());
                            lstItem.SubItems.Add(quarterFinal["scoreAwayTeam"].ToString());
                            lstItem.SubItems.Add(teamBrow["teamName"].ToString());
                            lstItem.Group = lvOverviewP1.Groups[0];
                            lvOverviewP1.Items.Add(lstItem);
                        }
                    }
                }
                foreach (DataRow semiFinal in semiFinals.Rows)
                {
                    if (quarterFinals.Rows.Count == 0)
                    {
                        object[,] team1 = { { "rank", semiFinal["pouleRankingA"] } };
                        string semiIntern1a = "SELECT * FROM tblTeams WHERE pouleRanking =@rank";
                        DataTable semiIntern1b = dbh.FillDT(semiIntern1a, team1);
                        int semiCount1c = semiIntern1b.Rows.Count;

                        object[,] team2 = { { "rank", semiFinal["pouleRankingB"] } };
                        string semiIntern2a = "SELECT * FROM tblTeams WHERE pouleRanking = @rank";
                        DataTable semiIntern2b = dbh.FillDT(semiIntern2a, team2);
                        int semiCount2c = semiIntern2b.Rows.Count;

                        if (semiCount1c > 0 && semiCount2c > 0)
                        {
                            object[,] team1b = { { "poule", semiFinal["pouleIdA"] }, { "rank", semiFinal["pouleRankingA"] } };
                            string teamAsql = "SELECT * FROM tblTeams WHERE pouleId = " + semiFinal["pouleIdA"].ToString() + " AND pouleRanking = " + semiFinal["pouleRankingA"].ToString() + "";
                            DataTable teamAdt = dbh.FillDT(teamAsql, team1b);

                            object[,] team2b = { { "poule", semiFinal["pouleIdB"] }, { "rank", semiFinal["pouleRankingB"] } };
                            string teamBsql = "SELECT * FROM tblTeams WHERE pouleId = " + semiFinal["pouleIdB"] + " AND pouleRanking = " + semiFinal["pouleRankingB"] + "";
                            DataTable teamBdt = dbh.FillDT(teamBsql,team2b);

                            ListViewItem lsItem = new ListViewItem("semi-final");
                            lsItem.Group = lvOverviewP1.Groups[1];
                            lvOverviewP1.Items.Add(lsItem);

                            for (int i = 0; i < teamAdt.Rows.Count; i++)
                            {
                                DataRow teamArow = teamAdt.Rows[i];
                                DataRow teamBrow = teamBdt.Rows[i];

                                ListViewItem lstItem = new ListViewItem(teamArow["teamName"].ToString());
                                lstItem.SubItems.Add(semiFinal["scoreHomeTeam"].ToString());
                                lstItem.SubItems.Add(semiFinal["scoreAwayTeam"].ToString());
                                lstItem.SubItems.Add(teamBrow["teamName"].ToString());
                                lstItem.Group = lvOverviewP1.Groups[1];
                                lvOverviewP1.Items.Add(lstItem);
                            }
                        }
                    }
                }
                foreach (DataRow final in finals.Rows)
                {
                    if (semiFinals.Rows.Count == 0)
                    {
                        object[,] team1 = { { "rank", final["pouleRankingA"] } };
                        string finalIntern1a = "SELECT * FROM tblTeams WHERE pouleRanking = " + final["pouleRankingA"].ToString() + "";
                        DataTable finalIntern1b = dbh.FillDT(finalIntern1a, team1);
                        int finalCount1c = finalIntern1b.Rows.Count;

                        object[,] team2 = { { "rank", final["pouleRankingB"] } };
                        string finalIntern2a = "SELECT * FROM tblTeams WHERE pouleRanking = " + final["pouleRankingB"].ToString() + "";
                        DataTable finalIntern2b = dbh.FillDT(finalIntern2a, team2);
                        int finalCount2c = finalIntern2b.Rows.Count;

                        if (finalCount1c > 0 && finalCount2c > 0)
                        {
                            object[,] team1b = { { "poule", final["pouleIdA"] }, { "rank", final["pouleRankingA"] } };
                            string teamAsql = "SELECT * FROM tblTeams WHERE pouleId = " + final["pouleIdA"].ToString() + " AND pouleRanking = " + final["pouleRankingA"].ToString() + "";
                            DataTable teamAdt = dbh.FillDT(teamAsql, team1b);

                            object[,] team2b = { { "poule", final["pouleIdB"] }, { "rank", final["pouleRankingB"] } };
                            string teamBsql = "SELECT * FROM tblTeams WHERE pouleId = " + final["pouleIdB"] + " AND pouleRanking = " + final["pouleRankingB"] + "";
                            DataTable teamBdt = dbh.FillDT(teamBsql,team2b);
                            ListViewItem lsItem = new ListViewItem("final");
                            lsItem.Group = lvOverviewP1.Groups[2];
                            lvOverviewP1.Items.Add(lsItem);
                            for (int i = 0; i < teamAdt.Rows.Count; i++)
                            {
                                DataRow teamArow = teamAdt.Rows[i];
                                DataRow teamBrow = teamBdt.Rows[i];

                                ListViewItem lstItem = new ListViewItem(teamArow["teamName"].ToString());
                                lstItem.SubItems.Add(final["scoreHomeTeam"].ToString());
                                lstItem.SubItems.Add(final["scoreAwayTeam"].ToString());
                                lstItem.SubItems.Add(teamBrow["teamName"].ToString());
                                lstItem.Group = lvOverviewP1.Groups[2];
                                lvOverviewP1.Items.Add(lstItem);
                            }
                        }
                    }
                }
            }
        }

        private void ShowScoreCard()
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
                        object[,] obj = { { "poule", quarterFinals.Rows[i]["pouleRankingA"] } };
                        string quarterIntern1a = "SELECT * FROM tblTeams WHERE pouleRanking = @poule";
                        DataTable quarterIntern1b = dbh.FillDT(quarterIntern1a, obj);

                        object[,] obj2 = { { "poule", quarterFinals.Rows[i]["pouleRankingA"] } };
                        string quarterIntern2a = "SELECT * FROM tblTeams WHERE pouleRanking = @poule";
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

                        pnlPredCard.Controls.Add(lblHomeTeam);
                        pnlPredCard.Controls.Add(txtHomePred);
                        pnlPredCard.Controls.Add(txtAwayPred);
                        pnlPredCard.Controls.Add(lblAwayTeam);
                        if ((match["ScoreHomeTeam"].ToString() != null && match["ScoreHomeTeam"].ToString() != "") || (match["ScoreAwayTeam"].ToString() != null && match["ScoreAwayTeam"].ToString() != ""))
                        {
                            rows[i, 0].ReadOnly = true;
                            rows[i, 1].ReadOnly = true;
                        }
                        //}
                    }
                }
                if (quarterFinals.Rows.Count == 0)
                {

                    lengthOutterArray = semiFinals.Rows.Count;
                    rows = new TextBox[lengthOutterArray, lengthInnerArray];
                    index = new int[lengthOutterArray];
                    for (int i = 0; i < semiFinals.Rows.Count; i++)
                    {
                        object[,] obj = { { "rank", semiFinals.Rows[i]["pouleRankingA"] } };
                        string semiIntern1a = "SELECT * FROM tblTeams WHERE pouleRanking = @rank";
                        DataTable semiIntern1b = dbh.FillDT(semiIntern1a, obj);

                        object[,] obj2 = { { "rank", semiFinals.Rows[i]["pouleRankingB"] } };
                        string semiIntern2a = "SELECT * FROM tblTeams WHERE pouleRanking = @rank";
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

                        pnlPredCard.Controls.Add(lblHomeTeam);
                        pnlPredCard.Controls.Add(txtHomePred);
                        pnlPredCard.Controls.Add(txtAwayPred);
                        pnlPredCard.Controls.Add(lblAwayTeam);
                        if ((match["ScoreHomeTeam"].ToString() != null && match["ScoreHomeTeam"].ToString() != "") || (match["ScoreAwayTeam"].ToString() != null && match["ScoreAwayTeam"].ToString() != ""))
                        {
                            rows[i, 0].ReadOnly = true;
                            rows[i, 1].ReadOnly = true;
                        }
                    }
                }
                if (quarterFinals.Rows.Count == 0 && semiFinals.Rows.Count == 0)
                {
                    lengthOutterArray = finals.Rows.Count;
                    rows = new TextBox[lengthOutterArray, lengthInnerArray];
                    index = new int[lengthOutterArray];

                    for (int i = 0; i < finals.Rows.Count; i++)
                    {
                        object[,] obj = { { "rank", finals.Rows[i]["pouleRankingA"] } };
                        string finalIntern1a = "SELECT * FROM tblTeams WHERE pouleRanking =@rank";
                        DataTable finalIntern1b = dbh.FillDT(finalIntern1a, obj);

                        object[,] obj2 = { { "rank", finals.Rows[i]["pouleRankingB"] } };
                        string finalIntern2a = "SELECT * FROM tblTeams WHERE pouleRanking = @rank";
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

                        pnlPredCard.Controls.Add(lblHomeTeam);
                        pnlPredCard.Controls.Add(txtHomePred);
                        pnlPredCard.Controls.Add(txtAwayPred);
                        pnlPredCard.Controls.Add(lblAwayTeam);
                        if ((match["ScoreHomeTeam"].ToString() != null && match["ScoreHomeTeam"].ToString() != "") || (match["ScoreAwayTeam"].ToString() != null && match["ScoreAwayTeam"].ToString() != ""))
                        {
                            rows[i, 0].ReadOnly = true;
                            rows[i, 1].ReadOnly = true;
                        }
                    }
                }
            }
        }

        internal void GetUsername(string un)
        {
            userName = un;
        }

        private void btnEditPrediction_Click(object sender, EventArgs e)
        {
            object[,] ud = { { "username", unLbl.Text } };
            DataTable tblUsers = dbh.FillDT("select * from tblUsers WHERE (Username=@username)", ud);
            DataRow rowUser = tblUsers.Rows[0];
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
                        object[,] obj2 = { { "id", rowUser["id"] }, { "game", index[j] } };
                        string query2 = "SELECT * FROM tblPredictions WHERE (User_id = @id AND Game_id = @game)";
                        DataTable checkup = dbh.FillDT(query2, obj2);
                        if (checkup.Rows.Count == 1)
                        {
                            object[,] upd = { { "home", home }, { "away", away }, { "id", rowUser["id"] }, { "game", index[j] } };
                            dbh.Execute("UPDATE tblPredictions SET PredictedHomeScore=@home, PredictedAwayScore=@away WHERE (User_id=@id AND Game_id=@game)", upd);
                        }
                        else
                        {
                            object[,] upd = { { "home", home }, { "away", away }, { "id", rowUser["id"] }, { "game", index[j] } };
                            dbh.Execute("Insert Into tblPredictions (User_id, Game_id, PredictedHomeScore, PredictedAwayScore) VALUES (@id, @game, @home, @away)", upd);
                        }
                    }
                }
            }
            else
            {
                DataTable dt = dbh.FillDT("SELECT * FROM tblPlayoffPredictions");
                for (int i = 0; i < dt.Rows.Count; i++)
                {
                    DataRow game = dt.Rows[i];
                    for (int k = 0; k < lengthInnerArray; k++)
                    {
                        if (k == 0)
                        {
                            if (rows[i, k].Text == "" || game["ScoreHomeTeam"] == null)
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
                            if (rows[i, k].Text == "" || game["ScoreAwayTeam"] == null)
                            {
                                away = null;
                            }
                            else
                            {
                                away = rows[i, k].Text;
                            }
                        }
                    }
                    if ((game["HomeTeamScore"] == null || game["HomeTeamScore"].ToString() == "") && (game["AwayTeamScore"] == null || game["AwayTeamScore"].ToString() == "") && (home != null && home != "") && (away != null && away != ""))
                    {
                        object[,] pla = { { "id", rowUser["id"] }, { "game", index[i] } };
                        string query2 = "SELECT * FROM tblPlayoffPredictions WHERE (User_id=@id AND Game_id=@game)";
                        DataTable checkup = dbh.FillDT(query2, pla);
                        if (checkup.Rows.Count == 1)
                        {
                            object[,] upd = { { "home", home }, { "away", away }, { "id", rowUser["id"] }, { "game", index[i] } };
                            dbh.Execute("UPDATE tblPlayoffPredictions SET PredictedHomeScore=@home, PredictedAwayScore=@away WHERE (User_id=@id AND Game_id=@game)", upd);
                        }
                        else
                        {
                            object[,] upd = { { "home", home }, { "away", away }, { "id", rowUser["id"] }, { "game", index[i] } };
                            dbh.Execute("Insert Into tblPlayoffPredictions (User_id, Game_id, PredictedHomeScore, PredictedAwayScore) VALUES (@id, @game, @home, @away)", upd);
                        }
                    }
                }
            }

        }

        private void btnInsertPrediction_Click(object sender, EventArgs e)
        {
            object[,] obj = { { "username", unLbl.Text } };
            DataTable tblUsers = dbh.FillDT("SELECT * from tblUsers WHERE (Username=@username)", obj);
            DataRow rowUser = tblUsers.Rows[0];
            string home = "";
            string away = "";

            DataTable tblGames = dbh.FillDT("SELECT * FROM tblGames WHERE finished = 0");

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
                        object[,] upd = { { "home", home }, { "away", away }, { "id", rowUser["id"] }, { "game", index[j] } };
                        dbh.Execute("Insert Into tblPredictions (User_id, Game_id, PredictedHomeScore, PredictedAwayScore) VALUES (@id, @game, @home, @away)", upd);
                    }
                }
            }
            else
            {
                DataTable dt = dbh.FillDT("SELECT * FROM tblPlayoffs WHERE playoffRankingA = 0 AND playoffRankingB = 0");
                for (int i = 0; i < dt.Rows.Count; i++)
                {
                    DataRow game = dt.Rows[i];
                    for (int k = 0; k < lengthInnerArray; k++)
                    {
                        if (k == 0)
                        {
                            if (rows[i, k].Text == "" || game["ScoreHomeTeam"] == null)
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
                            if (rows[i, k].Text == "" || game["ScoreAwayTeam"] == null)
                            {
                                away = null;
                            }
                            else
                            {
                                away = rows[i, k].Text;
                            }
                        }
                    }
                    if ((game["ScoreHomeTeam"] == null || game["ScoreHomeTeam"].ToString() == "") && (game["ScoreAwayTeam"] == null || game["ScoreAwayTeam"].ToString() == "") && (home != null && home != "") && (away != null && away != ""))
                    {
                        object[,] upd = { { "home", home }, { "away", away }, { "id", rowUser["id"] }, { "game", index[i] } };
                        dbh.Execute("Insert Into tblPlayoffPredictions (User_Id, Game_Id, PredictedHomeScore, PredictedAwayScore) VALUES (@id, @game, @home, @away)", upd);
                    }
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
