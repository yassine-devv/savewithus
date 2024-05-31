<style>
    <?php include 'style/style.css'; ?>
</style>
<?php
//session_start();
include('../db.php');

$arr_keys = ['utenti', 'campagne', 'blog', 'eventi'];

if (isset($_GET['azionecamp'])) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST["accept"])) {
            $sql = "UPDATE campagne SET campagne.stato='2' WHERE campagne.id_campagna=" . $_GET['azionecamp'];
            $ris = $conn->query($sql);

            header('location: index.php?page=campagne');
        }
        if (isset($_POST["delete"])) {
            $sql = "UPDATE campagne SET campagne.stato='0' WHERE campagne.id_campagna=" . $_GET['azionecamp'];
            $ris = $conn->query($sql);

            header('location: index.php?page=campagne');
        }
    }
}
if (isset($_GET['azioneblog'])) {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST["accept"])) {
            $sql = "UPDATE blog SET blog.stato='2' WHERE blog.id_blog=" . $_GET['azioneblog'];
            $ris = $conn->query($sql);

            header('location: index.php?page=blog');
        }
        if (isset($_POST["delete"])) {
            $sql = "UPDATE blog SET blog.stato='0' WHERE blog.id_blog=" . $_GET['azioneblog'];
            $ris = $conn->query($sql);

            header('location: index.php?page=blog');
        }
    }
}


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

    $sql = "SELECT * from amministratori WHERE id_admin=" . $_SESSION['idamm'];
    $risprivilegi = $conn->query($sql);

    switch ($page) {
        case "profili":
            //echo "Profili";
            if ($risprivilegi->fetch_assoc()['azione_utenti'] == "true") {
?>
                <div class="tabl-utenti">
                    <h2>Profili registrati</h2><br>
                    <?php
                    $sql = "SELECT utenti.id_user, utenti.nome, utenti.cognome, utenti.email, utenti.username, utenti.num_tel FROM `utenti`";
                    $ris = $conn->query($sql);

                    //$row = $ris->fetch_assoc();

                    //var_dump($row);
                    if ($ris->num_rows > 0) {
                    ?>

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
                } else {
                    echo "<h2>Non hai accesso a questa sezione</h2>";
                }
        ?>
        </div>
        <?php

            break;
        case "campagne":
            //echo "campagne";
            if ($risprivilegi->fetch_assoc()['azione_campagne'] == "true") {

                $sql = "SELECT utenti.username, campagne.id_campagna, campagne.nome_campagna, campagne.stato, campagne.luogo, campagne.latitudine, campagne.longitudine FROM utenti join campagne on utenti.id_user=campagne.autore";
                $ris = $conn->query($sql);

        ?>
            <div class="tabl-utenti">
                <h2>Campagne</h2><br>


                <table class="table">
                    <h3>Da accettare</h3><br>
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
                                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?azionecamp=" . $row["id_campagna"] ?>" method="post">
                                            <input type="submit" class="btn btn-success" value="Accetta" name="accept">
                                            <input type="submit" class="btn btn-danger" value="Respingi" name="delete">
                                        </form>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <br>
                <h3>In corso</h3><br>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Autore</th>
                            <th scope="col">Nome campagna</th>
                            <th scope="col">Luogo</th>
                            <!-- <th scope="col">Azioni</th> -->
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $sql = "SELECT utenti.username, campagne.id_campagna, campagne.nome_campagna, campagne.stato, campagne.luogo, campagne.latitudine, campagne.longitudine FROM utenti join campagne on utenti.id_user=campagne.autore";
                        $ris = $conn->query($sql);
                        while ($row = $ris->fetch_assoc()) {

                            if ($row["stato"] == "2") {
                        ?>
                                <tr>
                                    <th><?= $row['id_campagna'] ?></th>
                                    <td><?= $row['username']  ?></td>
                                    <td><?= $row['nome_campagna'] ?></td>
                                    <td><?= $row['luogo'] ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
            } else {
                echo 'Non hai accesso a questa sezione';
            }


            break;
        case "blog":
            if ($risprivilegi->fetch_assoc()['azione_blog'] == "true") {
        ?>
            <div class="table-blogs">
                <h3>Blogs</h3>
                <br>
                <table class="table">
                    <h3>Da accettare</h3><br>
                    <?php
                    $sql = "SELECT blog.id_blog, blog.titolo, blog.testo, blog.created, blog.stato, blog.autore, utenti.id_user, utenti.username FROM utenti join blog on utenti.id_user=blog.autore where blog.stato=1";
                    $ris = $conn->query($sql);
                    if ($ris->num_rows > 0) {
                    ?>
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Autore</th>
                                <th scope="col">Titolo</th>
                                <th scope="col">Testo</th>
                                <th scope="col">Data creazione</th>
                                <th scope="col">Azioni</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php

                            while ($row = $ris->fetch_assoc()) {
                                /* if ($row["stato"] == "1") { */
                            ?>
                                <tr>
                                    <th><?= $row['id_blog'] ?></th>
                                    <td><?= $row['username']  ?></td>
                                    <td><?= $row['titolo'] ?></td>
                                    <td>
                                        <button class="btn btn-primary" onclick="view_content_blog('<?= $row['id_blog']  ?>')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                            </svg>
                                        </button>
                                    </td>
                                    <td><?= $row['created'] ?></td>
                                    <td>
                                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?azioneblog=" . $row["id_blog"] ?>" method="post">
                                            <input type="submit" class="btn btn-success" value="Accetta" name="accept">
                                            <input type="submit" class="btn btn-danger" value="Respingi" name="delete">
                                        </form>
                                    </td>
                                </tr>
                            <?php
                                /* } */
                            }


                            ?>
                        </tbody>
                    <?php
                    } else {
                    ?>
                        <tr>
                            <h5>Nessun blog da accettare</h5>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <table class="table">
                    <br>
                    <h3>Pubblici</h3><br>
                    <?php
                    $sql = "SELECT blog.id_blog, blog.titolo, blog.testo, blog.created, blog.stato, blog.autore, utenti.id_user, utenti.username FROM utenti join blog on utenti.id_user=blog.autore where blog.stato=2";
                    $ris = $conn->query($sql);
                    if ($ris->num_rows > 0) {
                    ?>
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Autore</th>
                                <th scope="col">Titolo</th>
                                <th scope="col">Testo</th>
                                <th scope="col">Data creazione</th>
                                <!-- <th scope="col">Azioni</th> -->
                            </tr>
                        </thead>

                        <tbody>
                            <?php

                            while ($row = $ris->fetch_assoc()) {
                                /* if ($row["stato"] == "1") { */
                            ?>
                                <tr>
                                    <th><?= $row['id_blog'] ?></th>
                                    <td><?= $row['username']  ?></td>
                                    <td><?= $row['titolo'] ?></td>
                                    <td>
                                        <button class="btn btn-primary" onclick="view_content_blog('<?= $row['id_blog']  ?>')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                            </svg>
                                        </button>
                                    </td>
                                    <td><?= $row['created'] ?></td>
                                    <!-- <td>
                                                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?azioneblog=" . $row["id_blog"] ?>" method="post">
                                                    <input type="submit" class="btn btn-success" value="Accetta" name="accept">
                                                    <input type="submit" class="btn btn-danger" value="Respingi" name="delete">
                                                </form>
                                            </td> -->
                                </tr>
                            <?php
                                /* } */
                            }


                            ?>
                        </tbody>
                    <?php
                    } else {
                    ?>
                        <tr>
                            <h5>Nessun blog pubblico</h5>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        <?php
            } else {
                echo 'Non hai accesso a questa sezione';
            }
            break;
        case "eventi":
            if ($risprivilegi->fetch_assoc()['azione_eventi'] == "true") {
                echo boolval($risprivilegi->fetch_assoc()['azione_eventi']);
                echo "eventi";
            } else {
                echo 'Non hai accesso a questa sezione';
            }
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

<div class="box-modify-camp">
    <div class="top">
        <span id="title">VISUALIZZA CONTENUTO</span><br>
        <svg onclick="close_box()" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
        </svg>
    </div>

    <div id="content"></div>

</div>

<script>
    function view_content_blog(id) {
        console.log("ciao");
        let box = document.querySelector(".box-modify-camp");

        if (box.style.display == "block") {
            box.style.display = "none";
            document.body.style.overflow = "scroll";
        } else {
            box.style.display = "block";
            document.body.style.overflow = "hidden";

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    document.getElementById('content').innerHTML = "<p>"+this.responseText+"</p>";
                }
            }
            xmlhttp.open("GET", "../functions.php?blog_content=" + id, true);
            xmlhttp.send();
        }

    }

    function close_box() {
        let box = document.querySelector(".box-modify-camp");

        box.style.display = "none";
        document.body.style.overflow = "scroll";
    }
</script>