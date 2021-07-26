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
 * Strings for component 'assignsubmission_comprimg', language 'en'
 *
 * @package     assignsubmission_comprimg
 * @copyright   2021 michael pollak <moodle@michaelpollak.org>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Compressed Images';
$string['default'] = 'Enabled by default';
$string['default_help'] = 'If set, this submission method will be enabled by default for all new assignments.';
$string['comprimg'] = 'Compressed Images';
$string['enabled'] = 'Compressed Images';
$string['enabled_help'] = 'If enabled, students are able to upload and automatically compress images for their submission.';
$string['comprimgforlog'] = 'An image has been uploaded and was compressed.';
$string['eventassessableuploaded'] = 'An image has been uploaded and was compressed.';
$string['siteuploadlimit'] = 'Site upload limit';

$string['maxwidth'] = 'Maximum width of image';
$string['maxwidth_help'] = 'Larger uploaded images will be compressed to this image width.';
$string['forcemaxwidth'] = 'Force maximum width';
$string['forcemaxwidth_help'] = 'If selected teachers cannot overrule the maximal width of images.';
$string['maxheight'] = 'Maximum height of image';
$string['maxheight_help'] = 'Larger uploaded images will be compressed to this image height.';
$string['forcemaxheight'] = 'Force maximum height';
$string['forcemaxheight_help'] = 'If selected teachers cannot overrule the maximal height of images.';
$string['maxfilesize'] = 'Maximum file size of image';
$string['maxfilesize_help'] = 'Larger uploaded images will be compressed to this image filesize.';
$string['forcemaxfilesize'] = 'Force maximum file size';
$string['forcemaxfilesize_help'] = 'If selected teachers cannot overrule the maximal file size of images.';
$string['studentoverride'] = 'I want to upload this file despite not meeting the criteria.';
$string['noforce'] = "Don't force requirements";
$string['noforce_postfix'] = 'Students are allowed to overrule the requirements.';
$string['prefixscaled'] = "scaled_";
$string['prefixcomp'] = "compressed_";

// Privacy API
$string['privacy:metadata:comprimgpurpose'] = 'The images uploaded for this assignment submission';

$string['errorwidthheight'] = 'Sorry, we couldn\'t resize your uploaded image.';
$string['errormaxsize'] = 'Sorry, your image is still too big after compression.';
$string['errorcompression'] = 'Sorry, we couldn\'t compress your image.';

