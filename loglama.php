<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loglama";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    http_response_code(500);
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ad = $_POST['name'];
    $email = $_POST['email'];
    $mesaj = $_POST['message'];

    if (isset($ad) && isset($email) && isset($mesaj)) {

        $stmt = $conn->prepare("INSERT INTO loglama (ad, email, mesaj) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $ad, $email, $mesaj);

        if ($stmt->execute()) {
            echo '<script>alert("Mesajınız başarıyla gönderildi!");</script>';
        } else {
            echo "Hata: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();

    }
    else {
        echo '<script>alert("Lütfen tüm alanları doldurunuz!");</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Loglama</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        console.log('Sayfa yüklendi');

        window.addEventListener('load', function() {
            console.log('Tüm kaynaklar yüklendi');
        });

        function formGonderildi(event) {
            event.preventDefault();

            var ad = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var mesaj = document.getElementById('message').value;

            if (ad && email && mesaj) {
                console.log(`Mesaj gönderildi: ${ad} - ${email} - ${mesaj}`);
                $.ajax({
                    url: 'loglama.php',
                    type: 'POST',
                    data: {
                        name: ad,
                        email: email,
                        message: mesaj
                    },
                    success: function(response) {
                        alert("Mesajınız başarıyla gönderildi!");
                    },
                    error: function(xhr, status, error) {
                        console.error('Sunucudan bir hata döndü:', xhr.status, xhr.statusText);
                        alert('Mesaj gönderilirken bir hata oluştu!');
                    }
                });
            } else {
                console.error('Lütfen tüm alanları doldurunuz');
                alert('Lütfen tüm alanları doldurunuz!');
            }
        }
    </script>
</head>
<body>
<header>
    <h1>Merhaba</h1>
    <nav>
        <ul>
            <li><a href="#">Anasayfa</a></li>
            <li><a href="#">Hakkımızda</a></li>
            <li><a href="#">İletişim</a></li>
        </ul>
    </nav>
</header>

<main>
    <section>
        <h2>Hakkımızda</h2>
        <p>Şu anda bir loglama apisi üzerinde çalışıyorum.</p>
    </section>

    <section>
        <h2>İletişim</h2>
        <form method="post" onsubmit="formGonderildi(event);">
            <label for="name">Adınız:</label>
            <input type="text" id="name" name="name"><br>

            <label for="email">E-posta Adresiniz:</label>
            <input type="email" id="email" name="email"><br>

            <label for="message">Mesajınız:</label>
            <textarea id="message" name="message"></textarea><br>

            <input type="submit" value="Gönder">
        </form>
    </section>
</main>

</body>
</html>
