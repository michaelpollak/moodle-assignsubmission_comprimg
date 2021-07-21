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
 * This file defines the admin settings for this plugin
 *
 * @package     assignsubmission_comprimg
 * @copyright   2021 michael pollak <moodle@michaelpollak.org>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


// Note: This is on by default.
$settings->add(new admin_setting_configcheckbox('assignsubmission_comprimg/default',
                new lang_string('default', 'assignsubmission_comprimg'),
                new lang_string('default_help', 'assignsubmission_comprimg'), 1));

// Maxwidth in pixels.
$settings->add(new admin_setting_configtext('assignsubmission_comprimg/maxwidth',
                new lang_string('maxwidth', 'assignsubmission_comprimg'),
                new lang_string('maxwidth_help', 'assignsubmission_comprimg'), 1024, PARAM_INT));

// Allow teachers to overrule maxwidth.
$settings->add(new admin_setting_configcheckbox('assignsubmission_comprimg/forcemaxwidth',
                new lang_string('forcemaxwidth', 'assignsubmission_comprimg'),
                new lang_string('forcemaxwidth_help', 'assignsubmission_comprimg'), 0));

// Maxheight in pixels.
$settings->add(new admin_setting_configtext('assignsubmission_comprimg/maxheight',
                new lang_string('maxheight', 'assignsubmission_comprimg'),
                new lang_string('maxheight_help', 'assignsubmission_comprimg'), 1024, PARAM_INT));

// Allow teachers to overrule maxwidth.
$settings->add(new admin_setting_configcheckbox('assignsubmission_comprimg/forcemaxheight',
                new lang_string('forcemaxheight', 'assignsubmission_comprimg'),
                new lang_string('forcemaxheight_help', 'assignsubmission_comprimg'), 0));

// Maxfilesize in megabyte.
$name = new lang_string('maxfilesize', 'assignsubmission_comprimg');
$description = new lang_string('maxfilesize_help', 'assignsubmission_comprimg');

$element = new admin_setting_configtext('assignsubmission_comprimg/maxfilesize',
                $name, $description, 2, PARAM_INT);
$settings->add($element);

// Allow teachers to overrule maxfilesize.
$settings->add(new admin_setting_configcheckbox('assignsubmission_comprimg/forcemaxfilesize',
                new lang_string('forcemaxfilesize', 'assignsubmission_comprimg'),
                new lang_string('forcemaxfilesize_help', 'assignsubmission_comprimg'), 0));

                