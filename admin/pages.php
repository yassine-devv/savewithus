<style>
    <?php include 'style/style.css'; ?>
</style>
<?php
//session_start();
include('../db.php');

$arr_keys = ['utenti', 'campagne', 'blog', 'eventi'];


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['modifica-username'])) {
        $sql = "UPDATE amministratori SET amministratori.username='" . $_POST['username'] . "' WHERE amministratori.id_admin=" . $_SESSION['idamm'];
        $ris = $conn->query($sql);
    }

    if (isset($_POST['agg-priv'])) {
        //echo "<script>console.log('".json_encode(implode(",", $_POST))."')</script>";
        $msg = $_POST;
        $arrris = array();

        foreach ($arr_keys as $key) {
            $keyp = 'azione-' . $key;
            //echo $keyp . '<br>';
            if (array_key_exists($keyp, $_POST)) {
                $arrris[$keyp] = "true";
            } else if (!array_key_exists($keyp, $_POST)) {
                $arrris[$keyp] = "false";
            }
        }

        //var_dump($arrris);

        $sql = "UPDATE `amministratori` SET";

        foreach ($arrris as $key => $value) {
            $campo = str_replace("-", "_", $key);
            if ($campo !== "azione_eventi") {
                $sql .= " " . $campo . "=" . "'" . $value . "',";
            } else {
                $sql .= " " . $campo . "=" . "'" . $value . "'";
            }
        }


        $sql .= " WHERE `amministratori`.`id_admin` = " . $_SESSION['idamm'] . ";";

        $ris = $conn->query($sql);
    }
}

function get_page($page)
{
    $dlticon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
  </svg>';
    include('../db.php');
    switch ($page) {
        case "profili":
            //echo "Profili";
            $sql = "SELECT utenti.id_user, utenti.nome, utenti.cognome, utenti.email, utenti.username, utenti.num_tel FROM `utenti`";
            $ris = $conn->query($sql);

            //$row = $ris->fetch_assoc();

            //var_dump($row);
            if ($ris->num_rows > 0) {
?>
                <div class="tabl-utenti">
                    <h2>Profili registrati</h2><br>
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nome Cognome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Username</th>
                                <th scope="col">Telefono</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $ris->fetch_assoc()) {
                            ?>
                                <tr>
                                    <th><?= $row['id_user'] ?></th>
                                    <td><?= $row['nome'] . " " . $row['cognome'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['username'] ?></td>
                                    <td><?= $row['num_tel'] ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else {
                echo "Nessun utente registrato";
            }

            break;
        case "campagne":
            //echo "campagne";

            $sql = "SELECT utenti.username, campagne.id_campagna, campagne.nome_campagna, campagne.stato, campagne.luogo, campagne.latitudine, campagne.longitudine FROM utenti join campagne on utenti.id_user=campagne.autore";
            $ris = $conn->query($sql);

            ?>
            <div class="tabl-utenti">
                <h2>Campagne</h2><br>
                <h3>Da accettare</h3><br>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Autore</th>
                            <th scope="col">Nome campagna</th>
                            <th scope="col">Luogo</th>
                            <th scope="col">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        while ($row = $ris->fetch_assoc()) {
                            if ($row["stato"] == "1") {
                        ?>
                                <tr>
                                    <th><?= $row['id_campagna'] ?></th>
                                    <td><?= $row['username']  ?></td>
                                    <td><?= $row['nome_campagna'] ?></td>
                                    <td><?= $row['luogo'] ?></td>
                                    <td>
                                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
                                            <input type="button" value="<?php echo $dlticon ?>" name="delete">
                                            <input type="submit" value="<?= $dlticon ?>" name="delete">
                                        </form>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php



            break;
        case "blog":
            echo "blog";
            break;
        case "eventi":
            echo "eventi";
            break;
        default:
            $sql = "SELECT * from amministratori";
            $ris = $conn->query($sql);

            $row = $ris->fetch_assoc();
            $usernameadmin = $row['username'];

        ?>
            <div class="container dati_profilo">
                <h1>Dati profilo</h1>

                <div class="row">
                    <div class="col dati">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" class="form-inline">
                            <label>Username:</label><br><br>
                            <input type="text" name="username" value="<?= $usernameadmin ?>">
                            <input type="submit" class="btn btn-primary" value="Modifica" name="modifica-username">
                        </form>
                        <!-- <button class="btn btn-primary">Visualizza password</button> -->
                    </div>
                    <div class="col privilegi">
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">

                            <div style="display: flex;">
                                <h2>Privilegi</h1>
                                    <input style="margin-left: auto;" type="submit" class="btn btn-primary btn-sm submit-agg" name="agg-priv" value="Aggiorna">
                            </div>

                            <br>
                            <?php
                            $arr_keys = ['utenti', 'campagne', 'blog', 'eventi'];

                            foreach ($arr_keys as $key) {
                            ?>
                                <label>Azione su <?= $key ?>: </label>
                                <?php
                                if ($row['azione_' . $key] == "true") {
                                ?>
                                    <input type="checkbox" name="azione-<?= $key ?>" onchange="console.log(this.value)" checked><br><br>
                                <?php
                                } else {
                                ?>
                                    <input type="checkbox" name="azione-<?= $key ?>" onchange="console.log(this.value)"><br><br>
                            <?php

                                }
                            }

                            ?>
                        </form>
                    </div>
                </div>
            </div>
<?php
    }
}

?>