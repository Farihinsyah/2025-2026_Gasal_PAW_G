<form method="post" action="form.php">
    <!-- Surname -->
    <label for="surname">Masukkan surname:</label><br>
    <input type="text" id="surname" name="surname"
        value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>"><br>
    <?php
    if (isset($errors['surname'])) {
        echo "<small style='color:red;'>" . htmlspecialchars($errors['surname']) . "</small><br>";
    }
    ?>
    <br>

    <!-- Umur -->
    <label for="umur">Masukkan umur:</label><br>
    <input type="text" id="umur" name="umur"
        value="<?php echo isset($_POST['umur']) ? htmlspecialchars($_POST['umur']) : ''; ?>"><br>
    <?php
    if (isset($errors['umur'])) {
        echo "<small style='color:red;'>" . htmlspecialchars($errors['umur']) . "</small><br>";
    }
    ?>
    <br>

    <!-- Email -->
    <label for="email">Masukkan email:</label><br>
    <input type="text" id="email" name="email"
       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"><br>
    <?php
    if (isset($errors['email'])) {
        echo "<small style='color:red;'>" . htmlspecialchars($errors['email']) . "</small><br>";
    }
    ?>
    <br>

    <input type="submit" value="submit">
</form>