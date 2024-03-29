<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
// Connect to database
$db = mysqli_connect('localhost', 'root', '', 'jfsa');

// Retrieve user details from database
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_assoc($result);

$userN = $_SESSION['username'];


// -----------------
$sql = "SELECT * FROM locations  WHERE username = '$username'";
$result = $db->query($sql);

$locations = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}



if ($db->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GeoPulse</title>
<!--    <link rel="icon" type="image/x-icon" href="images/logo1.png"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.0/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css">


    <!-- BOOTSTRAP only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <!-- jquery -->
    <script src="libs/jquery.js"></script>
    <link rel="stylesheet" href="libs/jquery-ui-1.12.1/jquery-ui.css">
    <script src="libs/jquery-ui-1.12.1/external/jquery/jquery.js"></script>
    <script src="libs/jquery-ui-1.12.1/jquery-ui.js"></script>

    <!-- Leaflet -->

    <link rel="stylesheet" href="libs/leaflet/leaflet.css" />
    <script src="libs/leaflet/leaflet.js"></script>

    <!-- ZoomBar & slider-->
    <script src="libs/L.Control.ZoomBar-master/src/L.Control.ZoomBar.js"></script>
    <link rel="stylesheet" href="libs/L.Control.ZoomBar-master/src/L.Control.ZoomBar.css" />
    <script src="libs/Leaflet.zoomslider-master/src/L.Control.Zoomslider.js"></script>
    <link rel="stylesheet" href="libs/Leaflet.zoomslider-master/src/L.Control.Zoomslider.css" />

    <!-- MousePosition -->
    <script src="libs/Leaflet.MousePosition-master/src/L.Control.MousePosition.js"></script>
    <link rel="stylesheet" href="libs/Leaflet.MousePosition-master/src/L.Control.MousePosition.css" />

    <!-- line-measure -->
    <link rel="stylesheet" href="libs/polyline-measure/line-measure.css" />
    <script src="libs/polyline-measure/line-measure.js"></script>
    <link rel="stylesheet" href="libs/leaflet-measure-master/leaflet-measure.css" />
    <script src="libs/leaflet-measure-master/leaflet-measure.js"></script>
    <script src="libs/feat.js"></script>


    <!-- draw -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.2/leaflet.draw.css" />
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.4.2/leaflet.draw.js"></script> -->

    <!-- 
  <link rel="stylesheet" href="libs/leaflet-draw-control.css"> -->
    <script src="libs/leaflet-draw-control.js"></script>


    <!-- github -->
    <script src="https://kartena.github.io/Proj4Leaflet/lib/proj4-compressed.js"></script>
    <script src="https://kartena.github.io/Proj4Leaflet/src/proj4leaflet.js"></script>



    <!-- legend -->

    <link rel="stylesheet" href="libs/leaflet-wms-legend/leaflet.wmslegend.css" />
    <script src="libs/leaflet-wms-legend/leaflet.wmslegend.js"></script>


    <!-- csslink -->
    <link rel="stylesheet" href="mystyle1.css">


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- html2pdfcdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.0/jspdf.umd.min.js"></script> -->

    <script src="libs/leaflet-image.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>

    <!-- fontawsome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- json -->

    <script>

    $(function() {
                        var availableTags = ['Pimpalgaon Tarf Khed,Khed', 'Rohakal,Khed', 'Kanersar,Khed', 'Chinchoshi,Khed',
                            'Sabalewadi,Khed', 'Sidhegavhan,Khed', 'Torne Bk,Khed', 'Hedruj,Khed', 'Davadi,Khed',
                            'Nimgaon,Khed', 'Kadus,Khed', 'Talawade,Khed', 'Kohinde Bk.,Khed', 'Bahirwadi,Khed',
                            'Pangari,Khed', 'Pait,Khed', 'Shelu,Khed', 'Koregaon Kh,Khed', 'Koregaon bk,Khed',
                            'Chandus,Khed', 'Retavadi,Khed', 'Kharpudi Bk,Khed', 'Pimpari Bk,Khed', 'Kharpudi Kh,Khed',
                            'Kalewadi,Khed', 'Padali,Khed', 'Koye,Khed', 'Gosasi,Khed', 'Vadgaon Ghenand,Khed',
                            'Manjarewadi,Khed', 'Kurkundi,Khed', 'Raundhalwadi,Khed', 'Kalus,Khed', 'Shive,Khed',
                            'Gargotiwadi,Khed', 'Kiwale,Khed', 'Jaidwadi,Khed', 'Khalchi Bhamburwadi,Khed',
                            'Arudewadi,Khed', 'Kohinkarwadi,Khed', 'Gulani,Khed', 'Pur,Khed', 'Jaulke Kh.,Khed',
                            'Waki BK,Khed', 'Ahire,Khed', 'Dhamane,Khed', 'Askhed Bk,Khed', 'Kaman,Khed', 'Chas,Khed',
                            'Papalwadi,Khed', 'Mirajewadi,Khed', 'Wakalwadi,Khed', 'Takalkarwadi,Khed', 'Donde,Khed',
                            'Wahagaon,Khed', 'Karanj Vihire,Khed', 'Koyali Tarf Chakan,Khed', 'Akharwadi,Khed',
                            'Jaulke Bk.,Khed', 'Vaphgaon,Khed', 'Chichbaiwadi,Khed', 'Gadakwadi,Khed', 'Chaudharwadi,Khed',
                            'Warude,Khed', 'Butewadi,Khed', 'Waki Tarf Wada,Khed', 'Gonvadi,Khed', 'Pimpari Kh.,Khed',
                            'Bahul,Khed', 'Chakan,Khed', 'Rajgurunagar,Khed', 'Shelgaon,Khed', 'Askhed Kh,Khed',
                            'Parale,Khed', 'Malkhed,Haveli', 'Bakori,Haveli', 'Gogalwadi,Haveli', 'Kondhanpur,Haveli',
                            'Mandavi Kh,Haveli', 'Mandavi Bk.,Haveli', 'Khadakwadi,Haveli', 'Aambi,Haveli',
                            'Alandi Mhatobachi,Haveli', 'Walati,Haveli', 'Manerwadi,Haveli', 'Tikekarwadi,Haveli',
                            'Khanapur,Haveli', 'Sashte,Haveli', 'Tarade,Haveli', 'Dongargaon,Haveli', 'Thoptewadi,Haveli',
                            'Gorhe Kh.,Haveli', 'Dehu,Haveli', 'Malinagar,Haveli', 'Vitthal Nagar,Haveli',
                            'Gorhe Bk.,Haveli', 'Bivari,Haveli', 'Sangarun,Haveli', 'Agalambe,Haveli', 'Arvi,Haveli',
                            'Pimpri Tarf Sandas,Haveli', 'Sangavi Tarf Sandas,Haveli', 'Jambhali,Haveli', 'Ashtapur,Haveli',
                            'Bhagatwadi,Haveli', 'Gaud Dara,Haveli', 'Wade Bolhai,Haveli', 'Shindwane,Haveli',
                            'Kalyan,Haveli', 'Ghera Sinhgad,Haveli', 'Kudaje,Haveli', 'Donaje,Haveli', 'Wanjalewadi,Haveli',
                            'Burkegaon,Haveli', 'Nhavi Sandas,Haveli', 'Shindewadi,Haveli', 'Gawadewadi,Haveli',
                            'Murkutenagar,Haveli', 'Khamgaon Tek,Haveli', 'Ramoshiwadi,Haveli', 'Mokarwadi,Haveli',
                            'Khadewadi,Haveli', 'Bahuli,Haveli', 'Tanaji Nagar,Haveli', 'Sonapur,Haveli', 'Vardare,Haveli',
                            'Sambharewadi,Haveli', 'Khamgaon Mawal,Haveli', 'Mogarwadi,Haveli', 'Mordarwadi,Haveli',
                            'Avasare,Haveli', 'Rahatvade,Haveli', 'Hingangaon,Haveli', 'Shiraswadi,Haveli', 'Katavi,Mawal',
                            'Kale,Mawal', 'Prabhachiwadi,Mawal', 'Yelse,Mawal', 'Ardav (Adavi),Mawal', 'Kadadhe,Mawal',
                            'Nane,Mawal', 'Tung,Mawal', 'Tikona,Mawal', 'Chavsar,Mawal', 'Boravli,Mawal', 'Inglun,Mawal',
                            'Khand,Mawal', 'Kolechafesar,Mawal', 'Kusur,Mawal', 'Morave,Mawal', 'Pimpriwadi,Mawal',
                            'Shivali,Mawal', 'Thoran,Mawal', 'Ukasan,Mawal', 'Vadeshwar,Mawal', 'Kusgaon P.m.,Mawal',
                            'Takave Bk.,Mawal', 'Shevati,Mawal', 'Pansoli,Mawal', 'Atvan,Mawal', 'Malegao Bk.,Mawal',
                            'Apati,Mawal', 'Bhajgaon,Mawal', 'Bhoyre,Mawal', 'Divad,Mawal', 'Govitri,Mawal', 'Jovan,Mawal',
                            'Kondiwade,Mawal', 'Pachane,Mawal', 'Pale,Mawal', 'Udhewadi,Mawal', 'Vadavali,Mawal',
                            'Pangloli,Mawal', 'Karandoli,Mawal', 'Dhaiwali,Mawal', 'Shilatane,Mawal', 'Takave Kh.,Mawal',
                            'Rajpuri,Mawal', 'Kurvande,Mawal', 'Lonawale,Mawal', 'Malavandi Thule,Mawal', 'Ambegaon,Mawal',
                            'Kunewadi,Mawal', 'Dahuli,Mawal', 'Mangarul,Mawal', 'Somwadi,Mawal', 'Kondivade N.m,Mawal',
                            'Dudhivare,Mawal', 'Vagheshwar,Mawal', 'Shilimb,Mawal', 'Sawale,Mawal', 'Pusane,Mawal',
                            'Malegao Kh,Mawal', 'Ajivali,Mawal', 'Valakh,Mawal', 'Sawantwadi,Mawal', 'Malewadi,Mawal',
                            'Mahagaon,Mawal', 'Lohagad,Mawal', 'Kambare N.m.,Mawal', 'Dhalewadi,Mawal', 'Budhavadi,Mawal',
                            'Gevhandhe,Mawal', 'Nagatali,Mawal', 'Rakaswadi,Mawal', 'Kusavali,Mawal', 'Wahangao,Mawal',
                            'Kiwale,Mawal', 'Shirdhe,Mawal', 'Jamboli,Mawal', 'Nandgaon,Mawal', 'Shindgaon,Mawal',
                            'Kambare Andar,Mawal', 'Kashal,Mawal', 'Khandashi,Mawal', 'Nesave,Mawal', 'Valavanti,Mawal',
                            'Dhangavhan,Mawal', 'Ovale,Mawal', 'Yelghol,Mawal', 'Velholi,Mawal', 'Karanjgaon,Mawal',
                            'Sangise,Mawal', 'Pale Nane Mawal,Mawal', 'Mormarwadi,Mawal', 'Mau,Mawal', 'Kacharewadi,Mawal',
                            'Vaund,Mawal', 'Brahmanwadi,Mawal', 'Done,Mawal', 'Kothurne,Mawal', 'Shire,Mawal',
                            'Belaj,Mawal', 'Jeware,Mawal', 'Vehergaon,Mawal', 'Budhle N.M.,Mawal', 'Vadivale,Mawal',
                            'Adhale bk,Mawal', 'Adhale kh.,Mawal', 'Shivane,Mawal', 'Phalne,Mawal', 'Ghonshet,Mawal',
                            'Sai,Mawal', 'Bhadawali,Mawal', 'Majgaon,Mawal', 'Pimpal Khunte,Mawal', 'Malawali P.m.,Mawal',
                            'Bedse,Mawal', 'Thugaon,Mawal', 'Varu,Mawal', 'Phagane,Mawal', 'Kadav,Mawal',
                            'Gevhande Khadak,Mawal', 'Keware,Mawal', 'Thakursai,Mawal', 'Brahmanoli,Mawal', 'Muthe,Mulshi',
                            'Padalgharwadi,Mulshi', 'Mugaon,Mulshi', 'Valane,Mulshi', 'Ugavali,Mulshi', 'Jawal,Mulshi',
                            'Ghutake,Mulshi', 'Ekole,Mulshi', 'Tailbaila,Mulshi', 'Bhambarde,Mulshi', 'Botarwadi,Mulshi',
                            'Chandivali,Mulshi', 'Andgaon,Mulshi', 'Dhamanohol,Mulshi', 'Koloshi,Mulshi', 'Sakhari,Mulshi',
                            'Kharavade,Mulshi', 'Pimploli,Mulshi', 'Masgaon,Mulshi', 'Wadavali,Mulshi', 'Admal,Mulshi',
                            'Bhembhatmal,Mulshi', 'Palase,Mulshi', 'Patharshet,Mulshi', 'Saiv Kh,Mulshi', 'Bhode,Mulshi',
                            'Mulshi Kh,Mulshi', 'Tata Talav,Mulshi', 'Karmoli,Mulshi', 'Shedani,Mulshi', 'Kolwan,Mulshi',
                            'Sathesai,Mulshi', 'Mugavde,Mulshi', 'Katavadi,Mulshi', 'Davje,Mulshi', 'Malegaon,Mulshi',
                            'Wanjale,Mulshi', 'Watunde,Mulshi', 'Vede,Mulshi', 'Mandede,Mulshi', 'Jatede,Mulshi',
                            'Kashing,Mulshi', 'Amaralewadi,Mulshi', 'Barape_Bk,Mulshi', 'Bhalgudi,Mulshi', 'Saltar,Mulshi',
                            'Shirvali,Mulshi', 'Nive,Mulshi', 'Andhale,Mulshi', 'Ambarwet,Mulshi', 'Vandre,Mulshi',
                            'Vadgaon,Mulshi', 'Pimpri,Mulshi', 'Rihe,Mulshi', 'Katarkhadak,Mulshi', 'Nandgaon,Mulshi',
                            'Padalghar,Mulshi', 'Mose Kh.,Mulshi', 'Dhadavali,Mulshi', 'Gadhale,Mulshi', 'Chinchwad,Mulshi',
                            'Kule,Mulshi', 'Hulavalewadi,Mulshi', 'Tamhini Bk,Mulshi', 'Warak,Mulshi', 'Vegre,Mulshi',
                            'Kolavade,Mulshi', 'Kondhur,Mulshi', 'Pethshahapur,Mulshi', 'Devghar,Mulshi', 'Ambavane,Mulshi',
                            'Kolavali,Mulshi', 'Pomgaon,Mulshi', 'Male,Mulshi', 'Kumbheri,Mulshi', 'Jamgaon,Mulshi',
                            'Share,Mulshi', 'Disali,Mulshi', 'Shileshwar,Mulshi', 'Bhadas Bk.,Mulshi', 'Gavadewadi,Mulshi',
                            'Dongrgaon,Mulshi', 'Hotale,Mulshi', 'Nanegaon,Mulshi', 'Asade,Mulshi', 'Chikhali Bk.,Mulshi',
                            'Belwade,Mulshi', 'Tav,Mulshi', 'Chale,Mulshi', 'Darawali (Dakhli),Mulshi', 'Morewadi,Mulshi',
                            'Bharekarwadi,Mulshi', 'Kasarsai,Mulshi', 'Visakhar,Mulshi', 'Walen,Mulshi', 'Hadasi,Mulshi',
                            'Kalamshet,Mulshi', 'Vitthalwadi,Mulshi', 'Andeshe,Mulshi', 'Khubawali,Mulshi',
                            'Temghar,Mulshi', 'Lavarde,Mulshi', 'Khechre,Mulshi', 'Marnewadi,Mulshi', 'Bhuini,Mulshi',
                            'Savargaon,Mulshi', 'Akole,Mulshi', 'Kondhavale,Mulshi', 'Paud,Mulshi', 'Dakhane,Mulshi',
                            'Sambhave,Mulshi', 'Khamboli,Mulshi', 'Kemasewadi,Mulshi', 'Adgaon,Mulshi',
                            'Chikhalgaon,Mulshi', 'Ravade,Mulshi', 'Nandivali,Mulshi', 'Dasave,Mulshi', 'Bhambavade,Bhor',
                            'Bhongavli,Bhor', 'Dhangavadi,Bhor', 'Gunand,Bhor', 'Kenjal,Bhor', 'Khadki,Bhor', 'Kikavi,Bhor',
                            'Morwadi,Bhor', 'Nhavi,Bhor', 'Nidhan,Bhor', 'Nigade,Bhor', 'Pande,Bhor', 'Panjalwadi,Bhor',
                            'Rajapur,Bhor', 'Sangavi Kh,Bhor', 'Sarole,Bhor', 'Savardare,Bhor', 'Taprewadi,Bhor',
                            'Umbare,Bhor', 'Vathar Kh,Bhor', 'Wagajwadi,Bhor', 'Degaon,Bhor', 'Didghar,Bhor',
                            'Jambhali,Bhor', 'Kambare,Bhor', 'Kanjale,Bhor', 'Karandi,Bhor', 'Ketkavane,Bhor', 'Khopi,Bhor',
                            'Kolavadi,Bhor', 'Kurungvadi,Bhor', 'Kusgaon,Bhor', 'Malegaon,Bhor', 'Parvadi,Bhor',
                            'Ranje,Bhor', 'Salavade,Bhor', 'Sangavi Bk,Bhor', 'Sonavadi,Bhor', 'Virwadi,Bhor',
                            'Tamhanwadi,Daund', 'Sahajpurwadi,Daund', 'Nandur,Daund', 'Dalimb,Daund', 'Takali,Daund',
                            'Vadgaon Bande,Daund', 'Koregaon Bhiwar,Daund', 'Telewadi,Daund', 'Panwali,Daund',
                            'Pilanwadi,Daund', 'Mirwadi,Daund', 'Dahitane,Daund', 'Devkarwadi,Daund', 'Boribhadak,Daund',
                            'Boriaindi,Daund', 'Boratewadi,Daund', 'Bharatgaon,Daund', 'Jawajebuwachiwadi,Daund',
                            'Kamatwadi,Daund', 'Kasurdi,Daund', 'Bhandgaon,Daund', 'Patethan,Daund', 'Tambewadi,Daund',
                            'Ladkatwadi,Daund', 'Khutbav,Daund', 'Pimpalgaon,Daund', 'Delvadi (Ekeriwadi),Daund',
                            'Undawadi,Daund', 'Nathachiwadi,Daund', 'Valki,Daund', 'Rahu,Daund', 'Khamgaon,Daund',
                            'Nangaon,Daund', 'Amoni Mal,Daund', 'Ganesh Road,Daund', 'Varwand,Daund', 'Khopodi,Daund',
                            'Handalwadi,Daund', 'Wakhari,Daund', 'Dapodi,Daund', 'Pargaon,Daund', 'Deshmukh Mala,Daund',
                            'Nimbalkar Wasti,Daund', 'Galandwadi,Daund', 'Kodit Kh.,Purandar', 'Pur,Purandar',
                            'Pokhar,Purandar', 'Warwadi,Purandar', 'Kumbhoshi,Purandar', 'Somurdi,Purandar',
                            'Gherapurandhar,Purandar', 'Supe  Kh.,Purandar', 'Misalwadi,Purandar', 'Thapewadi,Purandar',
                            'Bhivari,Purandar', 'Bhivadi,Purandar', 'Bahirwadi,Purandar', 'Bhopgaon,Purandar',
                            'Patharwadi,Purandar', 'Pimpale,Purandar', 'Panvadi,Purandar', 'Hivare,Purandar',
                            'Kodit Budruk,Purandar', 'Askarwadi,Purandar', 'Chambali,Purandar', 'Borhalewadi,Purandar',
                            'Garade,Purandar', 'Ambawane,Velhe', 'Chinchale Bk,Velhe', 'Karajwane,Velhe', 'Adavali,Velhe',
                            'Askawadi,Velhe', 'Ketkavane,Velhe', 'Kolwadi,Velhe', 'Lasirgaon,Velhe', 'Margasani,Velhe',
                            'Khambawadi,Velhe', 'Mangdari,Velhe', 'Mangaon,Velhe', 'Pole,Velhe', 'Kasedi,Velhe',
                            'Chikhali Kh,Velhe', 'Bhalvadi,Velhe', 'Thangaon,Velhe', 'Shirkoli,Velhe', 'Ghodshet,Velhe',
                            'Ambed,Velhe', 'Khamgaon,Velhe', 'Vinzar,Velhe', 'Wangani,Velhe', 'Varasgaon,Velhe',
                            'Ambegaon Kh,Velhe', 'Gholapghar,Velhe', 'Kambegi,Velhe', 'Ranjane,Velhe', 'Dapode,Velhe',
                            'Chinchale Kh,Velhe', 'Kuran Bk,Velhe', 'Vadghar,Velhe', 'Givashi,Velhe', 'Koshimghar,Velhe',
                            'Mose Bk,Velhe', 'Saiv Bk.,Velhe', 'Ranavdi,Velhe', 'Boravale,Velhe', 'Nigade Bk.,Velhe',
                            'Panshet,Velhe', 'Kondgaon,Velhe', 'Malavli,Velhe', 'Kathawadi,Velhe', 'Rule,Velhe',
                            'Kuran Kh,Velhe', 'Dhindli,Velhe', 'Ambegaon Bk,Velhe', 'Osade,Velhe', 'Nigade Mose,Velhe',
                            'Kandave,Velhe', 'Wanjalwadi,Velhe', 'Kurvathi,Velhe', 'Pimpale Jagtap,Shirur',
                            'Wajewadi,Shirur', 'Kasari,Shirur', 'Jategaon Kh.,Shirur', 'Takali Bhima,Shirur',
                            'Kondhapuri,Shirur', 'Khairewadi,Shirur', 'Rautwadi,Shirur', 'Karandi,Shirur', 'Amdabad,Shirur',
                            'Jategaon Bk.,Shirur', 'Kendur,Shirur', 'Shastabad,Shirur', 'Khandale,Shirur',
                            'Sone Sangavi,Shirur', 'Hivare,Shirur', 'Burunjwadi,Shirur', 'Darekarwadi,Shirur',
                            'Vitthalwadi,Shirur', 'Dingrajwadi,Shirur', 'Malthan,Shirur', 'Waghale,Shirur', 'Varude,Shirur',
                            'Shivtakrar Mahalungi,Shirur', 'Shingadwadi,Shirur', 'Ranjangaon Sandas,Shirur',
                            'Rakshewadi,Shirur', 'Pimpale Dumal,Shirur', 'Pimpari Dumala,Shirur', 'Nimgaon Bhogi,Shirur',
                            'Mukhai,Shirur', 'Motewadi,Shirur', 'Midgulwadi,Shirur', 'Lakhewadi,Shirur',
                            'Karanjawane,Shirur', 'Golegaon,Shirur', 'Dhanore,Shirur', 'Dhamari,Shirur', 'Dahiwadi,Shirur',
                            'Chincholi,Shirur', 'Bhambarde,Shirur', 'Arangaon,Shirur', 'Ambale,Shirur',
                            'Ganegaon Burunjwadi,Shirur', 'Kanhur mesai,Shirur', 'Pabal,Shirur', 'Andhalgaon,Shirur',
                            'Nimone,Shirur', 'Nhavara,Shirur', 'Kohakdewadi,Shirur', 'Uralgaon,Shirur',
                            'Alegaon Paga,Shirur', 'Nagargaon,Shirur', 'Babhulsar Kh.,Shirur', 'Chavhanwadi,Shirur',
                            'Parodi,Shirur', 'Nimgaon Mhalungi,Shirur', 'Karade,Shirur', 'Nighoje,Khed', 'Moi,Khed',
                            'Khalumbre,Khed', 'Mahalunge,Khed', 'Kanhewadi Tarf Chakan,Khed', 'Sangurdi,Khed',
                            'Shinde,Khed', 'Wasuli,Khed', 'Yevalewadi,Khed', 'Sawardari,Khed', 'Kuruli,Khed',
                            'Medankarwadi,Khed', 'Nanekarwadi,Khed', 'Sudumbare,Mawal', 'Chimbali,Khed', 'Indori,Mawal',
                            'Biradwadi,Khed', 'Bhamboli,Khed', 'Ambethan,Khed', 'Kharabwadi,Khed', 'Warale,Khed',
                            'Kadachiwadi,Khed', 'Sudhavadi,Mawal', 'Jambavade,Mawal', 'Bhare,Mulshi', 'Kasar Amboli,Mulshi',
                            'Mukhaiwadi,Mulshi', 'Uravade,Mulshi', 'Bhukum,Mulshi', 'Nande,Mulshi', 'Sus,Mulshi',
                            'Bavadhan Bk,Mulshi', 'Vadki,Haveli', 'Ghotavde,Mulshi', 'Talegaon Dabhade (R),Mawal',
                            'Urse,Mawal', 'Parandwadi,Mawal', 'Bhilarewadi,Haveli', 'Jambhulwadi,Haveli', 'Kolavadi,Haveli',
                            'Sasewadi,Bhor', 'Shindewadi,Bhor', 'Lavale,Mulshi', 'Man,Mulshi', 'Sanas Nagar,Haveli',
                            'Nandoshi,Haveli', 'Pirangut,Mulshi', 'Vadgaon Shinde,Haveli', 'Loni-Kalbhor,Haveli',
                            'Dive,Purandar', 'Ambegaon,Mulshi', 'Pawarwadi,Purandar', 'Kalewadi,Purandar',
                            'Sonori,Purandar', 'Bhugaon,Mulshi', 'Manjari Kh.,Haveli', 'Manjari Bk,Haveli',
                            'Kolavdi,Haveli', 'Shevalwadi,Haveli', 'Kadamvak Wasti,Haveli', 'Holkarwadi,Haveli',
                            'Autad Handewadi,Haveli', 'Vadachiwadi,Haveli', 'Pisoli,Haveli', 'Bebad Ohol,Mawal',
                            'Dhamne,Mawal', 'Godumbare,Mawal', 'Shirgaon,Mawal', 'Gahunje,Mawal', 'Salumbe,Mawal',
                            'Sangavade,Mawal', 'Jambe,Mulshi', 'Nere,Mulshi', 'Dattawadi,Mulshi', 'Hinjavadi,Mulshi',
                            'Marunji,Mulshi', 'Mahalunge,Mulshi', 'Materewadi,Mulshi', 'Bhoirwadi,Mulshi',
                            'Bhegdewadi,Mulshi', 'Chande,Mulshi', 'Mulkhed,Mulshi', 'Godambewadi,Mulshi',
                            'Bhowarapur,Haveli', 'Uruli Kanchan,Haveli', 'Peth,Haveli', 'Koregaon Mul,Haveli',
                            'Prayagdham,Haveli', 'Sortapwadi,Haveli', 'Kunjirwadi,Haveli', 'Theur,Haveli', 'Naygaon,Haveli',
                            'Mangewadi,Haveli', 'Nimbalkarwadi,Haveli', 'Kirkitwadi,Haveli', 'Khadakwasale,Haveli',
                            'Nanded ,Haveli', 'Kopare,Haveli', 'Kondhave Dhavade,Haveli', 'Mendhewadi,Mawal',
                            'Varale,Mawal', 'Malawadi,Mawal', 'Ambi,Mawal', 'Brahman Wadi,Mawal', 'Sate,Mawal',
                            'Mohitewadi,Mawal', 'Chikhalse,Mawal', 'Ahirvade,Mawal', 'Kusgaon Kh.,Mawal',
                            'Khadkale (CT),Mawal', 'Kamshet,Mawal', 'Paravadi,Mawal', 'Sadavli,Mawal', 'Ozarde,Mawal',
                            'Baur,Mawal', 'Brahmanwadi,Mawal', 'Karunj,Mawal', 'Adhe Kh.,Mawal', 'Somatane,Mawal',
                            'Jadhavwadi,Mawal', 'Akurdi,Mawal', 'Nanoli Tarf Chakan,Mawal', 'Sangavi,Mawal',
                            'Umbare Navalakh,Mawal', 'Badhalawadi,Mawal', 'Naygaon,Mawal', 'Nanoli N.m.,Mawal',
                            'Jambhul,Mawal', 'Kanhe,Mawal', 'Ambale,Mawal', 'Nigade,Mawal', 'Kalhat,Mawal',
                            'Pawalewadi,Mawal', 'Boraj,Mawal', 'Patan,Mawal', 'Kusgaon Bk. (CT),Mawal', 'Devale,Mawal',
                            'Aundhe kh.,Mawal', 'Dongargaon,Mawal', 'Aundholi,Mawal', 'Varsoli,Mawal', 'Karla,Mawal',
                            'Waksai,Mawal', 'Devghar,Mawal', 'Sadapur,Mawal', 'Kune N.m.,Mawal', 'Bhaje,Mawal',
                            'Malvali,Mawal', 'Mudhavare,Mawal', 'Taje,Mawal', 'Pathargaon,Mawal', 'Pimploli,Mawal',
                            'Santosh Nagar ,Khed', 'Rakshewadi,Khed', 'Waki Kh,Khed', 'Holewadi,Khed', 'Chandoli,Khed',
                            'Varachi Bhamburwadi,Khed', 'Sandbhorwadi,Khed', 'Pacharnewadi,Khed', 'Dhorewadi,Khed',
                            'Vadgaon Tarf Khed,Khed', 'Shiroli,Khed', 'Satkarsthal,Khed', 'Koregaon Bhima _CT,Shirur',
                            'Sanaswadi ,Shirur', 'Shikrapur,Shirur', 'Talegaon Dhamdhere,Shirur', 'Apti,Shirur',
                            'Vadu Bk.,Shirur', 'Shirur_Annapur_Saradwadi_Tardobachiwadi_Kardilwad,Shirur',
                            'Dhok Sangavi,Shirur', 'Karegaon,Shirur', 'Ranjangaon Ganpati,Shirur', 'Vanpuri,Purandar',
                            'Udachiwadi,Purandar', 'Singapur,Purandar', 'Zendewadi,Purandar', 'Jadhavwadi,Purandar',
                            'Kumbharvalan,Purandar', 'Ambodi,Purandar', 'Gurholi,Purandar', 'Bhose,Khed', 'Alandi,Khed',
                            'Dhanore,Khed', 'Rase,Khed', 'Solu,Khed', 'Pimpalgaon Tarf Chakan,Khed', 'Tulapur,Haveli',
                            'Nirgudi,Haveli', 'Markal,Khed', 'Charholi Kh.,Khed', 'Kelgaon,Khed', 'Shivapur,Haveli',
                            'Khed Shivapur,Haveli', 'Ramnagar,Haveli', 'Kasurdi,Bhor', 'Shivare,Bhor', 'Velu,Bhor',
                            'Hrishchandri,Bhor', 'Kapurhol,Bhor', 'Divale,Bhor', 'Kamthadi,Bhor', 'Kelavade,Bhor',
                            'Nasrapur,Bhor', 'Naygaon,Bhor', 'Varve Bk.,Bhor', 'Varve Kh.,Bhor', 'Ketkawale,Purandar',
                            'Chivewadi,Purandar', 'Devadi,Purandar', 'Awhalwadi,Haveli', 'Loni-kand,Haveli',
                            'Kesnand,Haveli', 'Taleranwadi,Haveli', 'Bhavadi,Haveli', 'Perane,Haveli', 'Phulgaon,Haveli',
                            'Wadhu Kh,Haveli', 'Wagholi,Haveli', 'Dhumalicha Mala,Daund', 'Kedgaon,Daund',
                            'Kedgaon Station,Daund', 'Dhaygudewadi,Daund', 'Boripardhi,Daund', 'Yawat Station,Daund',
                            'Yawat,Daund', 'Chandkhed,Mawal', 'Darumbare,Mawal', 'Vadgaon,Mawal', 'Golegaon,Khed',
                            'Narhe,Haveli'
                        ];

             
$(document).ready(function() {
        $(".search").autocomplete({
            source: function(request, response) {
                const marathiText = /^[\u0900-\u097F\s]*$/;
                var array = [request.term];

                if (marathiText.test(array[0])) {
                    $.ajax({
                        url: 'searche.php',
                        method: 'POST',
                        data: { search2: array[0] },
                        success: function(data) {              
                            var autocompleteData = data.map(function(item) {
                                return {
                                    label: item.ownername + ', ' + item.villagename + ', ' + item.talukaname + ', ' + item.gutnumber,
                                    value: item.ownername + ',' + item.villagename + ',' + item.talukaname + ',' + item.gutnumber.split('/')[0]
                                };
                            });
                            // console.log(autocompleteData,"autocompleteData")
                            response(autocompleteData);
                        },
                        error: function(error) {
                            console.error('AJAX request failed:', error);
                        }
                    });
                } else {
                    var filteredData = availableTags.filter(function (item){
                                return item.toLowerCase().includes(array[0].toLowerCase());
                            })
                            response(filteredData);
                    // Handle non-Marathi text
                }
            }
        });
    });


    });

    
                        
                    

    </script>


    <!--Bookmarks-->

    <!-- <script src="libs/bookmark.js"></script> -->
    <!-- <link href="libs/leaflet.bookmark.css" rel="stylesheet"> -->

    <style>
    /* CSS Styles for the Location Table */

    #locationTable {
        width: 100%;
        border-collapse: collapse;
        color: #333;
        border: none;
        /* Font color for table elements */
    }

    /* #locationTable th, */
    #locationTable td a {
        padding: 2px;
        text-align: left;
        font-size: 12px;
        /* border-bottom: 1px solid #ddd; */

    }

    #locationTable td a:hover {
        color: greenyellow;

    }
   
    #locationTable a {
        color: whitesmoke;
        /* Font color for links */
        text-decoration: none;
    }

    .deleteBtn {
        padding: 5px 10px;
        /* background-color: #dc3545; */
        background: transparent;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-left: 20px;
    }

    .deleteBtn:hover {
       color: #c82333;
    }
    
    .table-wrapper {
        width: 200px;
    max-height: 200px; /* Adjust the desired height as needed */
    overflow-y: auto;
}

    .my-custom-class {
        padding: 5px;
        font-size: 10px;
    }

    .my-success-popup-class {
        padding: 10px;
        font-size: 8px;
    }
    .my-custom-class1 {
        padding: 2px;
        font-size: 10px;
    }

    .my-success-popup-class1{
        padding: 5px;
        font-size: 8px;
    }

    .my-title-class{
            padding-top: 10px;
            font-weight: bold;
            font-size: 15px;
            color: #004aad;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <h2 style="color:#dddddd">
                <!-- <img class="imglogo" src="images/logo1.png" alt="image not found"
                        style="width:40px; height:40px; border-radius:180%; background-color:#dddddd; margin-top:-3%;"> -->
                    GIS Portal</h2>
            </div>
            <ul class="sidebar-nav">
                <li class="active">
                    <h3 class="profile-username  fw-bold text-capitalize fs-5 px-4 pt-4 " style="color:#dddddd;">
                        <?php
                        echo $_SESSION['username'];
                        ?>
                    </h3>

                    <p class="text-muted px-4 ">
                        <?php
                        echo $user['email'];
                        ?>
                    </p>

                </li>

                <li>
                    <!-- <div class=""> -->
                        <a class="fs-6 px-3" style="color:#dddddd;" href="index.php"> <i class="fa-solid fa-house"></i>   Dashboard</a>
                    <!-- </div> -->
                </li>

                <li>
                    <!-- <div class=""> -->
                        <a class="fs-6 px-3" style="color:#dddddd;" href="tabledemo.php"><i class="fas fa-chart-line"></i>   Statistics </a>
                    <!-- </div> -->
                </li>
                <hr>
                <li>
                    <!-- *************************prompt ************** -->

                

                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle  text-light ms-1 " type="button"
                            id="locationDropdown" data-bs-toggle="dropdown" aria-expanded="false"> <i
                                class="fad fa-bookmark"></i>
                            Bookmarks
                        </button>
                        <ul class="dropdown-menu ms-1 px-2 bg-transparent" aria-labelledby="locationDropdown" >
                            <p type="button" class="text-light" style="font-size:12px;" id="saveBtn">Create Bookmark <i
                                    class="far fa-plus-circle"></i>
                            </p>
                            <div class="table-wrapper">
                                <table id="locationTable">
                                    <tbody></tbody>
                                    
                                    
                                </table>
                            </div>
                        </ul>
                    </div>


                </li>

                <li class="nav-item">

                </li>
            </ul>
        </aside>

        <div id="navbar-wrapper">
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">

                        <a href="#" class="navbar-brand fs-3" id="sidebar-toggle" onclick="toggleSidebar()">
                            <i id="sidebar-open-icon" class="fas fa-angle-double-left"
                                style=" padding :10px; border-radius:180%;  color: #343a40;"></i>
                            <i id="sidebar-close-icon" class="fas fa-angle-double-right d-none"
                                style=" color: #343a40;"></i>
                        </a>
                    </div>
                    <form action="Logout.php" method="post">
                        <button class="tablinks bg-danger text-light p-1 px-2"
                            style="border:0; font-size:15px ; border-radius:10px;" name="Logout" type="submit"><img
                                src="images/logout2.jpg" alt="image not found" style=" width:20px; height:20px;"><span
                                class="d-none d-sm-inline"> logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <section id="content-wrapper">
            <div id="map"></div>

            <div id="main">
                <!-- <img src="images/logo1.png" alt="image not found"> -->
                <div class="main_search">
                    <input type="text" placeholder="Search.." name="search2" class="search">

                    <button class="bg-light" id="btnData2" type="button" onclick="SearchMe(); sendData()"><i
                            class="far fa-search"></i></button>

                    <button class="btn-success" id="btnData1" type="button" onclick="ClearMe()">Clear</button>


                    <button onclick="takeScreenshot()" id="save-btn" class="text-light border-0 "
                        style="background:#004aad;    "><i class="fas fa-download"></i></button>
                    <!-- <button >Export Map</button> -->

                </div>
                <!-- <i class="fad fa-user fs-3 " ></i>
    <p class="username" onclick="openNav()">
        <?php echo $_SESSION['username']; ?>
    </p> -->

            </div>
        </section>


    </div>



    <!-- *********************************************MAP.JS******************************************************************************************* -->








    <script>
    // MAP

    var map, geojson;

    //Add Basemap
    var map = L.map("map", {}).setView([18.55, 73.85], 10, L.CRS.EPSG4326);

    var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });


    var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    })

    var Esri_WorldImagery = L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {
            attribution: "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community"
        }
    );
    // <!-- -----------------layer displayed------------------------ -->


    var baseLayers = {
        SImagery: Esri_WorldImagery,
        GoogleImage: googleSat,
        OSM: osm,
    };


    var wms_layer1 = L.tileLayer.wms(

        "https://portal.geopulsea.com/geoserver/zone/wms", {
            // layers: layerName,
            format: "image/png",
            transparent: true,
            tiled: true,
            version: "1.1.0",
            attribution: "Revenue",
            opacity: 1,

        }
    );




    var wms_layer12 = L.tileLayer.wms(
        "https://portal.geopulsea.com/geoserver/zone/wms", {
            layers: "zone:Revenue",
            format: "image/png",
            transparent: true,
            tiled: true,
            version: "1.1.0",
            attribution: "Revenue",
            opacity: 1,

        }
    );

    


    var userRole = "<?php echo $user['role']; ?>";
    // var userRole = "lohar";

    var shantaramList = ['zone:DP', 'zone:Revenue', 'zone:RP', 'zone:Change_overlay1', 'zone:Change_overlay', 'zone:Modification'];
    var adminList = ['zone:DP', 'zone:Revenue', 'zone:RP' ];
    var user2 = ['zone:DP', 'zone:Revenue' ];
    var finalDraftList = ['zone:DP', 'zone:Revenue', 'zone:RP', 'zone:Change_overlay1'];
    // var finalDraftList = ['DP:Revenue', 'DP:VILLAGE_BOUNDARY', 'DP:Taluka_Boundary'];
    var concatenatedList = [];
    var wmsLayersNames = ["DP", "Revenue", "RP", "Change_overlay1"];
    // var wmsLayersNames = ["Revenue", "VILLAGE_BOUNDARY", "Taluka_Boundary"];
    var wmsLayerss = {};

    if (userRole === "shantaram") {
        finalDraftList = shantaramList;
        wmsLayersNames = ['DP', 'Revenue', "RP", "Change_overlay1", "Change_overlay", "Modification"];
    }
    if (userRole === "admin") {
        finalDraftList = adminList;
        wmsLayersNames = ["DP", "Revenue", "RP"];
    }
    if (userRole === "user2") {
        finalDraftList = user2;
        wmsLayersNames = [ "DP", "Revenue"];
    }
    // console.log(finalDraftList);
    // console.log(wmsLayersNames);

    for (var i = 0; i < finalDraftList.length; i++) {
        var concatenatedString = 'wms_layer' + (i + 1);

        // Function to create the GeoServer layer
        var geoserverLayer = createGeoServerLayer(userRole);

        function createGeoServerLayer(userRole) {
            var concatenatedString = finalDraftList[i];
            return createWMSLayer(concatenatedString)
        }
        if (finalDraftList[i] === "zone:DP") {
            geoserverLayer.addTo(map);
        }
        wmsLayerss[wmsLayersNames[i]] = geoserverLayer;
        concatenatedList.push(concatenatedString);
    }

    function createWMSLayer(layerName) {
        return L.tileLayer.wms('https://portal.geopulsea.com/geoserver/zone/wms', {
            layers: layerName,
            format: 'image/png',
            transparent: true,
            tiled: true,
            version: "1.1.0",
            attribution: "Revenue_Boundary",
            opacity: 0.7,
        });
    }
    var control = new L.control.layers(baseLayers, wmsLayerss).addTo(map);



    map.on("contextmenu", (e) => {
        let size = map.getSize();
        let bbox = map.getBounds().toBBoxString();
        let layer = 'zone:Revenue';
        let style = 'zone:Revenue';
        let urrr =
            `https://portal.geopulsea.com/geoserver/zone/wms?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetFeatureInfo&FORMAT=image%2Fpng&TRANSPARENT=true&QUERY_LAYERS=${layer}&STYLES&LAYERS=${layer}&exceptions=application%2Fvnd.ogc.se_inimage&INFO_FORMAT=application/json&FEATURE_COUNT=50&X=${Math.round(e.containerPoint.x)}&Y=${Math.round(e.containerPoint.y)}&SRS=EPSG%3A4326&WIDTH=${size.x}&HEIGHT=${size.y}&BBOX=${bbox}`

        if (urrr) {
        fetch(urrr)
            .then((response) => response.json())
            .then((html) => {
                var htmldata = html.features[0].properties;
                let keys = Object.keys(htmldata);
                let values = Object.values(htmldata);
                var gutNumber = values[1];
                var villageName = values[3];
                var talukaName = values[2];
                $.ajax({
                    url: 'tr.php', // Replace with the actual path to your PHP script
                    method: 'POST',
                    data: {
                        gutNumber: gutNumber.split('/')[0],
                        villageName: villageName,
                        talukaName: talukaName
                    },
                    success: function (data) {
                        var ownersData = data; 
                        let finalOwnerNames = ownersData.map(owner => owner.ownername).join('<br>');
                        
                        let ownerAreas = Array.from(new Set(ownersData.map(owner => owner.area))).join('<br>');

                        let txtk1 = "";
                        var xx = 0;
                        for (let gb in keys) {
                            txtk1 += "<tr><td>" + keys[xx] + "</td><td>" + values[xx] + "</td></tr>";
                            xx += 1;
                        }

                        let detaildata1 =
                            "<div style='max-height: 350px;  overflow-y: scroll;'><table  style='width:70%;' class='popup-table' >" +
                            txtk1 + "<tr><td>OwnerName</td><td>" + finalOwnerNames +
                            "</td></tr><tr><td>7/12 Area </td><td>" + ownerAreas +
                            "</td></tr><tr><td>Co-Ordinates</td><td>" + e.latlng +
                            "</td></tr></table></div>";

                        L.popup()
                            .setLatLng(e.latlng)
                            .setContent(detaildata1)
                            .openOn(map);
                    },
                    error: function (error) {
                        console.error('AJAX request failed:', error);
                    }
                });
            });
}
    });


    map.on('dblclick', function(e) {
 
        let size = map.getSize();
        let bbox = map.getBounds().toBBoxString();
        let layer = 'zone:Revenue';
        let style = 'zone:Revenue';
        let urrr =
            `https://portal.geopulsea.com/geoserver/zone/wms?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetFeatureInfo&FORMAT=image%2Fpng&TRANSPARENT=true&QUERY_LAYERS=${layer}&STYLES&LAYERS=${layer}&exceptions=application%2Fvnd.ogc.se_inimage&INFO_FORMAT=application/json&FEATURE_COUNT=50&X=${Math.round(e.containerPoint.x)}&Y=${Math.round(e.containerPoint.y)}&SRS=EPSG%3A4326&WIDTH=${size.x}&HEIGHT=${size.y}&BBOX=${bbox}`
 
        // you can use this url for further processing such as fetching data from server or showing it on the map
 
        if (urrr) {
            fetch(urrr)
 
                .then((response) => response.json())
                .then((html) => {
              
                    var htmldata = html.features[0].properties
                    // console.log(htmldata,"_____________________")
                    var coordinatesArray =  html.features[0].geometry.coordinates[0][0]
                    var coordinatesList = coordinatesArray.join(', ');
                    // console.log(coordinatesList,"******************************", coordinatesArray)
                    var  geometryType = html.features[0].geometry.type
 
 
                    var coordinatesWithAltitude = coordinatesArray.map(function(coord) {
                        return [coord[0].toFixed(15), coord[1].toFixed(15)  , 0];
                                });
 
                        // console.log(coordinatesWithAltitude);
 
 
 
                    function generateKML(geometryType, coordinatesArray) {
 
                        var kml =`<?xml version="1.0" encoding="UTF-8"?>
                                    <kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
                                    <Document>
                                        <name>UPolygon.kml</name>
                                        <StyleMap id="m_ylw-pushpin">
                                            <Pair>
                                                <key>normal</key>
                                                <styleUrl>#s_ylw-pushpin</styleUrl>
                                            </Pair>
                                            <Pair>
                                                <key>highlight</key>
                                                <styleUrl>#s_ylw-pushpin_hl</styleUrl>
                                            </Pair>
                                        </StyleMap>
                                        <Style id="s_ylw-pushpin">
                                            <IconStyle>
                                                <scale>1.1</scale>
                                                <Icon>
                                                    <href>http://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png</href>
                                                </Icon>
                                                <hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
                                            </IconStyle>
                                            <LineStyle>
                                                <color>ff00ff00</color>
                                                <width>5</width>
                                            </LineStyle>
                                            <PolyStyle>
                                                <color>80ffffff</color>
                                            </PolyStyle>
                                        </Style>
                                        <Style id="s_ylw-pushpin_hl">
                                            <IconStyle>
                                                <scale>1.3</scale>
                                                <Icon>
                                                    <href>http://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png</href>
                                                </Icon>
                                                <hotSpot x="20" y="2" xunits="pixels" yunits="pixels"/>
                                            </IconStyle>
                                            <LineStyle>
                                                <color>ff00ff00</color>
                                                <width>5</width>
                                            </LineStyle>
                                            <PolyStyle>
                                                <color>80ffffff</color>
                                            </PolyStyle>
                                        </Style>
                                        <Placemark>
                                            <name>Untitled Polygon</name>
                                            <styleUrl>#m_ylw-pushpin</styleUrl>
                                            <Polygon>
                                                <tessellate>1</tessellate>
                                                <outerBoundaryIs>
                                                    <LinearRing>
                                                        <coordinates>
                                                        ${coordinatesArray.join(' ')}
                                                        </coordinates>
                                                        </LinearRing>
                                                    </outerBoundaryIs>
                                                </Polygon>
                                            </Placemark>
                                        </Document>
                                        </kml>`;
                        return kml;
                    }
                    var kmlContent = generateKML(geometryType, coordinatesWithAltitude);
 
                    // console.log(kmlContent);
 
                  
            var ssDownload = document.createElement('a');
            ssDownload.href = 'data:application/vnd.google-earth.kml+xml;charset=utf-8,' + encodeURIComponent(kmlContent);
            ssDownload.download = 'polygon.kml';
            ssDownload.textContent = 'Download KML';
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            var ssOpenInGoogleEarth = document.createElement('a');
            ssOpenInGoogleEarth.href = 'https://earth.google.com/web/search/' + lat + "," + lng ;
            ssOpenInGoogleEarth.target = '_blank';
            ssOpenInGoogleEarth.textContent = 'Open in Google Earth';
            var ssOpenInGoogleMap = document.createElement('a');
            ssOpenInGoogleMap.href = "https://www.google.com/maps?q=" +  lat + "," + lng;
            ssOpenInGoogleMap.target = '_blank';
            ssOpenInGoogleMap.textContent = 'Open in Google Map';

            // Create a div element to hold the links
            var container = L.DomUtil.create('div');
            container.appendChild(ssDownload);
            container.appendChild(document.createElement('br')); // Add a line break between the links
            container.appendChild(ssOpenInGoogleEarth);
            container.appendChild(document.createElement('br')); // Add a line break between the links
            container.appendChild(ssOpenInGoogleMap);

            // Create a Leaflet popup and set its content to the container
            var popup = L.popup()
            .setLatLng(e.latlng)
            .setContent(container)
            .openOn(map);
                });
            }
     
    });
 
 
           
    // var control = new L.control.layers(baseLayers, WMSlayers).addTo(map);






    // ***************************************************************Draw control***************************************************************
    var polyline = L.polyline([], {
        color: 'red'
    });
    var polygon = L.polygon([], {
        color: 'red'
    });
    var circle = L.circle([], {
        color: 'red'
    });
    var coordinates = [];

    var editableLayers = new L.FeatureGroup(); // add the polyline to the FeatureGroup
    map.addLayer(editableLayers);

    var drawPluginOptions = {
        position: 'topright',
        draw: {
            polygon: {
                allowIntersection: true, // Restricts shapes to simple polygons
                shapeOptions: {
                    dashArray: '2, 5',
                    color: 'red'

                }
            },

            polyline: {
                allowIntersection: true, // Restricts shapes to simple polylines
                shapeOptions: {
                    dashArray: '2, 5',
                    color: 'red'
                }
            },

            circle: {
                allowIntersection: true, // Restricts shapes to simple polylines
                shapeOptions: {
                    dashArray: '2, 5',
                    color: "red",


                }
            },
            // disable toolbar item by setting it to false
            // Turns off this drawing tool
            rectangle: false,
            marker: false,
        },
        edit: {
            featureGroup: editableLayers, //REQUIRED!!
            remove: true
        }
    };

    //****************** */ Initialise the draw control and pass it the FeatureGroup of editable layers*************************
    var drawControl = new L.Control.Draw(drawPluginOptions);
    map.addControl(drawControl);

    map.on('draw:created', function(e) {
        var type = e.layerType;
        var layer = e.layer;

        if (type === 'polyline') {
            // add the drawn polyline to the FeatureGroup
            editableLayers.addLayer(layer);

            // update the coordinates variable
            var latlngs = layer.getLatLngs();
            coordinates = latlngs.map(function(latlng) {
                return [latlng.lat, latlng.lng];
            });
            polyline.setLatLngs(coordinates);
        } else if (type === 'polygon') {
            // add the drawn polygon to the FeatureGroup
            editableLayers.addLayer(layer);

            // update the coordinates variable
            var latlngs = layer.getLatLngs();
            coordinates = latlngs.map(function(latlng) {
                return [latlng.lat, latlng.lng];
            });
            polygon.setLatLngs(coordinates);
        } else if (type === 'circle') {
            // add the drawn polyline to the FeatureGroup
            editableLayers.addLayer(layer);

            // update the coordinates variable
            var latlngs = layer.getLatLngs();
            coordinates = latlngs.map(function(latlng) {
                return [latlng.lat, latlng.lng];
            });
            circle.setLatLngs(coordinates);
        }
    });




    // **********************************************



    // var editableLayers = new L.FeatureGroup();
    // map.addLayer(editableLayers);

    map.on('draw:created', function(e) {
        var type = e.layerType,
            layer = e.layer;

        editableLayers.addLayer(layer);
    });

    var north = L.control({
        position: "bottomleft"
    });
    north.onAdd = function(map) {
        var div = L.DomUtil.create("div", "info legend");
        div.innerHTML = '<img src="./images/North.png" style = "height: 50px; width: 50px;">';
        return div;
    }
    north.addTo(map);

    uri =
        "https://portal.geopulsea.com/geoserver/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&WIDTH=40&HEIGHT=20&LAYER=zone:DP", {
            // namedToggle: false,

        };
    L.wmsLegend(uri);
    //

    // control
    // mouse position

    //******************************************************************Scale***************************************************************

    L.control
        .scale({
            imperial: false,
            maxWidth: 200,
            metric: true,
            position: 'bottomleft',
            updateWhenIdle: false
        })
        .addTo(map);

    //**************************************************line mesure*************************************************************
    L.control
        .polylineMeasure({
            position: "topleft",
            unit: "kilometres",
            showBearings: true,
            clearMeasurementsOnStop: false,
            showClearControl: true,
            showUnitControl: true
        })
        .addTo(map);

    //**********************************************************area measure**********************************************************************
    var measureControl = new L.Control.Measure({
        position: "topleft"
    });
    measureControl.addTo(map);

    $('#btnData2').click(function() {
        SearchMe();
    });

    $('#btnData1').click(function() {
        ClearMe();
    });


    // *****************************************************************Search Button**********************************************************************
    function SearchMe() {
    var array = $('.search').val().split(",");

    const marathiText = /^[\u0900-\u097F\s]*$/;

  

        if (marathiText.test(array[0])) {
            // =============================added for name vise seaech ==========================
            var guts = array.slice(3, array.length).join(",")
            // console.log(guts,"guts",marathiText.test(array[0]))
            var sql_filter1 = "Village_Name_Revenue Like '" + array[1] + "'" + "AND Gut_Number IN (" + guts + ")" +
                "AND Taluka Like '" + array[2] + "'"

                // console.log(sql_filter1,"guts")    
            fitbou(sql_filter1)
            wms_layer12.setParams({
                cql_filter: sql_filter1,
                styles: 'highlight',
            });
            wms_layer12.addTo(map).bringToFront();
        
                // console.log("The text is in Marathi.");
                } 
        else{
            // console.log(array[0],"not matched")
            // =============================added for name vise seaech ==========================
     if (array.length == 1) {
            var sql_filter1 = "Gut_Number Like '" + array[0] + "'"
            fitbou(sql_filter1)
            wms_layer12.setParams({
                cql_filter: sql_filter1,
                styles: 'highlight',
            });
            wms_layer12.addTo(map).bringToFront();
        } else if (array.length == 2) {
            var sql_filter1 = "Village_Name_Revenue Like '" + array[0] + "'" + "AND Taluka Like '" + array[1] + "'"
            fitbou(sql_filter1)
            wms_layer12.setParams({
                cql_filter: sql_filter1,
                styles: 'highlight',
            });
            wms_layer12.addTo(map).bringToFront();
        } else if (array.length >= 3) {
            var guts = array.slice(2, array.length).join(", ")
            var sql_filter1 = "Village_Name_Revenue Like '" + array[0] + "'" + "AND Gut_Number IN (" + guts + ")" +
                "AND Taluka Like '" + array[1] + "'"
                // console.log(sql_filter1)
            fitbou(sql_filter1)
            wms_layer12.setParams({
                cql_filter: sql_filter1,
                styles: 'highlight',
            });
            wms_layer12.addTo(map).bringToFront();
        }
        };
    
    }
    // ------------------------------------------save search history-------
    function sendData() {
        var searchQuery = $('.search').val();
        var data = {
            query: searchQuery,
            username: "<?php echo $_SESSION['username'] ?>",
        };

        var xhr = new XMLHttpRequest();

        //👇 set the PHP page you want to send data to
        xhr.open("POST", "save_search_data.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        //👇 what to do when you receive a response
        xhr.onreadystatechange = function() {
            if (xhr.readyState == XMLHttpRequest.DONE) {
                // alert(xhr.responseText);
            }
        };

        //👇 send the data
        xhr.send(JSON.stringify(data));
    }
    // -------------------------------------------------

    function fitbou(filter) {
        var layer = 'zone:Revenue'
        var urlm = "https://portal.geopulsea.com/geoserver/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=" +
            layer + "&CQL_FILTER=" + filter + "&outputFormat=application/json";
        $.getJSON(urlm, function(data) {
            geojson = L.geoJson(data, {});
            map.fitBounds(geojson.getBounds());
        });
    };

    function ClearMe() {
        map.setView([18.55, 73.85], 10, L.CRS.EPSG3857)
    };





    // ***************************************************************Make QUery***************************************************************

    $("#button").click(function() {
        $("#box form").toggle("slow");
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "https://portal.geopulsea.com/geoserver/zone/wfs?request=getCapabilities",
                dataType: "xml",
                success: function(xml) {
                    var select1 = $('#layer');

                    $(xml).find('FeatureType').each(function() {
                        $(this).find('Name').each(function() {
                            var value = $(this).text();
                            select1.append(
                                "<option class='ddindent' value='" +
                                value + "'>" + value + "</option>");
                        });
                    });
                }
            });
        });
        $(function() {
            $("#layer").change(function() {
                var attributes = document.getElementById("attributes");
                var length = attributes.options.length;
                for (i = length - 1; i >= 0; i--) {
                    attributes.options[i] = null;
                }
                var value_layer1 = $(this).val();
                $(document).ready(function() {
                    $.ajax({
                        type: "GET",
                        url: "https://portal.geopulsea.com/geoserver/wfs?service=WFS&request=DescribeFeatureType&version=1.1.0&typeName=" +
                            value_layer1,
                        dataType: "xml",

                        success: function(xml) {

                            var select2 = $('#attributes');
                            $(xml).find('xsd\\:sequence').each(function() {
                                $(this).find('xsd\\:element').each(
                                    function() {
                                        var value = $(this)
                                            .attr('name');
                                        var type = $(this).attr(
                                            'type');
                                        if (value != 'geom' &&
                                            value != 'the_geom'
                                        ) {
                                            select2.append(
                                                "<option class='ddindent' value='" +
                                                type +
                                                "'>" +
                                                value +
                                                "</option>");
                                        }
                                    });
                            });
                        }
                    })
                });
                document.getElementById("textval").innerHTML = value_layer1;
            })
        });
        $(function() {
            $("#attributes").change(function() {
                var operator = document.getElementById("operator");
                var attributes = $("#layer option:selected").text();
                var length = operator.options.length;
                for (i = length - 1; i >= 0; i--) {
                    operator.options[i] = null;
                }
                var value_type = $(this).val();
                var value_attribute = $('#attributes option:selected').text();
                operator.options[0] = new Option('Select operator', "");
                if (value_type == 'xsd:short' || value_type == 'xsd:int' || value_type ==
                    'xsd:double') {
                    var operator1 = document.getElementById("operator");
                    operator1.options[1] = new Option('>', '>');
                    operator1.options[2] = new Option('<', '<');
                    operator1.options[3] = new Option('=', '=');
                    operator1.options[4] = new Option('<=', '<=');
                    operator1.options[5] = new Option('=>', '=>');
                    operator1.options[6] = new Option('IN ()', 'IN');
                    operator1.options[7] = new Option('OR ||', 'OR');
                    operator1.options[8] = new Option('AND &', 'AND');
                } else if (value_type == 'xsd:string') {
                    var operator1 = document.getElementById("operator");
                    operator1.options[1] = new Option('Like', 'ILike');
                    operator1.options[2] = new Option('IN ()', 'IN');
                    operator1.options[3] = new Option('OR ||', 'OR');
                    operator1.options[4] = new Option('AND &', 'AND');
                }


                var selectvalue = document.getElementById("selectvalue");
                var length = selectvalue.options.length;
                for (i = length - 1; i >= 0; i--) {
                    selectvalue.options[i] = null;
                }

                $(document).ready(function() {
                    $.ajax({
                        type: "GET",
                        url: "https://portal.geopulsea.com/geoserver/wfs?service=wfs&version=1.0.0&request=getfeature&typename=" +
                            attributes + "&PROPERTYNAME=" + value_attribute,
                        dataType: "xml",
                        success: function(xml) {
                            var select3 = $('#selectvalue');
                            var unq = new Array();
                            $(xml).each(function() {
                                $(this).find('gml\\:featureMember')
                                    .each(function() {
                                        unq.push($(this)
                                            .text());
                                    });
                                let unique = unq.filter((item, i,
                                    ar) => ar.indexOf(
                                    item) === i);
                                for (let i = 0; i < unique
                                    .length; i++) {
                                    select3.append(
                                        "<option class='ddindent' value='" +
                                        unique[i] + "'>" +
                                        unique[i] + "</option>");
                                }
                            });
                        }
                    });
                });
                document.getElementById("textval").innerHTML = "From Layer" + attributes +
                    " is " + value_attribute;
            });

        });
    });



    $(function() {
        $("#selectvalue").change(function() {
            var vars = ['layer', 'attributes', 'operator', 'selectvalue'];
            for (let i = 0; i < vars.length; i++) {
                //   var operator = document.getElementById("operator");
                var layer = $("#layer option:selected").text();
                var attributes = $("#attributes option:selected").text();
                var operator = $("#operator option:selected").text();
                var selectvalue = $("#selectvalue option:selected").text();
            }
            document.getElementById("textval").innerHTML = "From Layer " + layer + " column is " +
                attributes + " " + operator + " value is " + selectvalue;

            var sql_filter1 = attributes + " Like '" + selectvalue + "'"
            // console.log(sql_filter1)
            fitbou(sql_filter1, layer)

            var wms_layerf = L.tileLayer.wms(
                "https://portal.geopulsea.com/geoserver/zone/wms", {
                    layers: layer,
                    format: "image/png",
                    transparent: true,
                    tiled: true,
                    version: "1.1.0",
                    attribution: "ugc",
                    opacity: 1,
                    cql_filter: sql_filter1,
                    styles: 'highlight',

                }
            );
            wms_layerf.addTo(map);


            function fitbou(filter, layer1) {
                var urlm =
                    "https://portal.geopulsea.com/geoserver/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=" +
                    layer1 + "&CQL_FILTER=" + filter + "&outputFormat=application/json";
                // console.log(urlm)
                $.getJSON(urlm, function(data) {
                    geojson = L.geoJson(data, {});
                    map.fitBounds(geojson.getBounds());
                });
            };


        });
    })






    // ***************************************************pdf*********************************************************

    function takeScreenshot() {
        html2canvas(document.getElementById('map'), {
            useCORS: true
        }).then(function(canvas) {
            var imgData = canvas.toDataURL('image/png');

            var pdf = new jsPDF();
            pdf.addImage(imgData, 'PNG', 15, 25, 180, 135); //x,y , width, height

            // Get the height of the canvas element and add it to the PDF
            var imgHeight = canvas.height;

            // Add the local image to the PDF
            var img = new Image();
            img.onload = function() {
                pdf.addImage(img, 'PNG', 15, 170, 180, 80);
                pdf.save('map.pdf');
            };
            img.src = 'finalLegend.png';
        });
    }




    const $button = document.querySelector('#sidebar-toggle');
    const $wrapper = document.querySelector('#wrapper');

    $button.addEventListener('click', (e) => {
        e.preventDefault();
        $wrapper.classList.toggle('toggled');
    });

    function toggleSidebar() {
        var sidebar = document.getElementById("sidebar-wrapper");
        var openIcon = document.getElementById("sidebar-open-icon");
        var closeIcon = document.getElementById("sidebar-close-icon");

        if (sidebar.classList.contains("toggled")) {
            // Sidebar is open, so close it
            sidebar.classList.remove("toggled");
            openIcon.classList.remove("d-none");
            closeIcon.classList.add("d-none");
        } else {
            // Sidebar is closed, so open it
            sidebar.classList.add("toggled");
            openIcon.classList.add("d-none");
            closeIcon.classList.remove("d-none");
        }
    }
   

    // --------------------------------------bookmark updated code
    $('#saveBtn').on('click', function() {
  var userN = "<?php echo $_SESSION['username'] ?>";
  
  // Get the center coordinates of the map
  var mapCenter = map.getCenter();
  var latitude = mapCenter.lat;
  var longitude = mapCenter.lng;

  // Show a SweetAlert dialog with an input field for the location name
  Swal.fire({
    title: 'Save Location',
    html: '<input id="locationName" class="swal2-input" placeholder="Enter location name">',
    showCancelButton: true,
    confirmButtonText: 'Save',
    preConfirm: function() {
      var name = Swal.getPopup().querySelector('#locationName').value;
      return name;
    },
    customClass: {
      popup: 'my-custom-class',
      title: 'my-title-class'
    }
  }).then(function(result) {
    var name = result.value;
    
    if (name) {
      $.ajax({
        type: 'POST',
        url: 'bkmrk/save_location.php',
        data: {
          userN: userN,
          lat: latitude,
          lng: longitude,
          name: name
        },
        success: function(response) {
          Swal.fire({
            title: 'Location saved successfully.',
            icon: 'success',
            customClass: {
              popup: 'my-success-popup-class',
              title: 'my-title-class'
            }
          });
          // Reload table
          loadLocationTable();
        },
        error: function(xhr, status, error) {
        //   console.log(xhr.responseText);
          Swal.fire({
            title: 'An error occurred while saving the location.',
            customClass: {
              popup: 'my-success-popup-class',
              title: 'my-title-class'
            }
        });
        }
      });
    }
  });
});



    function loadLocationTable() {
        var userN = "<?php echo $_SESSION['username'] ?>";
        $.ajax({
            type: 'GET',
            url: 'bkmrk/get_locations.php',
            dataType: 'json',
            success: function(response) {
                var locations = response;
                var tableBody = $('#locationTable tbody');
                tableBody.empty();

                for (var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    var row = '<tr>' +
                        '<td><a href="#" onclick="zoomToLocation(' + location.latitude + ', ' + location
                        .longitude + ')">' + location.name + '</a></td>' +
                        '<td><button class="deleteBtn" data-id="' + location.id +
                        '"><i class="fas fa-trash-alt"></i></button></td>' +
                        '</tr>';
                    tableBody.append(row);
                }
            },
            error: function(xhr, status, error) {
                // console.log(xhr.responseText);
                alert('An error occurred while retrieving locations.');
            }
        });
    }

    $(document).on('click', '.deleteBtn', function() {
  var locationId = $(this).data('id');

  // Show a confirmation dialog before deleting
  Swal.fire({
    title: 'Delete Bookmark',
    text: 'Are you sure you want to delete this bookmark?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Delete',
    cancelButtonText: 'Cancel',
    customClass: {
      popup: 'my-custom-class',
      title: 'my-title-class'
    }
  }).then(function(result) {
    if (result.isConfirmed) {
      // User confirmed the deletion, proceed with AJAX request
      $.ajax({
        type: 'POST',
        url: 'bkmrk/delete_location.php',
        data: {
          id: locationId
        },
        success: function(response) {
          Swal.fire({
            title: 'Bookmark deleted successfully.',
            position: 'center',
            icon: 'success',
            customClass: {
              popup: 'my-custom-class1',
              title: 'my-title-class'
            }
          });
          // Reload table
          loadLocationTable();
        },
        error: function(xhr, status, error) {
        //   console.log(xhr.responseText);
          Swal.fire({
            title: 'An error occurred while deleting the location.',
            position: 'center',
            icon: 'error',
            customClass: {
              popup: 'my-success-popup-class1',
              title: 'my-title-class'
            }
          });
        }
      });
    }
  });
});



    function zoomToLocation(latitude, longitude) {
        map.flyTo([latitude, longitude], 15);
    }
    // Load table on page load
    loadLocationTable();

    </script>

</body>

</html>
