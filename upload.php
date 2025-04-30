<?php
$to = 'mohmadazelmad3@gmail.com'; // Change this to your email address
$subject = 'Fichiers téléchargés par un étudiant';
$message = 'Voici les fichiers téléchargés par l\'étudiant ' . $_POST['studentName'] . ' pour le cours ' . $_POST['courseName'] . '.';
$headers = 'From: webmaster@example.com';  // Change this to a valid email address.

$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

$headers .= "\nMIME-Version: 1.0\n" .
"Content-Type: multipart/mixed;\n" .
" boundary=\"{$mime_boundary}\"";

$email_message = $message."\n\n";
$email_message .= "--{$mime_boundary}\n" .
"Content-Type:text/plain; charset=\"iso-8859-1\"\n" .
"Content-Transfer-Encoding: 7bit\n\n" .
$email_message . "\n\n";

if (isset($_FILES['files']['name']) && count($_FILES['files']['name']) > 0) {
    for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
        if ($_FILES['files']['error'][$i] == 0) {
            $file_name = $_FILES['files']['name'][$i];
            $file_size = $_FILES['files']['size'][$i];
            $file_tmp = $_FILES['files']['tmp_name'][$i];
            $file_type = $_FILES['files']['type'][$i];
            $file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));

            $email_message .= "--{$mime_boundary}\n" .
            "Content-Type: {$file_type};\n" .
            " name=\"{$file_name}\"\n" .
            "Content-Disposition: attachment;\n" .
            "Content-Transfer-Encoding: base64\n\n" .
            $file_content . "\n\n";
        }  else {
             echo '<script>
                    document.getElementById("error-message").textContent = "Erreur lors du téléchargement du fichier: ' . $file_name . '";
                    document.getElementById("error-message").style.display = "block";
                  </script>';
        }
    }
}
$email_message .= "--{$mime_boundary}--\n";
if (mail($to, $subject, $email_message, $headers)) {
    echo '<script>
            document.getElementById("upload-message").textContent = "Les fichiers ont été envoyés avec succès par email !";
            document.getElementById("upload-message").style.display = "block";
            setTimeout(function(){
                document.getElementById("uploadForm").reset();
                document.getElementById("upload-message").style.display = "none";
            }, 3000);
          </script>';
} else {
    echo '<script>
            document.getElementById("error-message").textContent = "Erreur lors de l\'envoi de l\'email. Veuillez contacter l\'administrateur.";
            document.getElementById("error-message").style.display = "block";
          </script>';
}
?>
