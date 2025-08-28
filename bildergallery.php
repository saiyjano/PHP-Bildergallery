<!DOCTYPE html>
<html>

<head>
    <title>Bildergallerie</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0 auto;
            background: #f0f0f0;
            font: 11pt Calibri;
        }

        #content {
            margin: 10px auto;
            background: #ffffff;
            padding: 10px;
            border: 1px solid #dddddd;
            text-align: center;
            width: 750px;
        }

        /* ―――――― [ Sonstiges ] ―――――― */
        img {
            object-fit: cover;
            width: 200px;
            height: auto;
            max-height: 200px;
        }

        /* ―――――― [ Seitennavigation ] ―――――― */
        .navigation {
            width: 100%;
            padding: 10px;
        }

        .navigation a {
            padding: 10px;
            color: #a9b18e;
            text-decoration: none;
            margin: 5px;
        }


        .navigation a:hover {
            background: #a9b18e;
            color: #fff;
        }

        .current {
            padding: 10px;
            color: #dbc3a6;
            margin: 5px;
            font-weight: bold;
        }

        .pages {
            padding: 10px;
            color: #dbc3a6;
            margin: 5px;
        }

        .pages::before {
            content: "|";
            color: #dddddd;
            margin: 5px 20px 5px 5px;
        }

        .disabled {
            color: #dddddd;
            cursor: default;
            pointer-events: none;
            text-decoration: none;
            margin: 5px;
        }
    </style>
</head>

<body>

    <div id="content">
        <p>
            <?php
            // ―――――― [ SETTINGS ] ――――――
            $url         = ""; // Hier gibst du deinen Pfad zur Gallerie an.
            $go          = max(1, (int)($_GET['go'] ?? 1));
            $anzeige     = 6; // Zahl für die Auslese. Also wie viele Bilder angezeigt werden sollen
            $p           = 2;
            $verzeichnis = "images/"; // Wo sind deine Bilder gespeichert

            // Bilder sammeln & sortieren
            $bilder = glob("$verzeichnis*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE); // Erlaubte Dateien
            sort($bilder); // Bilder werden nach dem Namen sotiert. 

            $pages = max(1, ceil(count($bilder) / $anzeige));
            $go    = min($go, $pages);

            // ―――――― [ BILDER AUSLESEN ] ――――――
            foreach (array_slice($bilder, ($go - 1) * $anzeige, $anzeige) as $bild) {
                $title = pathinfo($bild, PATHINFO_FILENAME);
                echo <<<HTML
                        <img src="$bild">
                    HTML;
            }

            // ―――――― [ SEITENAVIGATION ] ――――――
            $range = range(max(1, $go - $p), min($pages, $go + $p));
            $links = array_map(
                fn($i) => $i == $go ? "<span class='current'>[ $i ]</span>" : "<a href='$url?go=$i'>$i</a>",
                $range
            );

            // Erste/Zurück
            if ($go > 1) {
                array_unshift($links, "<a href='$url?go=1'>« Erste</a>", "<a href='$url?go=" . ($go - 1) . "'>«</a>");
            } else {
                array_unshift($links, "<span class='disabled'>« Erste</span>", "<span class='disabled'>«</span>");
            }

            // Weiter/Letzte
            if ($go < $pages) {
                array_push($links, "<a href='$url?go=" . ($go + 1) . "'>»</a>", "<a href='$url?go=$pages'>Letzte »</a>");
            } else {
                array_push($links, "<span class='disabled'>»</span>", "<span class='disabled'>Letzte »</span>");
            }

            echo "<div class='navigation'>" . implode(" ", $links) . " <span class='pages'>Seite $go von $pages</span></div>";
            ?>
    </div>

</body>

</html>