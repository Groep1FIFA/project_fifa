using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Data.SqlClient;
using System.Windows.Forms;
using System.Data;

namespace ProjectFifaV2
{
    class DatabaseHandler
    {
        private SqlConnection con;

        public DatabaseHandler()
        {
            string Path = Environment.CurrentDirectory;
            string[] appPath = Path.Split(new string[] { "bin" }, StringSplitOptions.None);
            AppDomain.CurrentDomain.SetData("DataDirectory", appPath[0]);

            con = new SqlConnection(@"Data Source=(LocalDB)\MSSQLLocalDB;AttachDbFilename='|DataDirectory|\db.mdf';Integrated Security=True;Connect Timeout=30");
        }

        public void TestConnection()
        {
            bool open = false;
            
            try
            {
                con.Open();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
            finally
            {
                if (con.State == System.Data.ConnectionState.Open)
                {
                    open = true;
                }
                con.Close();
            }

            if (!open)
            {
                Application.Exit();
            }
        }

        public void OpenConnectionToDB()
        {
            try {
                con.Open();
            }
            catch
            {

            }
        }

        public void CloseConnectionToDB()
        {
            con.Close();
        }

        public void Execute(string query)
        {
            SqlCommand queryExecute = new SqlCommand(query, con);
            try {
                OpenConnectionToDB();
                int result = queryExecute.ExecuteNonQuery();
            }
            catch
            {

            }
            finally
            {
                CloseConnectionToDB();
            }
        }

        public void Execute(string query, object[,] param)
        {
            SqlCommand queryExecute = new SqlCommand(query, con);
            try
            {
                OpenConnectionToDB();
                for (int i = 0; i < param.Length / 2; i++)
                {
                    queryExecute.Parameters.AddWithValue(param[i, 0].ToString(), param[i, 1]);
                }
            
                int result = queryExecute.ExecuteNonQuery();
            }
            catch
            {

            }
            finally
            {
                CloseConnectionToDB();
            }
        }

        public System.Data.DataTable FillDT(string query)
        {
            TestConnection();
            OpenConnectionToDB();

            SqlDataAdapter dataAdapter = new SqlDataAdapter(query, GetCon());
            DataTable dt = new DataTable();
            dataAdapter.Fill(dt);
            
            CloseConnectionToDB();

            return dt;
        }

        public System.Data.DataTable FillDT(string query, object[,] param)
        {
            TestConnection();
            OpenConnectionToDB();

            SqlDataAdapter dataAdapter = new SqlDataAdapter(query, GetCon());

            SqlCommand command = new SqlCommand(query, GetCon());

            // Add the parameters for the SelectCommand.
            for (int i = 0; i < param.Length / 2; i++)
            {
                command.Parameters.AddWithValue(param[i, 0].ToString(), param[i, 1]);
            }

            dataAdapter.SelectCommand = command;

            DataTable dt = new DataTable();
            SqlDataReader read = command.ExecuteReader();
            while (read.Read())
            {
                object[] row = new object[read.FieldCount];// dt.Rows.Add(read);
                for (int i = 0; i < read.FieldCount; i++)
                {
                    row[i] = read[i];
                    if (dt.Columns.Count < read.FieldCount)
                    {
                        dt.Columns.Add(read.GetName(i));
                    }
                }
                dt.Rows.Add(row);
            }
            CloseConnectionToDB();

            return dt;
        }

        public SqlConnection GetCon()
        {
            return con;
        }
    }
}
