using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Data.SqlClient;
using System.IO;

namespace ProjectFifaV2
{
    public partial class frmAdmin : Form
    {
        private DatabaseHandler dbh;
        private OpenFileDialog opfd;

        private Form frmScoreInput;

        DataTable table;

        public frmAdmin()
        {
            dbh = new DatabaseHandler();
            table = new DataTable();
            this.ControlBox = false;
            InitializeComponent();
        }

        private void btnAdminLogOut_Click(object sender, EventArgs e)
        {
            txtQuery.Text = null;
            txtPath = null;
            dgvAdminData.DataSource = null;
            Hide();
        }

        private void btnExecute_Click(object sender, EventArgs e)
        {
            if (txtQuery.TextLength > 0)
            {
                try
                {
                    table.Clear();
                    dgvAdminData.Columns.Clear();
                    ExecuteSQL(txtQuery.Text);
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.ToString(), "Error", MessageBoxButtons.OK);
                }
            }
        }

        private void ExecuteSQL(string selectCommandText)
        {
            SqlDataAdapter dataAdapter = new SqlDataAdapter(selectCommandText, dbh.GetCon());
            dataAdapter.Fill(table);
            dgvAdminData.DataSource = table;
        }

        private void btnSelectFile_Click(object sender, EventArgs e)
        {
            try
            {
                txtPath.Text = null;
                string path = "";
            

                path = GetFilePath();

            
                if (!CheckExtension(path, ".csv"))
                {
                    MessageHandler.ShowMessage("The wrong filetype is selected.");
                }
                else
                {
                    txtPath.Text = path;
                    //
                }
            }
            catch (Exception ex)
            { MessageBox.Show(ex.ToString(), "Error", MessageBoxButtons.OK, MessageBoxIcon.Error, MessageBoxDefaultButton.Button1); }
        }

