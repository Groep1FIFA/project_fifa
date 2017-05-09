﻿namespace ProjectFifaV2
{
    partial class frmPlayer
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.btnEditPrediction = new System.Windows.Forms.Button();
            this.btnClearPrediction = new System.Windows.Forms.Button();
            this.btnLogOut = new System.Windows.Forms.Button();
            this.lblResultsOverview = new System.Windows.Forms.Label();
            this.btnShowRanking = new System.Windows.Forms.Button();
            this.lvOverviewP1 = new System.Windows.Forms.ListView();
            this.clmHomeTeam = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.clmHomeTeamScore = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.clmAwayTeamScore = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.clmAwayTeam = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.pnlPredCard = new System.Windows.Forms.Panel();
            this.btnInsertPrediction = new System.Windows.Forms.Button();
            this.unLbl = new System.Windows.Forms.Label();
            this.lvOverviewP2 = new System.Windows.Forms.ListView();
            this.columnHeader1 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader2 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader3 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.columnHeader4 = ((System.Windows.Forms.ColumnHeader)(new System.Windows.Forms.ColumnHeader()));
            this.SuspendLayout();
            // 
            // btnEditPrediction
            // 
            this.btnEditPrediction.Location = new System.Drawing.Point(485, 86);
            this.btnEditPrediction.Margin = new System.Windows.Forms.Padding(4);
            this.btnEditPrediction.Name = "btnEditPrediction";
            this.btnEditPrediction.Size = new System.Drawing.Size(141, 37);
            this.btnEditPrediction.TabIndex = 1;
            this.btnEditPrediction.Text = "Edit Prediction";
            this.btnEditPrediction.UseVisualStyleBackColor = true;
            this.btnEditPrediction.Click += new System.EventHandler(this.btnEditPrediction_Click);
            // 
            // btnClearPrediction
            // 
            this.btnClearPrediction.Location = new System.Drawing.Point(485, 146);
            this.btnClearPrediction.Margin = new System.Windows.Forms.Padding(4);
            this.btnClearPrediction.Name = "btnClearPrediction";
            this.btnClearPrediction.Size = new System.Drawing.Size(141, 37);
            this.btnClearPrediction.TabIndex = 2;
            this.btnClearPrediction.Text = "Clear Prediction";
            this.btnClearPrediction.UseVisualStyleBackColor = true;
            this.btnClearPrediction.Click += new System.EventHandler(this.btnClearPrediction_Click);
            // 
            // btnLogOut
            // 
            this.btnLogOut.Location = new System.Drawing.Point(485, 213);
            this.btnLogOut.Margin = new System.Windows.Forms.Padding(4);
            this.btnLogOut.Name = "btnLogOut";
            this.btnLogOut.Size = new System.Drawing.Size(141, 37);
            this.btnLogOut.TabIndex = 3;
            this.btnLogOut.Text = "Log Out";
            this.btnLogOut.UseVisualStyleBackColor = true;
            this.btnLogOut.Click += new System.EventHandler(this.btnLogOut_Click);
            // 
            // lblResultsOverview
            // 
            this.lblResultsOverview.AutoSize = true;
            this.lblResultsOverview.Location = new System.Drawing.Point(776, 25);
            this.lblResultsOverview.Margin = new System.Windows.Forms.Padding(4, 0, 4, 0);
            this.lblResultsOverview.Name = "lblResultsOverview";
            this.lblResultsOverview.Size = new System.Drawing.Size(117, 17);
            this.lblResultsOverview.TabIndex = 5;
            this.lblResultsOverview.Text = "Results Overview";
            // 
            // btnShowRanking
            // 
            this.btnShowRanking.Location = new System.Drawing.Point(485, 26);
            this.btnShowRanking.Margin = new System.Windows.Forms.Padding(4);
            this.btnShowRanking.Name = "btnShowRanking";
            this.btnShowRanking.Size = new System.Drawing.Size(141, 37);
            this.btnShowRanking.TabIndex = 6;
            this.btnShowRanking.Text = "Show Ranking";
            this.btnShowRanking.UseVisualStyleBackColor = true;
            this.btnShowRanking.Click += new System.EventHandler(this.btnShowRanking_Click);
            // 
            // lvOverviewP1
            // 
            this.lvOverviewP1.Columns.AddRange(new System.Windows.Forms.ColumnHeader[] {
            this.clmHomeTeam,
            this.clmHomeTeamScore,
            this.clmAwayTeamScore,
            this.clmAwayTeam});
            this.lvOverviewP1.FullRowSelect = true;
            this.lvOverviewP1.Location = new System.Drawing.Point(635, 44);
            this.lvOverviewP1.Margin = new System.Windows.Forms.Padding(4);
            this.lvOverviewP1.Name = "lvOverviewP1";
            this.lvOverviewP1.Size = new System.Drawing.Size(318, 738);
            this.lvOverviewP1.TabIndex = 7;
            this.lvOverviewP1.UseCompatibleStateImageBehavior = false;
            this.lvOverviewP1.View = System.Windows.Forms.View.Details;
            // 
            // clmHomeTeam
            // 
            this.clmHomeTeam.Text = "HomeTeam";
            this.clmHomeTeam.Width = 100;
            // 
            // clmHomeTeamScore
            // 
            this.clmHomeTeamScore.Text = "Score";
            this.clmHomeTeamScore.TextAlign = System.Windows.Forms.HorizontalAlignment.Center;
            this.clmHomeTeamScore.Width = 50;
            // 
            // clmAwayTeamScore
            // 
            this.clmAwayTeamScore.Text = "Score";
            this.clmAwayTeamScore.TextAlign = System.Windows.Forms.HorizontalAlignment.Center;
            this.clmAwayTeamScore.Width = 50;
            // 
            // clmAwayTeam
            // 
            this.clmAwayTeam.Text = "Away Team";
            this.clmAwayTeam.Width = 100;
            // 
            // pnlPredCard
            // 
            this.pnlPredCard.Location = new System.Drawing.Point(16, 44);
            this.pnlPredCard.Margin = new System.Windows.Forms.Padding(4);
            this.pnlPredCard.Name = "pnlPredCard";
            this.pnlPredCard.Size = new System.Drawing.Size(461, 737);
            this.pnlPredCard.TabIndex = 8;
            // 
            // btnInsertPrediction
            // 
            this.btnInsertPrediction.Location = new System.Drawing.Point(485, 258);
            this.btnInsertPrediction.Margin = new System.Windows.Forms.Padding(4);
            this.btnInsertPrediction.Name = "btnInsertPrediction";
            this.btnInsertPrediction.Size = new System.Drawing.Size(141, 37);
            this.btnInsertPrediction.TabIndex = 9;
            this.btnInsertPrediction.Text = "Insert Prediction";
            this.btnInsertPrediction.UseVisualStyleBackColor = true;
            this.btnInsertPrediction.Click += new System.EventHandler(this.btnInsertPrediction_Click);
            // 
            // unLbl
            // 
            this.unLbl.AutoSize = true;
            this.unLbl.Location = new System.Drawing.Point(12, 9);
            this.unLbl.Name = "unLbl";
            this.unLbl.Size = new System.Drawing.Size(0, 17);
            this.unLbl.TabIndex = 10;
            // 
            // lvOverviewP2
            // 
            this.lvOverviewP2.Columns.AddRange(new System.Windows.Forms.ColumnHeader[] {
            this.columnHeader1,
            this.columnHeader2,
            this.columnHeader3,
            this.columnHeader4});
            this.lvOverviewP2.FullRowSelect = true;
            this.lvOverviewP2.Location = new System.Drawing.Point(961, 44);
            this.lvOverviewP2.Margin = new System.Windows.Forms.Padding(4);
            this.lvOverviewP2.Name = "lvOverviewP2";
            this.lvOverviewP2.Size = new System.Drawing.Size(318, 738);
            this.lvOverviewP2.TabIndex = 11;
            this.lvOverviewP2.UseCompatibleStateImageBehavior = false;
            this.lvOverviewP2.View = System.Windows.Forms.View.Details;
            // 
            // columnHeader1
            // 
            this.columnHeader1.Text = "HomeTeam";
            this.columnHeader1.Width = 100;
            // 
            // columnHeader2
            // 
            this.columnHeader2.Text = "Score";
            this.columnHeader2.TextAlign = System.Windows.Forms.HorizontalAlignment.Center;
            this.columnHeader2.Width = 50;
            // 
            // columnHeader3
            // 
            this.columnHeader3.Text = "Score";
            this.columnHeader3.TextAlign = System.Windows.Forms.HorizontalAlignment.Center;
            this.columnHeader3.Width = 50;
            // 
            // columnHeader4
            // 
            this.columnHeader4.Text = "Away Team";
            this.columnHeader4.Width = 100;
            // 
            // frmPlayer
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(1344, 898);
            this.Controls.Add(this.lvOverviewP2);
            this.Controls.Add(this.unLbl);
            this.Controls.Add(this.btnInsertPrediction);
            this.Controls.Add(this.pnlPredCard);
            this.Controls.Add(this.lvOverviewP1);
            this.Controls.Add(this.btnShowRanking);
            this.Controls.Add(this.lblResultsOverview);
            this.Controls.Add(this.btnLogOut);
            this.Controls.Add(this.btnClearPrediction);
            this.Controls.Add(this.btnEditPrediction);
            this.Margin = new System.Windows.Forms.Padding(4);
            this.Name = "frmPlayer";
            this.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen;
            this.Text = "PlayerName";
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion
        private System.Windows.Forms.Button btnClearPrediction;
        private System.Windows.Forms.Button btnLogOut;
        private System.Windows.Forms.Label lblResultsOverview;
        private System.Windows.Forms.Button btnShowRanking;
        private System.Windows.Forms.ListView lvOverviewP1;
        private System.Windows.Forms.ColumnHeader clmHomeTeam;
        private System.Windows.Forms.ColumnHeader clmHomeTeamScore;
        private System.Windows.Forms.ColumnHeader clmAwayTeamScore;
        private System.Windows.Forms.ColumnHeader clmAwayTeam;
        private System.Windows.Forms.Panel pnlPredCard;
        private System.Windows.Forms.Button btnEditPrediction;
        private System.Windows.Forms.Button btnInsertPrediction;
        private System.Windows.Forms.Label unLbl;
        private System.Windows.Forms.ListView lvOverviewP2;
        private System.Windows.Forms.ColumnHeader columnHeader1;
        private System.Windows.Forms.ColumnHeader columnHeader2;
        private System.Windows.Forms.ColumnHeader columnHeader3;
        private System.Windows.Forms.ColumnHeader columnHeader4;
    }
}