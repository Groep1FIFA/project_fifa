using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Data.SqlClient;
using MySql.Data;
using System.IO;

namespace ProjectFifaV2
{
    public partial class frmAdmin : Form
    {
        private DatabaseHandler dbh;
        private OpenFileDialog opfd;

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
            //dbh.TestConnection();
            SqlDataAdapter dataAdapter = new SqlDataAdapter(selectCommandText, dbh.GetCon());
            dataAdapter.Fill(table);
            dgvAdminData.DataSource = table;
        }

        private void btnSelectFile_Click(object sender, EventArgs e)
        {
            txtPath.Text = null;
            
            string path = GetFilePath();

            if (CheckExtension(path, "csv"))
            {
                txtPath.Text = path;
            }
            else
            {
                MessageHandler.ShowMessage("The wrong filetype is selected.");
            }
        }

        private void btnLoadData_Click(object sender, EventArgs e)
        {
            if (!(txtPath.Text == null) || !(txtPath.Text == ""))
            {
                /*string insert = "BULK INSERT TblTeams" +
               " FROM '" + txtPath.Text + "'" +
                "WITH" +
                "(" +
                   " FIRSTROW = 1," +
                   " FIELDTERMINATOR = ',', " +
                   " ROWTERMINATOR = ';', " +
                   " TABLOCK" +
                ")";
                dbh.OpenConnectionToDB();
                ExecuteSQL(insert);
                dbh.CloseConnectionToDB();*/
                using (StreamReader reader = new StreamReader(txtPath.Text))
                {
                    string line;
                    string[] lineWords;
                    do
                    {
                        line = reader.ReadLine();
                        lineWords = line.Split(',');
                        string insert = "INSERT tblTeams (Team_id, TeamName) VALUES ('" + lineWords[0].Trim('"') + "', '" + lineWords[1].Trim('"') + "')";
                        dbh.Execute(insert);
                    } while (!reader.EndOfStream);
                }
                //string[][] data = File.ReadLines(filePath).Select(x => x.Split(',')).ToArray();
                //foreach (var dataI in data)
                //{
                //    foreach (var dataJ in dataI)
                //    {
                //        MessageBox.Show(dataJ);
                //    }
                //}
            }
            else
            {
                MessageHandler.ShowMessage("No filename selected.");
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

            string ext = fileString.Substring(strLength - extensionLength, extensionLength);

            if (ext == extension)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}
