<?php

use Illuminate\Support\Facades\Log;

function getStudentsFromGroup($group) {
    switch ($group) {
        case "SL36":
            return array("Andriusevičiūtė Audronė", "Bachlina Darja (Zaičenko)", "Balčiūnaitė Rugilė",
                "Barauskytė Viktorija", "Belikova Ana", "Bieliauskytė Eivilė",
                "Burbaitė Visanta", "Butkevičiūtė Kamilė", "Dirmontaitė Estela",
                "Gorbačiova Darja", "Jonkutė Kotryna", "Kasparaitienė Ramunė",
                "Kasperavičienė Ingrida", "Kikilaitė Ineta", "Krakytė Simona",
                "Kundrotaitė Laura", "Kuokštytė Viktorija", "Martinavičiūtė Vesta",
                "Metrikaitė Adriana", "Narkutė Vesta", "Nomgaudė Odeta", "Noreikytė Lauryna",
                "Pareigienė Ernesta", "Perminaitė Arona", "Petkutė Gerda", "Rankelytė Ieva",
                "Reiman Lidija", "Skripkauskaitė Enrika", "Stonkutė Kamilė", "Stonkutė Vakarė",
                "Šaltupytė Lauryna", "Šimulytė Vakarė Adrutė", "Ukrinaitė Skaistė", "Vaitiekėnaitė Ugnė",
                "Vasiliauskaitė Livija", "Vasiukaitė Otilija", "Žerlauskaitė Ineta");
        case "SL37":
            return array("Andriusevičiūtė Audronė", "Bachlina Darja (Zaičenko)", "Balčiūnaitė Rugilė",
                "Barauskytė Viktorija", "Belikova Ana", "Bieliauskytė Eivilė", "Burbaitė Visanta", "Butkevičiūtė Kamilė",
                "Dirmontaitė Estela", "Gorbačiova Darja", "Jonkutė Kotryna", "Kasparaitienė Ramunė", "Kasperavičienė Ingrida",
                "Kikilaitė Ineta", "Krakytė Simona", "Kundrotaitė Laura", "Kuokštytė Viktorija", "Martinavičiūtė Vesta",
                "Metrikaitė Adriana", "Narkutė Vesta", "Nomgaudė Odeta", "Noreikytė Lauryna", "Pareigienė Ernesta",
                "Perminaitė Arona", "Petkutė Gerda", "Rankelytė Ieva", "Reiman Lidija", "Skripkauskaitė Enrika",
                "Stonkutė Kamilė", "Stonkutė Vakarė", "Šaltupytė Lauryna", "Šimulytė Vakarė Adrutė", "Ukrinaitė Skaistė",
                "Vaitiekėnaitė Ugnė", "Vasilievich Livija", "Vasiukaitė Otilija", "Žerlauskaitė Ineta");
        default:
            Log::error("Unexpected group: " . $group);
            return array();
    }
}

function getGroups() {
    return array("SL36", "SL37");
}
