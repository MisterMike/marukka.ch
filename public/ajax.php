<?php
/**
 * This file is part of the Minergie Gemeinderating Map Script
 *
 * PHP version 7.2
 *
 * @category  PHP
 * @package   Minergie_Gemeinderating
 * @author    Stefan Friedrich <friedrich@kreisvier.ch>
 * @author    Mischa Sprecher <mischa.sprecher@kreisvier.ch>
 * @copyright 2018 kreisvier communications AG
 * @version   GIT: kreisvier
 * @access    public
 * @link      www.kreisvier.ch
 */

require_once '_includes/config.php';
require_once '_includes/database.php';

try {
    $db = new MysqliDb($host, $user, $password, $db_name);
 } catch (mysqli_sql_exception $e) {
    throw $e;
 }

$gemeinde               = filter_input(INPUT_GET, 'gemeinde');
$order                  = filter_input(INPUT_GET, 'order');
$limit                  = filter_input(INPUT_GET, 'limit');
$kantoneListe           = filter_input(INPUT_GET, 'kantoneListe');
$gemeindegroesseListe   = filter_input(INPUT_GET, 'gemeindegroesseListe');

$select = "SELECT
                bfsnr,
                kanton,
                gemeinde,
                kategorie,
                points_total,
                points_k1,
                points_k2,
                points_k3,
                points_k4,
                points_k5,
                (rank_small+rank_medium+rank_large) as rank_total
            FROM " . $db_table . " WHERE points_total>0
            ";


if ($_SERVER['QUERY_STRING'] != '') {
    echo '<span class="qs" data-qs="' . $_SERVER['QUERY_STRING'] . '"></span>';
} else {
    echo '<span class="qs" data-qs="' . $_SERVER['QUERY_STRING'] . '&order=platz-DESC"></span>';
}


// gemeindename überschreibt alle
if ($gemeinde) {

    $gemeinde = str_replace(' ', '', preg_replace('[^\p{L}|\p{N} -]', '', $gemeinde));

    if ($gemeinde > 0) {

        $bfsnrArr       = array();
        $params         = $gemeinde ;
        $selectBfsnr    = "SELECT bfsnr FROM plz2bfsnr WHERE plz LIKE '%" . $gemeinde . "%' ";

        $resultBfsnr    = $db->rawQuery($selectBfsnr, $params);

        if(!$resultBfsnr){
            die ('<tr><td colspan="9">Nessun risultato</td></tr>');
         } else {
            foreach ($resultBfsnr as $rowBfsnr) {
                array_push($bfsnrArr, $rowBfsnr['bfsnr']);
            }

            $strBfsnr = '';
            for ($i=0; $i<sizeof($bfsnrArr); $i++) {
                if ($bfsnrArr[$i] != '') {
                    $strBfsnr = $strBfsnr . "bfsnr = '" . $bfsnrArr[$i] . "' OR ";
                }
            }
            $select = $select . ' AND (' . substr($strBfsnr, 0,  strlen($strBfsnr) - 4) . ')';
         }
    } else {
        $select = $select . " AND gemeinde LIKE '%" . $gemeinde . "%' ";
    }

} else {

    if (isset($gemeindegroesseListe) && $gemeindegroesseListe != '') {

        $gemeindegroesseListeTemp = explode('|', $gemeindegroesseListe);
        $strGemeindegroesse = '';

        for ($i=0; $i<sizeof($gemeindegroesseListeTemp); $i++) {
            if ($gemeindegroesseListeTemp[$i] == 'GG-GG') {
                $strGemeindegroesse = $strGemeindegroesse . "kategorie = 'grosse Gemeinde' OR ";
            } else if ($gemeindegroesseListeTemp[$i] == 'GG-MG') {
                $strGemeindegroesse = $strGemeindegroesse . "kategorie = 'mittlere Gemeinde' OR ";
            } else if ($gemeindegroesseListeTemp[$i] == 'GG-KG') {
                $strGemeindegroesse = $strGemeindegroesse . "kategorie = 'kleine Gemeinde' OR ";
            }
        }

        $select = $select . ' AND (' . substr($strGemeindegroesse, 0,  strlen($strGemeindegroesse) - 4) . ')';

    }

    if (isset($kantoneListe) && $kantoneListe != '') {

        $kantoneListeTemp = explode('|', $kantoneListe);
        $strKantone = '';

        for ($i=0; $i<sizeof($kantoneListeTemp); $i++) {
            if ($kantoneListeTemp[$i] != '') {
                $kantoneListeTemp[$i] = str_replace('KT-', '', $kantoneListeTemp[$i]);
                $strKantone = $strKantone . "kanton = '" . $kantoneListeTemp[$i] . "' OR ";
            }
        }

        $select = $select . ' AND (' . substr($strKantone, 0,  strlen($strKantone) - 4) . ')';

    }

}

