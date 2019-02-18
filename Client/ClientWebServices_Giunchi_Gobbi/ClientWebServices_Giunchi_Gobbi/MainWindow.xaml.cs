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
        List<string> lst;
        delegate void Delegato(string url);
        static readonly object locker = new object();

        public MainWindow()
        {
            InitializeComponent();
             
        }

        

        private void btn_visualizza_Click(object sender, RoutedEventArgs e)
        {
            string url = "http://10.13.100.5/gobbi/WebServicesLibri-Gobbi-Giunchi/Server/?funzione=0";
            Thread th = new Thread(()=>GetRequest(url));
            th.Start();
            //GetRequest(url);


            //GetRequest(url);
            
            

            while (true)
            {
                if (mycontent != "")
                {
                    MessageBox.Show(mycontent);
                    JavaScriptSerializer json = new JavaScriptSerializer();
                    lst = new List<string>(json.Deserialize<List<string>>(mycontent));

                    foreach (string str in lst)
                    {
                        lst_libri.Items.Add(str);
                    }
                    break;
                }
                
            }
        }

        async static void GetRequest(string url)
        {
            using (HttpClient client = new HttpClient())
            {
                using (HttpResponseMessage response = await client.GetAsync(url))
                {
                    using (HttpContent content = response.Content)
                    {
                        mycontent = await content.ReadAsStringAsync();
                        MessageBox.Show(mycontent);

                        
                        //MessageBox.Show();
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
