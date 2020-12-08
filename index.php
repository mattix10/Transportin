
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="Transportin - przeprowadzki i transport" />
	  <meta name="keywords" content="przeprowadzki, przeprowadzki Brodnica, szybka przeprowadzka, transport mebli, przewóz rzeczy,wywóz mebli, wywóz złomu, oczyszczanie posesji,dobra firma przeprowadzkowa, zabezpieczenie mebli">
    <title>Transportin</title>

    <link rel="stylesheet" href="./css/style.min.css" rel=preload>
    <link rel="stylesheet" href="./icons/font-awesome-4.7.0/css/font-awesome.min.css" rel=preload>
    <link rel="stylesheet" href="./css/bootstrap2.min.css">
    <link rel="stylesheet" href="./css/font.css">
    <script src="./javascript/fontawesomekit.js"></script>
</head>
<body>

<?php
include 'phpmailer/PHPMailerAutoload.php';
error_reporting(E_ERROR | E_PARSE);
$emailErr = $fromCityErr = $toCityErr = "";
$assemblyErr = $helpPackErr = $protectionErr = "";
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if(empty($_POST["fromCity"])) {
    $fromCityErr = "Miejscowość jest wymagana";
  } else {
    $fromCity = test_input($_POST["fromCity"]);
  }

  if(empty($_POST["toCity"])) {
    $toCityErr = "Miejscowość jest wymagana";
  } else {
    $toCity = test_input($_POST["toCity"]);
  }

  if(empty($_POST["helpPack"])) {
    $helpPackErr = "To pole jest wymagane";
  } else {
    $helpPack = test_input($_POST["helpPack"]);
  }

  if(empty($_POST["assembly"])) {
    $assemblyErr = "To pole jest wymagane";
  } else {
    $assembly = test_input($_POST["assembly"]);
  }

  if(empty($_POST["protection"])) {
    $protectionErr = "To pole jest wymagane";
  } else {
    $protection = test_input($_POST["protection"]);
  }

  if (empty($_POST["email"])) {
    $emailErr = "Adres e-mail jest wymagany";
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Nieprawidłowy adres e-mail";
    }
  }

  $howManyFloor = test_input($_POST["howManyFloor"]);
  $handicap = test_input($_POST["handicap"]);
  $howManyFurniture = test_input($_POST["howManyFurniture"]);
  $whichFurniture = test_input($_POST["whichFurniture"]);
  $annotation = test_input($_POST["annotation"]);
  if ($emailErr == '' && $fromCityErr == '' && $toCityErr == '' && $assemblyErr == '' && $helpPackErr == '' && $protectionErr == '') {
    $message = sendMail($email, $fromCity, $toCity, $helpPack, $howManyFloor, $handicap, $assembly, $protection, $howManyFurniture, $whichFurniture, $annotation);  
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function sendMail($email, $fromCity, $toCity, $helpPack, $howManyFloor, $handicap, $assembly, $protection, $howManyFurniture, $whichFurniture, $annotation){
  $mail = new PHPMailer;
  $mail->setFrom($email, 'Klient');
  $mail->addAddress('transportin.hubert.sarnowski@gmail.com', 'Transportin');
  $mail->Subject  = 'Formularz kontaktowy';
  $mail->isHTML(true);  
  $mail->Body = '
    <p>Adres email: '.$email.'</p>
    <p>Miejscowość (skąd): '.$fromCity.'</p>
    <p>Miejscowość (dokąd): '.$toCity.'</p>
    <p>Czy ma być pomoc przy załadunku i rozładunku: '.$helpPack.'</p>
    <p>Jeśli tak, to ile pięter: '.$howManyFloor.'</p>
    <p>Dodatkowe utrudnienia: '.$handicap.'</p>
    <p>Czy będzie potrzebny demontaż i montaż mebli: '.$assembly.'</p>
    <p>Czy będzie potrzebne dodatkowe zabezpieczenie mebli: '.$protection.'</p>
    <p>Ile mebli: '.$howManyFurniture.'</p>
    <p>Jakie meble: '.$whichFurniture.'</p>
    <p>Dodatkowe uwagi: '.$annotation.'</p>';
  
  $msg = '';
  if (array_key_exists('userfile', $_FILES)) {
    for ($ct = 0, $ctMax = count($_FILES['userfile']['tmp_name']); $ct < $ctMax; $ct++) {
      $extens = pathinfo($_FILES['userfile']['name'][$ct], PATHINFO_EXTENSION);
  
      if ($extens == "png" || $extens =="jpg" || $extens =="jpeg") {
        $ext = PHPMailer::mb_pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
        $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['userfile']['name'][$ct])) . '.' . $ext;
        $filename = $_FILES['userfile']['name'][$ct];
        if (move_uploaded_file($_FILES['userfile']['tmp_name'][$ct], $uploadfile)) {
          if (!$mail->addAttachment($uploadfile, $filename)) {
              $msg .= 'Nie udało się dołączyć pliku ' . $filename;
          }
        } 
      } else {
        $msg .= 'Nie udało się przenieść pliku do' . $uploadfile;
      }
    }
  }
  
  if (!$mail->send()) {
    $message = '<p class="text-danger">Formularz nie został wysłany.</p>';
    return $message;
  
  } else {
    $message = '<p class="text-success">Formularz został wysłany pomyślnie.</p>';
    return $message;
  }
}

