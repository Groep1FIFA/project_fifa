namespace ProjectFifaV2
{
    partial class frmScoreInput
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(frmScoreInput));
            this.pnlScores = new System.Windows.Forms.Panel();
            this.button1 = new System.Windows.Forms.Button();
            this.calculateScore = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // pnlScores
            // 
            this.pnlScores.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom) 
            | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
            this.pnlScores.AutoScroll = true;
            this.pnlScores.BackColor = System.Drawing.Color.White;
            this.pnlScores.Location = new System.Drawing.Point(22, 26);
            this.pnlScores.Name = "pnlScores";
            this.pnlScores.Size = new System.Drawing.Size(361, 358);
            this.pnlScores.TabIndex = 0;
            // 
            // button1
            // 
            this.button1.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.button1.Location = new System.Drawing.Point(389, 12);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(133, 60);
            this.button1.TabIndex = 1;
            this.button1.Text = "Insert";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // calculateScore
            // 
            this.calculateScore.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.calculateScore.Location = new System.Drawing.Point(389, 78);
            this.calculateScore.Name = "calculateScore";
            this.calculateScore.Size = new System.Drawing.Size(133, 59);
            this.calculateScore.TabIndex = 2;
            this.calculateScore.Text = "CalculateScore";
            this.calculateScore.UseVisualStyleBackColor = true;
            this.calculateScore.Click += new System.EventHandler(this.calculateScore_Click);
            // 
            // frmScoreInput
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackgroundImage = ((System.Drawing.Image)(resources.GetObject("$this.BackgroundImage")));
            this.ClientSize = new System.Drawing.Size(534, 538);
            this.Controls.Add(this.calculateScore);
            this.Controls.Add(this.button1);
            this.Controls.Add(this.pnlScores);
            this.Name = "frmScoreInput";
            this.Text = "frmScoreInput";
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.Panel pnlScores;
        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.Button calculateScore;
    }
}