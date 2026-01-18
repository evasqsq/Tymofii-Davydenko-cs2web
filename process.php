<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    

    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    $interests = $_POST['interests'] ?? [];
    $agreement = $_POST['agreement'] ?? '';
    

    $success = '';
    $error = '';
    

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Vyplňte všetky povinné polia!";
    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email nie je platný!";
    }
 
    elseif (empty($agreement)) {
        $error = "Musíte súhlasiť so spracovaním osobných údajov!";
    }

    else {

        $to = 'cs2.major@example.com';
        
  
        $headers = "From: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "Reply-To: $email\r\n";
        

        $body = "=== NOVÁ SPRÁVA Z WEBOVEJ STRÁNKY ===\n\n";
        $body .= "OSOBNÉ INFORMÁCIE:\n";
        $body .= "Meno: $name\n";
        $body .= "Email: $email\n\n";
        $body .= "OBSAH SPRÁVY:\n";
        $body .= "Téma: $subject\n\n";
        $body .= "Správa:\n";
        $body .= "$message\n\n";
        

        if (!empty($interests)) {
            $body .= "ZÁUJMY UŽÍVATEĽA:\n";
            $body .= implode(", ", $interests) . "\n\n";
        }
        
        $body .= "=== KONIEC SPRÁVY ===\n";
        $body .= "Čas odoslania: " . date('Y-m-d H:i:s') . "\n";


        if (mail($to, "Nova sprava: " . $subject, $body, $headers)) {
            $success = "Správa bola úspešne odoslaná! Ďakujeme za váš kontakt. Odpovieme vám do 24-48 hodín.";
            

            $confirmation_headers = "From: cs2.major@example.com\r\n";
            $confirmation_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            $confirmation_body = "Dobrý deň $name,\n\n";
            $confirmation_body .= "Ďakujeme vám za vašu správu! Vaša správa bola úspešne prijatá.\n\n";
            $confirmation_body .= "Detaily vašej správy:\n";
            $confirmation_body .= "Téma: $subject\n";
            $confirmation_body .= "Čas prijatia: " . date('Y-m-d H:i:s') . "\n\n";
            $confirmation_body .= "Odpovídame väčšinou v priebehu 24-48 hodín.\n\n";
            $confirmation_body .= "S pozdravom,\nCS2 Major Turnaje Team";
            
            mail($email, "Potvrdenie prijatia správy", $confirmation_body, $confirmation_headers);
        } else {
            $error = "Došlo k chybe pri odosielaní správy. Skúste neskôr alebo kontaktujte administrátora.";
        }
    }
    

    if ($success) {
        header("Location: formular.html?status=success&message=" . urlencode($success));
        exit();
    } elseif ($error) {
        header("Location: formular.html?status=error&message=" . urlencode($error));
        exit();
    }
}
?>