?>
  <header>
    <nav class="navbar fixed-top bg-orange">
      <div class="container">
        
        <div class="d-flex justify-content-between">
          <a href="#">
            <img class="height-60" src="./images/logo2_min.png" alt="logo">
          </a>
          <a class="toggle-button d-sm-block d-md-none d-lg-none d-xl-none">
            <i class="fa fa-bars "></i>
          </a>
        </div>
        
        <div class="navbar-links d-lg-block">
          <ul class="d-flex my-2 list-unstyled p-0">
            <li><a href="#about" class="d-block text-decoration-none  px-3 py-2">O NAS</a></li>
            <li><a href="#offer"  class="d-block text-decoration-none  px-3 py-2">OFERTA</a></li>
            <li><a href="#form"  class="d-block text-decoration-none  px-3 py-2">FORMULARZ</a></li>
            <li><a href="#contact"  class="d-block text-decoration-none px-3 py-2">KONTAKT</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

    <main>
      <article>
        <section>
          <div class="start-image mt-60 ">
          </div>
             <div class="start-title-wrapper">
              <div class="start-title mb-2"> TRANSPORTIN</div>
              <div class="start-subtitle pb-2">Eksperci od przeprowadzek</div>
            </div>
        </section>
        
        <section>

          <div class="container-xl">
            <div class="container mb-5 mt-3">

              <div class="row mt-5" id="about">
                <div class="col-lg-6 px-3 mt-5 order-2 order-lg-1">
                  <div class="max-width-500 mx-auto my-auto ">
                    <img class="w-100" src="./images/oferta_lewo_2_min.jpg">
                  </div>
                </div>
                <div class=" col-lg-6 order-1 order-lg-2">
                  <div class="d-flex justify-content-center align-items-center my-5">
                    <div class="bottom-orange mx-auto mb-2 mt-4">
                      <h1 class="font-bolder text-brown font-big mt-4">O NAS</h1>
                    </div>
                  </div>
                  <p class="text-brown-2 mb-5 text-justify mx-sm-4 px-3">
                    Jesteśmy młodą firmą specjalizują się w usługach transportowych - przeprowadzkach oraz wywożeniu niepotrzebnych rzeczy. Działamy szybko, tanio i wygodnie.  Pracujemy również w weekendy.
                  </p>
                </div>
              </div>
            
            </div>
          </div>

          <div class=" container-xl bg-light-grey ">  
            <div class="container ">

              <div class=" row mx-0 my-5" id="offer">
                <div class="col-lg-6  order-1 order-lg-2 my-4s  ">
                  <div class="d-flex justify-content-center align-items-center my-5">
                    <div class="bottom-orange mx-auto mb-3 mt-4">
                      <h1 class="font-bolder text-brown mb-2 mt-xs-5 font-big mt-4 mt-lg-0">OFERTA</h1>
                    </div>
                  </div>
                  <p class="text-brown-2 mb-5  mx-sm-4 px-3 text-justify">
                    Oferujemy transport i przeprowadzki lokalne, krajowe i międzynarodowe. Oczyszczamy mieszkania, posesje i ogrody ze starych mebli oraz złomu. Posiadamy dużego busa z kontenerem oraz windą. Oferujemy również zabezpieczenie Państwa mebli - posiadamy wszystkie potrzebne do tego środki (pasy, liny, kartony, stretch, koce).Towar przewożony ubezpieczony jest na kwotę 10 000 € (43 000 zł).               
                  </p>              
                </div>

                <div class="col-lg-6 px-3 order-1 order-lg-2 my-6">
                  <div class="max-width-500 mx-auto">
                    <img class="w-100" src="./images/oferta_prawo_min.jpg">
                  </div>
                </div>
              </div>
      
            </div> 
          </div> 
            
          <div class="container ">
            <div class="row my-5">
                <div class="bottom-orange mx-auto my-4">
                  <h1 class="font-bolder text-brown mt-5 font-big">SPRAWDŹ NAS</h1>
                </div>
            </div>

              <div class="row ">

                <div class="col my-3">
                  <div class=" height-50 d-flex justify-content-center align-items-center">
                    <i class="fas fa-map-marker-alt fa-2x"></i>
                  </div>
                  <div class="text-brown-2 text-center font-bold font-medium mt-1">
                    MOBILNOŚĆ
                  </div>
                  <div class="row d-flex align-items-center justify-content-center bg-light-grey height-200 text-center mt-3 px-4 py-3 mx-4">
                    Oferujemy przewozy rzeczy krajowe i zagraniczne.
                  </div>
                </div>

                <div class="col my-3">
                  <div class="height-50 d-flex justify-content-center align-items-center">
                    <i class="fas fa-shipping-fast fa-2x"></i>
                  </div>
                  <div class="text-brown-2 text-center font-bold font-medium mt-1">
                    SZYBKOŚĆ
                  </div>
                  <div class="row d-flex align-items-center justify-content-center bg-light-grey height-200 text-center mt-3 mx-4 px-4 py-3">
                    Nasze przeprowadzki są zorganizowane i przebiegają sprawnie.
                  </div>
                </div>

                <div class="col my-3">
                  <div class="height-50 d-flex justify-content-center align-items-center">
                    <i class="fas fa-dolly fa-2x"></i>
                  </div>
                  <div class="text-brown-2 text-center font-bold font-medium mt-1">
                    BEZPIECZEŃSTWO
                  </div>
                  <div class="row d-flex align-items-center justify-content-center bg-light-grey height-200 text-center mt-3 mx-3 px-4 py-3">
                    Wiemy, jak ważne są dla Państwa meble, dlatego dbamy, aby bezpiecznie dotarły do celu.
                  </div>
                </div>

                <div class="col my-3">
                  <div class="height-50 d-flex justify-content-center align-items-center">
                    <i class="fas fa-couch fa-2x"></i>
                  </div>
                    <div class="text-brown-2 text-center font-bold font-medium mt-1">
                      KOMFORT
                    </div>
                  <div class="row d-flex align-items-center justify-content-center bg-light-grey height-200 text-center mt-3 mx-3 px-4 py-3">
                    Robimy wszystko, by nasi klienci mogli komfortowo przenieść się do nowego lokum. 
                  </div>
                </div>

              </div>   
          </div>

              <div class="container">

                <div class="row mt-5">
                  <div class="bottom-orange mx-auto my-5">
                    <h1 class="font-bolder text-brown font-big">KALENDARZ</h1>
                  </div>
                </div> 
                <div class="row">
                  <h2 class=" text-center font-medium font-small w-100 text-brown mx-3 mb-5">
                    Sprawdź wolne terminy i umów się z nami na przeprowadzkę lub wywóz rzeczy.
                  </h2>
                </div>
                <div class="row">
                    <iframe src="https://calendar.google.com/calendar/embed?src=transportin.hubert.sarnowski%40gmail.com&ctz=Europe%2FWarsaw" class=" col-lg-6 my-3 mx-auto height-600" frameborder="0" scrolling="no"></iframe>
                </div>
              </div>

              <div id="form" class="container">

                <div  class="row">
                  <div class="bottom-orange mx-auto my-5">
                    <h1 class="font-bolder text-brown mb-2 mt-5 font-big">FORMULARZ</h1>
                  </div>
                </div> 
                
                <div class="row">
                  <p class=" text-center font-medium font-small w-100 text-brown mb-2 mx-2 ">
                    Masz pytania lub wątpliwości? Napisz do nas - chętnie Ci pomożemy :)
                  </p>
                </div>
                
                <div class="row">
                  <div class="col">

                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="mx-auto w-60 w-small-80 my-5" id="contact_form" enctype="multipart/form-data">

                      <div class="row">
                        <label for="fromCity"></label>
                        <input type="text" class="border-orange w-100 p-2 mt-3" id="fromCity" name="fromCity" placeholder="Skąd (miejscowość)" >
                        <span class="pt-1 text-danger"><?php echo $fromCityErr;?></span>
                      </div>
                      
                      <div class="row">
                        <label for="destinationCity"></label>
                        <input type="text" class="border-orange w-100 p-2 mt-4" id="toCity" name="toCity" placeholder="Dokąd (miejscowość)" >
                        <span class="pt-1 text-danger"><?php echo $toCityErr;?></span>
                      </div>
                      
                      <div class="row mt-4">
                        <p class="mx-2">Czy ma być pomoc przy załadunku i rozładunku?</p>
                        <span class=" text-danger"><?php echo $helpPackErr;?></span>
                        <div class="col-12">
                          <div class="form-check my-2 ">
                            <input class="form-check-input mr-1" type="radio" name="helpPack" id="helpPackNo" value="Nie">
                            <label class="form-check-label" for="helpPackNo">Nie</label>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="form-check my-2 ">
                            <input class="form-check-input mr-1" type="radio" name="helpPack" id="helpPackYes" value="Tak" >
                            <label class="form-check-label" for="helpPackYes">Tak</label>
                          </div>
                        </div>
                      </div>
                      
                      <div class="row">
                        <input type="number" min="0" class="border-orange w-100 p-2 mt-4" name="howManyFloor" placeholder="Jeśli tak, ile pięter przy załadunku?">
                      </div>
                      
                      <div class="row">
                        <input type="text"  class="border-orange w-100 p-2 mt-4" name="handicap" placeholder="Dodatkowe utrudnienia">
                      </div>
                      
                      <div class="row mt-4">
                        <p class="mx-2">Czy będzie potrzebny demontaż i montaż mebli?</p>
                        <span class=" text-danger"><?php echo $assemblyErr;?></span>
                        <div class="col-12">
                          <div class="form-check my-2 ">
                            <input class="form-check-input mr-1" type="radio" name="assembly" id="assemblyNot" value="Nie">
                            <label class="form-check-label" for="assemblyNot">Nie</label>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="form-check my-2">
                            <input class="form-check-input mr-3" type="radio" name="assembly" id="assemblyYes" value="Tak" >
                            <label class="form-check-label" for="assemblyYes">Tak</label>
                          </div>
                        </div>
                      </div>

                      <div class="row mt-4">
                        <p class="mx-2">Czy będzie potrzebne dodatkowe zabezpieczenie mebli (folia bąbelkowa, stretch, kartony, koce)?</p>
                        <span class=" text-danger mb-1"><?php echo $protectionErr;?></span>
                        <div class="col-12">
                          <div class="form-check my-2 ">
                            <input class="form-check-input mr-1" type="radio" name="protection" id="protectionNot" value="Nie">
                            <label class="form-check-label" for="protectionYes">Nie</label>
                          </div>
                        </div>

                        <div class="col-12">
                          <div class="form-check my-2">
                            <input class="form-check-input mr-3" type="radio" name="protection" id="Tak" value="Tak">
                            <label class="form-check-label" for="Tak">Tak</label>
                          </div>
                        </div>
                      </div>
                      
                      <div class="row">
                        <input type="number" min="0" class="border-orange w-100 p-2 mt-4" name="howManyFurniture" placeholder="Ile mebli?">
                      </div>
                      
                      <div class="row">
                        <input type="text" class="border-orange w-100 p-2 mt-4" name="whichFurniture" placeholder="Jakie meble?">
                      </div>
                      
                      <div class="row">
                        <textarea rows="4" class="border-orange w-100 p-2 mt-4" name="annotation" placeholder="Dodatkowe uwagi"></textarea>
                      </div>

                      <div class="row" id="imageInputs">

                      </div>

                      <div class="row my-4">
                        <div class="col d-flex align-items-center justify-content-center">
                          <button role="button" onclick="addInputs()" class="btn text-center text-brown px-5 py-2 bg-light-grey" id="addInput">Dodaj zdjęcie</button>
                        </div>
                      </div>

                      <div class="row">
                        <input type="text" class="border-orange w-100 p-2 mt-4" name="email" value="<?php if($message == '') echo $email;?>" placeholder="Adres e-mail do kontaktu" >
                        <span class="pt-1 text-danger"><?php echo $emailErr;?></span>
                      </div>

                      

                      <div class="row my-4">
                        <div class="col d-flex align-items-center justify-content-center">
                          <button role="button" type="submit" class="btn text-center text-white px-5 py-2 bg-orange">Wyślij</button>
                        </div>
                      </div>

                      <div class="row">
                        <div id="send_form_status" class="text-center mx-auto">
                          <?php   echo $message ?>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

          <div class="container">
            
            <div class="row ">
              <div class="mt-5 mx-auto text-brown font-bolder text-brown font-medium ">
                NIE UFAJ NAM!
              </div>
            </div>  
            
            <div class="row">
              <div class="bottom-orange mx-lg-auto mx-xl-auto mx-md-auto mx-5 ">
                <h1 class="font-bolder text-brown text-center font-big">ZAUFAJ NASZYM KLIENTOM! ;)</h1>
              </div>
            </div> 

            <div class="row mt-4 mb-5 pb-5">
              <div class="col mt-5">
                <div class="row d-flex align-items-center justify-content-center bg-light-grey min-height-230 h-auto mt-3 px-4 py-3 mx-4 font-italic">
                  <i class="fas fa-quote-right quote-left"></i>
                  <p class="px-3">Wszystkie moje oczekiwania wobec świadczonej usługi zostały spełnione, polecam. </p>                 
                  <i class="fas fa-quote-right quote-right"></i>
                </div>
                <div class="triangle"></div>
                <div class=" height-50 d-flex justify-content-center align-items-center">
                  <i class="fa fa-user fa-2x"></i>
                </div>
              </div>

              <div class="col mt-5">
                <div class="row d-flex align-items-center justify-content-center bg-light-grey min-height-230 h-auto mt-3 mx-4 px-4 py-3 font-italic">
                  <i class="fas fa-quote-right quote-left"></i>
                  <p class="px-3">Konkretna firma godna polecenia, jeśli będzie potrzeba na pewno ponownie skorzystam z usług.</p>
                  <i class="fas fa-quote-right quote-right"></i>
                </div>
                <div class="triangle"></div>
                <div class="height-50 d-flex justify-content-center align-items-center">
                  <i class="fa fa-user fa-2x"></i>
                </div>
              </div>

              <div class="col mt-5">
                <div class="row d-flex align-items-center justify-content-center bg-light-grey min-height-230 h-auto mt-3 mx-4 px-4 py-3 font-italic">
                  <i class="fas fa-quote-right quote-left"></i>
                  <p class="px-3">Wszystko super i sprawnie przebiegło. Miły kontakt. Pełen profesjonalizm. Gorąco polecam.</p>
                  <i class="fas fa-quote-right quote-right"></i>
                </div>
                <div class="triangle"></div>
                <div class="height-50 d-flex justify-content-center align-items-center">
                  <i class="fa fa-user fa-2x"></i>
                </div>
              
              </div>
            </div>  
          </div>
        
        </section>
      </article>
    </main>
    
    <footer>
      <div class="bg-orange pb-5 pt-3 mx-0">
        <div class="container" id="contact">
        
          <div class="row  text-white">
            
            <div class="col-lg-4 my-3">
              <div class="row">
                <div class="col mb-4">
                  <h5 class=" text-center ">
                    Kontakt
                  </h5>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col text-center">
                  <i class="fa fa-envelope fa-2x mb-2" aria-hidden="true"></i>
                    <p>transportin.hubert.sarnowski@gmail.com</p>       
                </div>
              </div>
              <div class="row mt-3">
                <div class="col text-center">
                  <i class="fas fa-phone-alt fa-2x mb-2"></i>
                  <p>534 219 944</p>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col py-2 text-center">
                  <i class="fa fa-map-marker fa-2x mb-2 " aria-hidden="true"></i>
                  <p class="my-0">87-300 Brodnica</p>
                  <p class="my-0">ul. Mieszka I 9/10</p>
                </div>
              </div>
            </div>

            <div class="col-lg-4 my-3 text-center">
              <h5 class="mb-4">
                Znajdź nas na mapie...
              </h5>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2426433.729996517!2d17.50967712150845!3d53.56554680948411!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471d1f98577d2145%3A0xb1b838c67633f2c2!2sTRANSPORTIN%20-%20Hubert%20Sarnowski!5e0!3m2!1spl!2s!4v1604855119138!5m2!1spl!2s" class="w-100" height="350" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
          
            <div class="col-lg-4 my-3 text-center">
              <h5 class="mb-4">
                ... oraz na facebooku
              </h5>
            <div class="fb-page w-100" 
                 data-tabs="timeline,events,messages"
                 data-href="https://www.facebook.com/Transportin-transport-i-przeprowadzki-111197194053498/"
                 data-width="300"
                 data-height="350"         	
                 data-lazy="true">
            </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  
    <div id="fb-root"></div>
    <script src="./javascript/addPhotos.js"></script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v8.0" nonce="5kRHNPHZ"></script>
    <script src="./javascript/navbar.js"></script>

</body>
</html>