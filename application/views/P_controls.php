<main>
    <p>
        <a href="<?php echo base_url('professeur/addControl')?>" >Ajouter un controle</a><br>
        <a href="<?php echo base_url('professeur/addControl/promo')?>" >Ajouter un DS de promo</a><br>

    </p>
    <?php
    $mat = null;
    if(isset($data['controls'][0])) {
        echo "<h2>Controles</h2>";

        foreach ($data['controls'] as $control) {
            $date = date_create_from_format('Y-m-d', $control->dateControle);
            if ($mat != $control->codeMatière) {
                $mat = $control->codeMatière;
                echo "<p>" . $control->nom . "</p>";
            }
            echo "<p>Controle du " . date_format($date, 'd/m/Y') . " Nom : ".$control->nom." Groupe : " . $control->nomGroupe . " Type : " . $control->typeControle . ($control->average != null ? ( " Moyenne : " . $control->average) : "") . ($control->median != null ? (" Medianne : " . $control->median): "") . " 
            <a href='" . base_url("process_professeur/deletecontrol/" . $control->idControle) . "'>X</a>
            <a href='" . base_url("professeur/editcontrol/" . $control->idControle) . "'>Edit</a></p>";
        }
    }
    if(isset($data['dspromo'])){
        echo "<h2>DS de promo </h2>";
        foreach ($data['dspromo'] as $control) {
            $date = date_create_from_format('Y-m-d', $control->dateControle);
            if ($mat != $control->codeMatière) {
                $mat = $control->codeMatière;
                echo "<p>" . $control->nom . "</p>";
            }
            echo "<p>Controle du " . date_format($date, 'd/m/Y') ." Nom : ".$control->nom."  Type : " . $control->typeControle . ($control->average != null ? ( " Moyenne : " . $control->average) : "") . ($control->median != null ? (" Medianne : " . $control->median): "") . " 
            <a href='" . base_url("process_professeur/deletecontrol/" . $control->idControle) . "'>X</a>
            <a href='" . base_url("professeur/editcontrol/" . $control->idControle) . "'>Edit</a></p>";
        }
    }
        ?>
</main>