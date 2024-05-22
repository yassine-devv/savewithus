<style>
    <?php include 'style/style.css'; ?>
</style>
<?php
//session_start();
include ('../db.php');

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
            if($campo !== "azione_eventi"){
                $sql .= " ".$campo."="."'".$value."',";
            }else{
                $sql .= " ".$campo."="."'".$value."'";
            }
        }


        $sql .= " WHERE `amministratori`.`id_admin` = ".$_SESSION['idamm'].";"; 

        //echo count(array_keys($arrris));


        $ris = $conn->query($sql);

    }
}

function get_page($page)
{
    include ('../db.php');
    switch ($page) {
        case "profili":
            echo "Profili";
            break;
        case "campagne":
            echo "campagne";
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
                                    <input style="margin-left: auto;" type="submit" class="btn btn-primary btn-sm submit-agg"
                                        name="agg-priv" value="Aggiorna">
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

                            <!-- <label>Azione sulle campagne: </label>
                        <input type="checkbox" name="azione-campagne" onchange="console.log(this.value)"><br><br>

                        <label>Azione sui blog: </label>
                        <input type="checkbox" name="azione-blog" onchange="console.log(this.value)"><br><br>
                        
                        <label>Azione sugli eventi: </label>
                        <input type="checkbox" name="azione-eventi" onchange="console.log(this.value)"><br><br> -->
                        </form>
                    </div>
                </div>
            </div>
        <?php
    }
}

?>