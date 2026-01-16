<?php
$opilased=simplexml_load_file("opilased.xml");
//õpilase otsing
function Lisaopilane(){

    if(isset($_POST['submit'])){
        $xmlDoc = new DOMDocument("1.0","UTF-8");
        $xmlDoc->preserveWhiteSpace = false;
        $xmlDoc->load('opilased.xml');
        $xmlDoc->formatOutput = true;
        $xml_opilane = $xmlDoc->createElement("opilane");
        $xmlDoc->appendChild($xml_opilane);
        $xml_root = $xmlDoc->documentElement;
        $xml_root->appendChild($xml_opilane);
        $xml_elukoht = $xmlDoc->createElement("elukoht");
        $xml_opilane->appendChild($xml_elukoht);
        unset($_POST['submit']);
        foreach($_POST as $voti=>$vaartus)
        {
            $kirje = $xmlDoc->createElement($voti,$vaartus);

            if ($voti == 'linn' || $voti == 'maakond')
               $xml_elukoht->appendChild($kirje);
            else
               $xml_opilane->appendChild($kirje);
        }
        $xmlDoc->save('opilased.xml');
        header("Refesh:0");

    }
    $xml_opilane=simplexml_load_file("opilased.xml");

}
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
    </tr>";
    foreach ($tulemus as $opilane) {
        echo "<tr>";
        echo "<td>" . $opilane->nimi . "</td>";
        echo "<td>" . $opilane->isikukood . "</td>";
        echo "<td>" . $opilane->eriala . "</td>";
        echo "<td>" . $opilane->elukoht->linn . ", " .
            $opilane->elukoht->maakond . "</td>";
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
    </tr>
    <?php
    foreach($opilased->opilane as $opilane){
        echo "<tr>";
        echo "<td>".$opilane->nimi."</td>";
        echo "<td>".$opilane->isikukood."</td>";
        echo "<td>".$opilane->eriala."</td>";
        echo "<td>".$opilane->elukoht->linn.", ".
            $opilane->elukoht->maakond."</td>";
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
                <td><label for="pilt">Pilt:</label></td>
                <td><input type="text" name="pilt" id="pilt"></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" id="submit" value="Sisesta"></td>
                <td></td>
            </tr>
        </form>
    </table>
    <?php
    if(isset($_POST['submit'])){
        $xmlDoc = new DOMDocument("1.0","UTF-8");
        $xmlDoc->preserveWhiteSpace = false;
        $xmlDoc->load('opilased.xml');
        $xmlDoc->formatOutput = true;

        $xml_root = $xmlDoc->documentElement;
        $xmlDoc->appendChild($xml_root);

        $xml_opilane = $xmlDoc->createElement("opilane");
        $xmlDoc->appendChild($xml_opilane);

        $xml_root->appendChild($xml_opilane);

        $xml_elukoht = $xmlDoc->createElement("elukoht");

        unset($_POST['submit']);
        foreach($_POST as $voti=>$vaartus)
        if ($voti == 'linn' || $voti == 'maakond'){
            $xml_elukoht->appendChild($xmlDoc->createElement($voti,$vaartus));
            $xml_opilane->appendChild($xml_elukoht);
        }
        else {
            $kirje = $xmlDoc->CreateElement($voti,$vaartus);
            $xml_opilane->appendChild($kirje);
        }
        $xmlDoc->save('opilased.xml');
        header("Refesh:0");
    }
    ?>
    </body>
    </html>


