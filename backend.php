<?php
error_log("PHP script triggered at " . date("Y-m-d H:i:s"));


    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';
    require 'phpmailer/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: POST");

    $data = json_decode(file_get_contents("php://input"), true);
    // file_put_contents("php_log.txt", print_r($data, true), FILE_APPEND);

    // $uid = uniqid("form_", true);
    // file_put_contents("php_log.txt", "[$uid] DATA: " . print_r($data, true), FILE_APPEND);


    // $name = $data["name"];
    // $email = $data["email"];
    // $message = $data["message"];

    $name = htmlspecialchars(trim($data["name"]));
    // $email = filter_var(trim($data["email"]), FILTER_SANITIZE_EMAIL);
    $email = filter_var(trim($data["email"]), FILTER_SANITIZE_EMAIL);
    // $email = $data['email'];
    // echo "User Email: " . $email;
    $message = htmlspecialchars(trim($data["message"]));

    $response = [
        "status" => "success",
        "message" => "Hello $name, we got your message!"
    ];

    header("Content-Type: application/json");
    echo json_encode($response);

    $mail = new PHPMailer(true);

    try{
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'portfolioconnect7@gmail.com';
        $mail->Password ='bvri vajt uzyp msam';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('portfolioconnect7@gmail.com', 'Md Arif Khan');
        $mail->addAddress('portfolioconnect7@gmail.com', 'Arif Khan');

        $mail->isHTML(true);
        $mail->Subject = "New Message from Portfolio!!!";
        $mail->Body = "Name: $name<br>Email: $email<br><br>Message:<br>$message";

        $mail->send();

        $replyMail = new PHPMailer(true);

        try{
          $replyMail->isSMTP();
          $replyMail->Host = 'smtp.gmail.com';
          $replyMail->SMTPAuth = true;
          $replyMail->Username = 'portfolioconnect7@gmail.com';
          $replyMail->Password ='bvri vajt uzyp msam'; // App password
          $replyMail->SMTPSecure = 'tls';
          $replyMail->Port = 587;

          $replyMail->setFrom('portfolioconnect7@gmail.com', 'Md Arif Khan');
          $replyMail->addAddress($email, $name);
          // echo "User Email: " . $email;

          $replyMail->isHTML(true);
          $replyMail->Subject = "We've received your message!";
          $replyMail->Body = "
  <div style='font-family: Arial, sans-serif; background:#f4f7fb; padding:30px;'>
      <div style='max-width:650px; margin:0 auto; background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.1);'>

          <!-- Header -->
          <div style='background:#007bff; padding:20px; text-align:center; color:#fff;'>
              <h2 style='margin:0; font-size:24px;'>Md Arif Khan</h2>
              <p style='margin:0; font-size:14px;'>Full-Stack Developer | Portfolio Contact</p>
          </div>

          <!-- Body -->
          <div style='padding:25px; color:#333; line-height:1.7; font-size:15px;'>
              <p>Hi <b>$name</b>,</p>
              
              <p>Thank you for reaching out through my portfolio website! 🙌<br>
              I really appreciate you taking the time to contact me.</p>
              
              <p>This is a quick confirmation that I have received your message. 
              I usually reply within <b>24–48 hours</b>. Please know that your query is important 
              to me, and I will respond as soon as possible.</p>
              
              <!-- Message Card -->
              <div style='background:#f9f9f9; border-left:5px solid #007bff; padding:15px; margin:20px 0; border-radius:6px;'>
                  <p style='margin:0;'><b>Your message details:</b></p>
                  <p style='margin:8px 0;'><b>Name:</b> $name</p>
                  <p style='margin:8px 0;'><b>Email:</b> $email</p>
                  <p style='margin:8px 0;'><b>Message:</b><br>$message</p>
              </div>

              <p>If your matter is urgent, you can reply directly to this email 
              and I will prioritize your response.</p>

              <p>Meanwhile, feel free to explore my portfolio and connect with me:</p>
              <p>
                  🔗 <a href='https://arif-drab.vercel.app' style='color:#007bff; text-decoration:none;'>Visit My Portfolio</a><br>
                  💼 <a href='https://www.linkedin.com/in/md-arif-khan-b002742aa' style='color:#007bff; text-decoration:none;'>LinkedIn</a><br>
                  💻 <a href='https://github.com/Arifkhan2007111' style='color:#007bff; text-decoration:none;'>GitHub</a>
              </p>

              <p style='margin-top:25px;'>Best regards,<br>
              <b>Md Arif Khan</b><br>
              Full-Stack Developer</p>
          </div>

          <!-- Footer -->
          <div style='background:#f1f1f1; padding:15px; text-align:center; font-size:12px; color:#777;'>
              This is an automated confirmation email. Please do not consider it a final response.<br>
              &copy; ".date('Y')." Md Arif Khan Portfolio
          </div>

      </div>
  </div>
";

          $replyMail->send();
        }catch (Exception $e) {
          echo "<br>Auto-reply email failed: " . $replyMail->ErrorInfo;
        }

    } catch(Exception $e){
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
        ]);
        exit;
    }

?>