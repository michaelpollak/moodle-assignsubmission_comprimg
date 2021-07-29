<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'assignsubmission_comprimg', language 'de'
 *
 * @package     assignsubmission_comprimg
 * @copyright   2021 michael pollak <moodle@michaelpollak.org>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Komprimierter Dateiupload';
$string['default'] = 'Standardmäßig aktiviert';
$string['default_help'] = 'Wenn markiert werden alle zukünftigen Aufgaben komprimierte Bilder erlauben.';
$string['comprimg'] = 'Komprimierter Dateiupload';
$string['enabled'] = 'Komprimierter Dateiupload';
$string['enabled_help'] = 'Wenn markiert können Lernende Bilder hochladen die automatisiert komprimiert werden.';
$string['comprimgforlog'] = 'Dateien wurden hochgeladen und komprimiert.';
$string['eventassessableuploaded'] = 'Dateien wurden hochgeladen und komprimiert.';
$string['siteuploadlimit'] = 'Größenbeschränkung';

$string['maxwidth'] = 'Maximale Breite des Bildes';
$string['maxwidth_help'] = 'Breitere Bilder werden automatisch auf das Format skaliert.';
$string['forcemaxwidth'] = 'Maximale Breite erzwingen';
$string['forcemaxwidth_help'] = 'Wenn markiert können Lehrende die maximale Breite nicht ändern.';
$string['maxheight'] = 'Maximale Höhe des Bildes';
$string['maxheight_help'] = 'Höhere Bilder werden automatisch auf das Format skaliert.';
$string['forcemaxheight'] = 'Maximale Höhe erzwingen';
$string['forcemaxheight_help'] = 'Wenn markiert können Lehrende die maximale Höhe nicht ändern.';
$string['maxfilesize'] = 'Maximale Dateigröße des Bildes';
$string['maxfilesize_help'] = 'Größere Bilder werden automatisch komprimiert.';
$string['forcemaxfilesize'] = 'Maximale Dateigröße erzwingen';
$string['forcemaxfilesize_help'] = 'Wenn markiert können Lehrende die maximale Dateigröße nicht ändern.';
$string['studentoverride'] = 'Ich möchte die Datei hochladen obwohl sie nicht den Vorgaben entspricht.';
$string['noforce'] = "Vorgaben nicht erzwingen";
$string['noforce_postfix'] = 'Lernende können Abgaben die nicht den Vorgaben entsprechen hochladen.';
$string['prefixscaled'] = "skaliert_";
$string['prefixcomp'] = "komprimiert_";
$string['constraints'] = 'Einschränkungen';
$string['constraintdetails'] = 'Bitte laden Sie Bilder innerhalb der folgenden Einschränkungen hoch. 
    Größere Dateien werden automatisch komprimiert.
    <br>Breite: {$a->maxwidth} px<br>Höhe: {$a->maxheight} px<br>Dateigröße: {$a->maxfilesize}';
$string['acceptedfiletypes'] = 'Akzeptierte Dateitypen';
$string['acceptedfiletypes_help'] = 'Die erlaubten Dateitypen können eingeschränkt werden.';
$string['maxfiles'] = 'Maximale Dateianzahl pro Abgabe';
$string['maxfiles_help'] = 'Es können maximal diese Anzahl an Dateien hochgeladen werden.';
$string['maxbytes'] = 'Maximale Dateigröße';
$string['maxbytes_help'] = 'Alle hochgeladenen Dateien dürfen maximal diese Dateigröße erreichen.';

// Privacy API
$string['privacy:metadata:comprimgpurpose'] = 'Dateien die für Abgaben hochgeladen und komprimiert wurden.';

$string['errorwidthheight'] = 'Sorry, wir konnten dein Bild leider nicht zuschneiden.';
$string['errormaxsize'] = 'Sorry, trotz Komprimierung ist dein Bild zu groß. 
    Deine Datei ist {$a->filesize} wobei maximal {$a->maxfilesize} erlaubt ist.';
$string['errorcompression'] = 'Sorry, wir konnten dein Bild leider nicht komprimieren.';