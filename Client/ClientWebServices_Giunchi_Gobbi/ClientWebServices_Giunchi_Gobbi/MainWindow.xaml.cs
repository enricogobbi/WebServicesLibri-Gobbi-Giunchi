using System;
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
using System.Xml;
using System.Xml.Linq;
using Newtonsoft.Json;
using System.Web.Script.Serialization;
using System.Threading;

namespace ClientWebServices_Giunchi_Gobbi
{
    /// <summary>
    /// Logica di interazione per MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        static string mycontent = "";
        static string titoli;
        static string[] splittato = new string[10];
        string url = "http://10.13.100.5/gobbi/WebServicesLibri-Gobbi-Giunchi/Server/?funzione=";

        public MainWindow()
        {
            InitializeComponent();
        }

        

        private async void btn_visualizza_Click(object sender, RoutedEventArgs e)
        {
            Task task = GetRequest(url + "0");
            await task;
            string str;

            foreach(string tmp in splittato)
            {
                str = tmp.Trim('"');
                lst_libri.Items.Add(str);
            }
        }

        async static Task GetRequest(string url)
        {
            using (HttpClient client = new HttpClient())
            {
                using (HttpResponseMessage response = await client.GetAsync(url))
                {
                    using (HttpContent content = response.Content)
                    {
                        mycontent = await content.ReadAsStringAsync();

                        bool find = true;
                        int start = 0;
                        int end = 0;
                        int i = 0;

                        while (find == true)
                        {
                            if (mycontent.Substring(start).Contains("data"))
                            {
                                start = mycontent.IndexOf("data", start);
                                end = mycontent.IndexOf("}", start);
                                titoli = mycontent.Substring(start + 7, end - start - 8);
                                splittato = titoli.Split(',');
                                start++;
                                i++;
                            }
                            else
                            {
                                find = false;
                            }
                        }
                        //MessageBox.Show(mycontent);
                    }
                }
            }
        }

        private void btn_pulisci_Click(object sender, RoutedEventArgs e)
        {
            lst_libri.Items.Clear();
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        {
            GetRequest(url + "1");
            string str;

            foreach (string tmp in splittato)
            {
                str = tmp.Trim('"');
                lst_libri.Items.Add(str);
            }

            //MessageBox.Show(mycontent);
        }

        private void btn_visualizza_elenco_libri_Click(object sender, RoutedEventArgs e)
        {
            GetRequest(url + "2");
            string str;

            foreach (string tmp in splittato)
            {
                str = tmp.Trim('"');
                lst_libri.Items.Add(str);
            }

            //MessageBox.Show(mycontent);
        }

        private void btn_elenco_tra_date_Click(object sender, RoutedEventArgs e)
        {
            GetRequest(url + "3");
            string str;

            foreach (string tmp in splittato)
            {
                str = tmp.Trim('"');
                lst_libri.Items.Add(str);
            }

            //MessageBox.Show(mycontent);
        }
    }
}