if (!isset($order) || $order == '') {
    $order = 'nix';
}

if ($order == 'kanton-ASC') {
    $select = $select . " ORDER BY kanton ASC, points_total DESC";
} else if ($order == 'kanton-DESC') {
    $select = $select . " ORDER BY kanton DESC, points_total DESC";
} else if ($order == 'gemeinde-ASC') {
    $select = $select . " ORDER BY gemeinde ASC, points_total DESC";
} else if ($order == 'gemeinde-DESC') {
    $select = $select . " ORDER BY gemeinde DESC, points_total DESC";
} else if ($order == 'ranking-ASC') {
    $select = $select . " ORDER BY rank_total ASC, points_total DESC";
} else if ($order == 'ranking-DESC') {
    $select = $select . " ORDER BY rank_total DESC, points_total DESC";
} else if ($order == 'gemeindegroesse-ASC') {
      $select = $select . " ORDER BY FIELD(kategorie, 'kleine Gemeinde', 'mittlere Gemeinde', 'grosse Gemeinde'), points_total ASC";
} else if ($order == 'gemeindegroesse-DESC') {
      $select = $select . " ORDER BY FIELD(kategorie, 'grosse Gemeinde', 'mittlere Gemeinde', 'kleine Gemeinde'), points_total DESC";
} else if ($order == 'points_total-ASC') {
    $select = $select . " ORDER BY points_total ASC";
} else if ($order == 'points_total-DESC') {
    $select = $select . " ORDER BY points_total DESC";
} else if ($order == 'points_k1-ASC') {
    $select = $select . " ORDER BY points_k1 ASC";
} else if ($order == 'points_k1-DESC') {
    $select = $select . " ORDER BY points_k1 DESC";
} else if ($order == 'points_k2-ASC') {
    $select = $select . " ORDER BY points_k2 ASC";
} else if ($order == 'points_k2-DESC') {
    $select = $select . " ORDER BY points_k2 DESC";
} else if ($order == 'points_k3-ASC') {
    $select = $select . " ORDER BY points_k3 ASC";
} else if ($order == 'points_k3-DESC') {
    $select = $select . " ORDER BY points_k3 DESC";
} else if ($order == 'points_k4-ASC') {
    $select = $select . " ORDER BY points_k4 ASC";
} else if ($order == 'points_k4-DESC') {
    $select = $select . " ORDER BY points_k4 DESC";
} else if ($order == 'points_k5-ASC') {
    $select = $select . " ORDER BY points_k5 ASC";
} else if ($order == 'points_k5-DESC') {
    $select = $select . " ORDER BY points_k5 DESC";
} else {
    $select = $select . " ORDER BY rank_total ASC, points_total DESC";
}


if ($limit != 'nono') {
    $select = $select . " LIMIT 10";
}

$result = $db->rawQuery($select);
$counter = 0;

foreach ($result as $row) {
    $counter++;
    $gemeindegroesse = "";
    if ($row['kategorie'] == "grosse Gemeinde") {
        $gemeindegroesse = "GG-GG";
        $gemeindeKategorie = "grande";
    } else if ($row['kategorie'] == "mittlere Gemeinde") {
        $gemeindegroesse = "GG-MG";
        $gemeindeKategorie = "medio";
    } else if ($row['kategorie'] == "kleine Gemeinde") {
        $gemeindegroesse = "GG-KG";
        $gemeindeKategorie = "piccolo";
    }

    $kanton = "KT-" . $row['kanton'];
    $bfsnr = $row['bfsnr'];

    echo '
    <tr data-ort="' . $gemeinde . '" class="tr order-kanton-' . $kanton . ' ' . $gemeindegroesse . ' ' . $kanton . '">
        <td class="td_kt td">' . $row['kanton'] . '</td>
        <td class="td_gemeinde td">' . $row['gemeinde'] . '</td>
        <td class="td_kategorie td">' . $gemeindeKategorie . '</td>
        <td class="td_rankingNatArr td">' . $row['rank_total'] . '</td>
        <td class="order-punkte td">' . round($row['points_total'],1) . '</td>
        <td class="order-punkte td">' . round($row['points_k1'],1) . '</td>
        <td class="order-punkte td">' . round($row['points_k2'],1) . '</td>
        <td class="order-punkte td">' . round($row['points_k3'],1) . '</td>
        <td class="order-punkte td">' . round($row['points_k4'],1) . '</td>
        <td class="order-punkte td">' . round($row['points_k5'],1) . '</td>
    </tr>';
}

echo "</tbody>";
echo ("<span class='hidden' data='".$select."'>&nbsp;</span>");
