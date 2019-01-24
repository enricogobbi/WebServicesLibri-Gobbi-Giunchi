﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.Net.Http;
using System.IO;

namespace ClientWebServices_Giunchi_Gobbi
{
    /// <summary>
    /// Logica di interazione per MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        public MainWindow()
        {
            InitializeComponent();
        }

        private void btn_visualizza_Click(object sender, RoutedEventArgs e)
        {
            string url = "http://10.13.100.3/gobbi/WebService/Server/service.php?name=" + "Panic";
            GetRequest(url);
            //Print();
        }

        async static void GetRequest(string url)
        {
            using (HttpClient client = new HttpClient())
            {
                using (HttpResponseMessage response = await client.GetAsync(url))
                {
                    using (HttpContent content = response.Content)
                    {//possiamo usare HttpContentHeader headers = content.Headers;
                        string mycontent = await content.ReadAsStringAsync();
                        MessageBox.Show(mycontent);
                    }
                }
            }
        }

        public void Print(string output)
        {
            lst_libri.Items.Add(output);
        }

        private void btn_pulisci_Click(object sender, RoutedEventArgs e)
        {
            lst_libri.Items.Clear();
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        {

        }

        private void btn_visualizza_elenco_libri_Click(object sender, RoutedEventArgs e)
        {

        }

        private void btn_elenco_tra_date_Click(object sender, RoutedEventArgs e)
        {

        }
    }
}