        private void btnLoadData_Click(object sender, EventArgs e)
        {
            string tableName = tableSelector.Text;
            if (!(txtPath.Text == null) || !(txtPath.Text == ""))
            {

                if (tableName != "" && txtPath.Text != "")
                {
                    if (!CheckExtension(txtPath.Text, ".csv"))
                    {
                        MessageHandler.ShowMessage("The wrong filetype is selected.");
                    }
                    else
                    {
                        try
                        {
                            dbh.Execute("DROP table " + tableName);
                        }
                        catch
                        {

                        }
                        if (tableName == "tblTeams")
                        {
                            dbh.Execute("CREATE table tblTeams (id int NOT NULL, pouleId int NOT NULL, teamName varchar(255) NOT NULL, teamNr int NOT NULL, pouleRanking int NOT NULL, playoffRanking int NOT NULL, PRIMARY KEY (id)) ");
                        }
                        else if (tableName == "tblGames")
                        {
                            dbh.Execute("CREATE table tblGames (Game_ID int NOT NULL, homeTeam int NOT NULL, awayTeam int NOT NULL, pouleId int NOT NULL, HomeTeamScore int NULL, AwayTeamScore int NULL, finished bit NOT NULL, PRIMARY KEY (Game_ID))");
                        }
                        else if (tableName == "tblPlayoffs")
                        {
                            dbh.Execute("CREATE TABLE tblPlayoffs (id int NOT NULL, pouleIdA int NULL, pouleIdB int NULL, pouleRankingA int NULL, pouleRankingB int NULL, playoffIdA int NULL, playoffIdB int NULL, playoffRankingA int NOT NULL, playoffRankingB int NOT NULL, scoreHomeTeam int NULL, scoreAwayTeam int NULL, finished bit NOT NULL, PRIMARY KEY (id))");
                        }
                        try
                        {

                            using (StreamReader reader = new StreamReader(txtPath.Text))
                            {
                                string line;
                                string[] lineWords;
                                do
                                {
                                    line = reader.ReadLine();
                                    lineWords = line.Split(',');
                                    if (tableName == "tblTeams")
                                    {
                                        string insert = "INSERT INTO tblTeams (id, pouleId, teamName, teamNr, pouleRanking, playoffRanking) VALUES ('" + lineWords[0].Trim('"') + "', '" + lineWords[1].Trim('"') + "', '" + lineWords[2].Trim('"') + "', '" + lineWords[3].Trim('"') + "', '" + lineWords[4].Trim('"') + "', '" + lineWords[5].Trim('"') + "')";
                                        dbh.Execute(insert);
                                    }
                                    else if (tableName == "tblGames")
                                    {
                                        string insert;
                                        if (lineWords[6].Trim('"') == "0")
                                        {
                                            insert = "INSERT INTO tblGames (Game_ID, homeTeam, awayTeam, pouleId, finished) VALUES ('" + lineWords[0].Trim('"') + "', '" + lineWords[1].Trim('"') + "', '" + lineWords[2].Trim('"') + "', '" + lineWords[3].Trim('"') + "', '" + lineWords[6].Trim('"') + "')";
                                        }
                                        else
                                        {
                                            insert = "INSERT INTO tblGames (Game_ID, homeTeam, awayTeam, pouleId, HomeTeamScore, AwayTeamScore, finished) VALUES ('" + lineWords[0].Trim('"') + "', '" + lineWords[1].Trim('"') + "', '" + lineWords[2].Trim('"') + "', '" + lineWords[3].Trim('"') + "', '" + lineWords[4].Trim('"') + "', '" + lineWords[5].Trim('"') + "', '" + lineWords[6].Trim('"') + "')";
                                        }
                                        dbh.Execute(insert);
                                    }
                                    else if (tableName == "tblPlayoffs")
                                    {
                                        string insert;
                                        if (lineWords[12].Trim('"') == "1")
                                        {
                                            insert = "INSERT INTO tblPlayoffs (id, pouleIdA, pouleIdB, pouleRankingA, pouleRankingB, playoffIdA, playoffIdB, playoffRankingA, playoffRankingB, scoreHomeTeam, scoreAwayTeam, finished) VALUES ('" + lineWords[0].Trim('"') + "', '" + lineWords[1].Trim('"') + "', '" + lineWords[2].Trim('"') + "', '" + lineWords[3].Trim('"') + "', '" + lineWords[4].Trim('"') + "', '" + lineWords[5].Trim('"') + "', '" + lineWords[6].Trim('"') + "', '" + lineWords[7].Trim('"') + "', '" + lineWords[8].Trim('"') + "', '" + lineWords[9].Trim('"') + "', '" + lineWords[10].Trim('"') + "', '" + lineWords[12].Trim('"') + "')";
                                        }
                                        else
                                        {
                                            insert = "INSERT INTO tblPlayoffs (id, pouleIdA, pouleIdB, pouleRankingA, pouleRankingB, playoffIdA, playoffIdB, playoffRankingA, playoffRankingB, finished) VALUES ('" + lineWords[0].Trim('"') + "', '" + lineWords[1].Trim('"') + "', '" + lineWords[2].Trim('"') + "', '" + lineWords[3].Trim('"') + "', '" + lineWords[4].Trim('"') + "', '" + lineWords[5].Trim('"') + "', '" + lineWords[6].Trim('"') + "', '" + lineWords[7].Trim('"') + "', '" + lineWords[8].Trim('"') + "', '" + lineWords[12].Trim('"') + "')";
                                        }
                                        dbh.Execute(insert);
                                    }
                                    else
                                    {

                                    }

                                } while (!reader.EndOfStream);
                            }

                        }

                        catch (Exception ex)
                        {

                        }

                    }
                }
                else
                {
                    MessageHandler.ShowMessage("No filename selected.");
                }
            }
        }
        
        private string GetFilePath()
        {
            string filePath = "";
            opfd = new OpenFileDialog();

            opfd.Multiselect = false;

            if (opfd.ShowDialog() == DialogResult.OK)
            {
                filePath = opfd.FileName;
            }

            return filePath;
        }

        private bool CheckExtension(string fileString, string extension)
        {
            int extensionLength = extension.Length;
            int strLength = fileString.Length;
            string ext = "";
            try {
                ext = fileString.Substring(strLength - extensionLength, extensionLength);
            }
            catch (Exception ex)
            {

            }

            if (ext == extension)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        private void insertBtn_Click(object sender, EventArgs e)
        {
            frmScoreInput = new frmScoreInput();
            frmScoreInput.Show();
        }
    }
}
