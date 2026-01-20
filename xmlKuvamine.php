<?php
$opilased=simplexml_load_file("opilased.xml");
//õpilase otsing
function Lisaopilane()
{

    $xmlDoc = new DOMDocument("1.0", "UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->load('opilased.xml');
    $xmlDoc->formatOutput = true;
    $xml_opilane = $xmlDoc->createElement("opilane");
    $xmlDoc->appendChild($xml_opilane);
    $xml_root = $xmlDoc->documentElement;
    $xml_root->appendChild($xml_opilane);
    $xml_elukoht = $xmlDoc->createElement("elukoht");
    $xml_opilane->appendChild($xml_elukoht);
    $xml_Ained = $xmlDoc->createElement("ained");
    $xml_opilane->appendChild($xml_Ained);
    $xml_aine = $xmlDoc->createElement("aine");
    $xml_aine2 = $xmlDoc->createElement("aine2");
    $xml_Hinded = $xmlDoc->createElement("hinded");
    $xml_opilane->appendChild($xml_Hinded);
    $xml_hinne = $xmlDoc->createElement("hinne");
    $xml_opilane->appendChild($xml_Hinded);
    unset($_POST['submit']);
    foreach ($_POST as $voti => $vaartus) {
        $kirje = $xmlDoc->createElement($voti, $vaartus);

        if ($voti == 'linn' || $voti == 'maakond')
            $xml_elukoht->appendChild($kirje);

        else if($voti == 'aine1')
        {
            $xml_aine->appendChild($xmlDoc->createElement("nimetus", $vaartus));
            $xml_Ained->appendChild($xml_aine);
            $xml_opilane->appendChild($xml_Ained);
        }
        else if($voti == 'hinne1')
        {
            $xml_aine->appendChild($xmlDoc->createElement("hinne", $vaartus));
            $xml_Ained->appendChild($xml_aine);
            $xml_opilane->appendChild($xml_Ained);
        }
        else if($voti == 'aine2')
        {
            $xml_aine2->appendChild($xmlDoc->createElement("nimetus", $vaartus));
            $xml_Ained->appendChild($xml_aine2);
            $xml_opilane->appendChild($xml_Ained);
        }
        else if($voti == 'hinne2')
        {
            $xml_aine2->appendChild($xmlDoc->createElement("hinne", $vaartus));
            $xml_Ained->appendChild($xml_aine2);
            $xml_opilane->appendChild($xml_Ained);
        }
        else {
            $kirje = $xmlDoc->createElement($voti, $vaartus);
            $xml_opilane->appendChild($kirje);
        }
    }

    $xmlDoc->save("opilased.xml");
    unset($_POST["submit"]);
}

    if(isset($_POST["submit"]))
    {
        LisaOpilane();
        header("Location: " . $_SERVER["PHP_SELF"]);
    }
    $opilased=simplexml_load_file("opilased.xml");



function erialaOtsing($paring){
    global $opilased;
    $tulemus=array();
    foreach($opilased->opilane as $opilane) {
        if (substr(strtolower($opilane->eriala), 0, strlen($paring))
            == strtolower($paring)) {
            array_push($tulemus, $opilane);
        } else if (substr(strtolower($opilane->nimi), 0, strlen($paring))
            == strtolower($paring)) {
            array_push($tulemus, $opilane);
        } else if (substr(strtolower($opilane->isikukood), 0, strlen($paring))
            == strtolower($paring)) {
            array_push($tulemus, $opilane);
        }

    }
    return $tulemus;
}

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="CSS.css">
    <script src="vapp.js"></script>
    <title>XML faili kuvamine - Opilased.xml</title>
</head>
<body>
<h1>XML faili kuvamine - Opilased.xml</h1>
<?php
//1.õpilase nimi
echo "1.õpilase nimi: ".$opilased->opilane[0]->nimi;
//kõik õpilased
?>
<form action="?" method="post">
    <label for="otsing">Õpilase nimi | Eriala | Isikukood:</label>
    <input type="text" name="otsing" id="otsing" placeholder="Nimi | Eriala | isikukood">
    <input type="submit" value="OK">
</form>
<?php
//otsingu tulemus:
if(!empty($_POST['otsing'])){
    $tulemus = erialaOtsing($_POST['otsing']);
        echo "<table>
    <tr>
        <th>Õpilase nimi</th>
        <th>Isikukood</th>
        <th>Eriala</th>
        <th>Elukoht</th>
        <th>Ained</th>
        <th>Hinnded</th>
        <th>Pilt</th>
    </tr>";
    foreach ($tulemus as $opilane) {
        echo "<tr>";
        echo "<td>" . $opilane->nimi . "</td>";
        echo "<td>" . $opilane->isikukood . "</td>";
        echo "<td>" . $opilane->eriala . "</td>";
        echo "<td>" . $opilane->elukoht->linn . ", " .
            $opilane->elukoht->maakond . "</td>";
        echo "<td>" . $opilane->ained->aine->nimetus . ",".
            $opilane->ained->aine->hinne . "</td>";
        echo "<td>" . $opilane->ained->aine->nimetus . ",".
            $opilane->ained->aine->hinne . "</td>";
        echo "<td><img src='".$opilane->pilt."' width='100px'></td>";
        echo "</tr>";
    }
    echo "</table>";
}else {
?>
<table>
    <tr>
        <th>Õpilase nimi</th>
        <th>Isikukood</th>
        <th>Eriala</th>
        <th>Elukoht</th>
        <th>Ained</th>
        <th>Hinnded</th>
        <th>Pilt</th>
    </tr>
    <?php
    foreach($opilased->opilane as $opilane){
        echo "<tr>";
        echo "<td>".$opilane->nimi."</td>";
        echo "<td>".$opilane->isikukood."</td>";
        echo "<td>".$opilane->eriala."</td>";
        echo "<td>".$opilane->elukoht->linn.", ".
            $opilane->elukoht->maakond."</td>";
        echo "<td>" . $opilane->ained->nimetus . ",".
            $opilane->ained->hinne . "</td>";
        echo "<td>" . $opilane->hinded->nimetus . ",".
            $opilane->hinded->hinne . "</td>";
        echo "<td><img src='".$opilane->pilt."' width='100px'></td>";
        echo "</tr>";
    }
    }
    ?>
    <html>
    <body>
    <h2>Õpilase Andmete Sisestamine Ja Salvestamine</h2>
    <table>
        <form action="" method="post" name="vorm1">

            <tr>
                <td><label for="nimi">Õpilase Nimi:</label></td>
                <td><input type="text" name="nimi" id="nimi" autofocus></td>
            </tr>
            <tr>
                <td><label for="isikukood">Isikukood:</label></td>
                <td><input type="text" name="isikukood" id="isikukood"></td>
            </tr>
            <tr>
                <td><label for="eriala">Eriala:</label></td>
                <td><input type="text" name="eriala" id="eriala"></td>
            </tr>
            <tr>
                <td><label for="linn">Linn:</label></td>
                <td><input type="text" name="linn" id="linn"></td>
            </tr>
            <tr>
                <td><label for="maakond">Maakond:</label></td>
                <td><input type="text" name="maakond" id="maakond"></td>
            </tr>
            <tr>
                <td><label for="aine1">Aine1:</label></td>
                <td><input type="text" name="aine1" id="aine1"></td>
            </tr>
            <tr>
                <td><label for="aine2">Aine2:</label></td>
                <td><input type="text" name="aine2" id="aine2"></td>
            </tr>
            <tr>
                <td><label for="hinne1">Hinne1:</label></td>
                <td><input type="text" name="hinne1" id="hinne1"></td>
            </tr>
            <tr>
                <td><label for="hinne2">Hinne2:</label></td>
                <td><input type="text" name="hinne2" id="hinne2"></td>
            </tr>
            <tr>
                <td><label for="pilt">Pilt:</label></td>
                <td><input type="text" name="pilt" id="pilt"></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" id="submit" value="Sisesta"></td>
                <td></td>
            </tr>
        </form>
    </table>
    </body>
    </html>


