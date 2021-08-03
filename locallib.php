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
 * This file contains the definition for the library class for file submission plugin
 *
 * This class provides all the functionality for the new assign module.
 *
 * @package     assignsubmission_comprimg
 * @copyright   2021 michael pollak <moodle@michaelpollak.org>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// File areas for file submission assignment.
define('assignsubmission_comprimg_MAXSUMMARYFILES', 5);
define('assignsubmission_comprimg_FILEAREA', 'submission_files');

/**
 * Library class for file submission plugin extending submission plugin base class
 *
 * @package   assignsubmission_comprimg
 * @copyright 2012 NetSpot {@link http://www.netspot.com.au}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class assign_submission_comprimg extends assign_submission_plugin {

    /**
     * Get the name of the file submission plugin
     * @return string
     */
    public function get_name() {
        return get_string('comprimg', 'assignsubmission_comprimg');
    }

    /**
     * Get file submission information from the database
     *
     * @param int $submissionid
     * @return mixed
     */
    private function get_file_submission($submissionid) {
        global $DB;
        return $DB->get_record('assignsubmission_comprimg', array('submission'=>$submissionid));
    }

    /**
     * Get the default setting for file submission plugin
     *
     * @param MoodleQuickForm $mform The form to add elements to
     * @return void
     */
    public function get_settings(MoodleQuickForm $mform) {
        global $CFG, $COURSE;

        // Teachers view.
        // Get admin configuration.
        $adminconf = get_config('assignsubmission_comprimg');
        
        // Check if we already have an instance to work with.
        if ($this->assignment->has_instance()) {
            $defaultmaxheight = $this->get_config('maxheight');
            $defaultmaxwidth = $this->get_config('maxwidth');
            $defaultmaxfilesize = $this->get_config('maxfilesize');
            $defaultnoforce = $this->get_config('noforce');
            $defaultfiletypes = $this->get_config('filetypes');
            $defaultmaxfiles = $this->get_config('maxfiles');
            $defaultmaxbytes = $this->get_config('maxbytes');
        } else {
            $defaultmaxheight = $adminconf->maxheight;
            $defaultmaxwidth = $adminconf->maxwidth;
            $defaultmaxfilesize = $adminconf->maxfilesize;
            $defaultnoforce = 0;
            $defaultfiletypes = $adminconf->filetypes;
            $defaultmaxfiles = get_config('assignsubmission_comprimg', 'maxfiles');
            $defaultmaxbytes = get_config('assignsubmission_comprimg', 'maxbytes');
        }

        // Added a div to allow easy css templating.
        $mform->addElement('html', '<div id="comprimg">');
        
        $mform->addElement('text', 'assignsubmission_comprimg_maxwidth', get_string('maxwidth', 'assignsubmission_comprimg'));
        $mform->setType('assignsubmission_comprimg_maxwidth', PARAM_INT);
        $mform->setDefault('assignsubmission_comprimg_maxwidth', $defaultmaxwidth);
        $mform->addHelpButton('assignsubmission_comprimg_maxwidth', 'maxwidth', 'assignsubmission_comprimg');
        $mform->hideIf('assignsubmission_comprimg_maxwidth', 'assignsubmission_comprimg_enabled', 'notchecked');
        // If teachers are not allowed to change maxwidth, disable UI.
        if($adminconf->forcemaxwidth) {
            $mform->disabledIf('assignsubmission_comprimg_maxwidth', 'assignsubmission_comprimg_enabled', 'checked');
        }

        $mform->addElement('text', 'assignsubmission_comprimg_maxheight', get_string('maxheight', 'assignsubmission_comprimg'));
        $mform->setType('assignsubmission_comprimg_maxheight', PARAM_INT);
        $mform->setDefault('assignsubmission_comprimg_maxheight', $defaultmaxheight);
        $mform->addHelpButton('assignsubmission_comprimg_maxheight', 'maxheight', 'assignsubmission_comprimg');
        $mform->hideIf('assignsubmission_comprimg_maxheight', 'assignsubmission_comprimg_enabled', 'notchecked');
        // If teachers are not allowed to change maxheight, disable UI.
        if($adminconf->forcemaxheight) {
            $mform->disabledIf('assignsubmission_comprimg_maxheight', 'assignsubmission_comprimg_enabled', 'checked');
        }

        $filesizes = array(209716 => '200kB', 524288 => '500kB', 1048576 => '1MB', 2097152 => '2MB', 5242880 => '5MB');
        $mform->addElement('select', 'assignsubmission_comprimg_maxfilesize', get_string('maxfilesize', 'assignsubmission_comprimg'), $filesizes);
        $mform->addHelpButton('assignsubmission_comprimg_maxfilesize', 'maxfilesize', 'assignsubmission_comprimg');
        $mform->setDefault('assignsubmission_comprimg_maxfilesize', $defaultmaxfilesize);
        $mform->hideIf('assignsubmission_comprimg_maxfilesize', 'assignsubmission_comprimg_enabled', 'notchecked');  
        // If teachers are not allowed to change maxheight, disable UI.
        if($adminconf->forcemaxfilesize) {
            $mform->disabledIf('assignsubmission_comprimg_maxfilesize', 'assignsubmission_comprimg_enabled', 'checked');
        }
        
        $mform->addElement('advcheckbox', 'assignsubmission_comprimg_noforce', get_string('noforce', 'assignsubmission_comprimg'), get_string('noforce_postfix', 'assignsubmission_comprimg'));
        $mform->setType('assignsubmission_comprimg_noforce', PARAM_INT);
        $mform->setDefault('assignsubmission_comprimg_noforce', $defaultnoforce);
        $mform->hideIf('assignsubmission_comprimg_noforce', 'assignsubmission_comprimg_enabled', 'notchecked');

        // Maximum submission size over all files.
        $choices = get_max_upload_sizes($CFG->maxbytes, $COURSE->maxbytes, get_config('assignsubmission_comprimg', 'maxbytes'));
        $name = get_string('maxbytes', 'assignsubmission_comprimg');
        $mform->addElement('select', 'assignsubmission_comprimg_maxbytes', $name, $choices);
        $mform->addHelpButton('assignsubmission_comprimg_maxbytes', 'maxbytes', 'assignsubmission_comprimg');
        $mform->setDefault('assignsubmission_comprimg_maxbytes', $defaultmaxbytes);
        $mform->hideIf('assignsubmission_comprimg_maxbytes', 'assignsubmission_comprimg_enabled', 'notchecked');
        
        // Maximum number of uploaded files.
        $options = array();
        for ($i = 1; $i <= get_config('assignsubmission_comprimg', 'maxfiles'); $i++) {
            $options[$i] = $i;
        }
        $name = get_string('maxfiles', 'assignsubmission_comprimg');
        $mform->addElement('select', 'assignsubmission_comprimg_maxfiles', $name, $options);
        $mform->addHelpButton('assignsubmission_comprimg_maxfiles', 'maxfiles', 'assignsubmission_comprimg');
        $mform->setDefault('assignsubmission_comprimg_maxfiles', $defaultmaxfiles);
        $mform->hideIf('assignsubmission_comprimg_maxfiles', 'assignsubmission_comprimg_enabled', 'notchecked');
        
        // Accepted file types.
        $name = get_string('acceptedfiletypes', 'assignsubmission_comprimg');
        $mform->addElement('filetypes', 'assignsubmission_comprimg_filetypes', $name);
        $mform->addHelpButton('assignsubmission_comprimg_filetypes', 'acceptedfiletypes', 'assignsubmission_comprimg');
        $mform->setDefault('assignsubmission_comprimg_filetypes', $defaultfiletypes);
        $mform->hideIf('assignsubmission_comprimg_filetypes', 'assignsubmission_comprimg_enabled', 'notchecked');
    
        $mform->addElement('html', '</div>');
    }

    /**
     * Save the settings for file submission plugin
     *
     * @param stdClass $data
     * @return bool
     */
    public function save_settings(stdClass $data) {
        
        // Store teachers settings if applicable.
        if (isset($data->assignsubmission_comprimg_maxwidth)) {
            $this->set_config('maxwidth', $data->assignsubmission_comprimg_maxwidth);
        }
        if (isset($data->assignsubmission_comprimg_maxheight)) {
            $this->set_config('maxheight', $data->assignsubmission_comprimg_maxheight);
        }
        if (isset($data->assignsubmission_comprimg_maxfilesize)) {
            $this->set_config('maxfilesize', $data->assignsubmission_comprimg_maxfilesize);
        }
        
        // Allow override by students.
        if (isset($data->assignsubmission_comprimg_noforce)) {
            $this->set_config('noforce', $data->assignsubmission_comprimg_noforce);
        }

        if (isset($data->assignsubmission_comprimg_filetypes)) {
            $this->set_config('filetypes', $data->assignsubmission_comprimg_filetypes);
        }
        
        if (isset($data->assignsubmission_comprimg_maxfiles)) {
            $this->set_config('maxfiles', $data->assignsubmission_comprimg_maxfiles);
        }
        
        if (isset($data->assignsubmission_comprimg_maxbytes)) {
            $this->set_config('maxbytes', $data->assignsubmission_comprimg_maxbytes);
        }
        
        return true;
    }

    /**
     * File format options
     *
     * @return array
     */
    private function get_file_options() {
        
        // NOTE: Filepicker ignores maxbytes when used with admin rights.
        $maxbytes = $this->get_config('maxbytes');
        if ($maxbytes == 0) {
            $maxbytes = get_config('assignsubmission_comprimg', 'maxbytes');
        }

        $fileoptions = array('subdirs' => 1,
                                'maxbytes' => $maxbytes,
                                'maxfiles' => $this->get_config('maxfiles'),
                                'accepted_types' => $this->get_configured_typesets(),
                                'return_types' => (FILE_INTERNAL | FILE_CONTROLLED_LINK));
        
        return $fileoptions;
    }

    /**
     * Add elements to submission form
     *
     * @param mixed $submission stdClass|null
     * @param MoodleQuickForm $mform
     * @param stdClass $data
     * @return bool
     */
    public function get_form_elements($submission, MoodleQuickForm $mform, stdClass $data) {
        global $OUTPUT;

        $fileoptions = $this->get_file_options();

        $submissionid = $submission ? $submission->id : 0;

        $adminconf = get_config('assignsubmission_comprimg');
        $teacherconf = $this->get_config();
        
        $maxfilesize = $adminconf->maxfilesize;
        if (!$adminconf->forcemaxfilesize AND isset($teacherconf->maxfilesize)) {
            $maxfilesize = $teacherconf->maxfilesize;
        }
        
        $maxwidth = $adminconf->maxwidth;
        if (!$adminconf->forcemaxwidth AND isset($teacherconf->maxwidth)) {
            $maxwidth = $teacherconf->maxwidth;
        }

        $maxheight = $adminconf->maxheight;
        if (!$adminconf->forcemaxheight AND isset($teacherconf->maxheight)) {
            $maxheight = $teacherconf->maxheight;
        }
        
        // Show the information about compression to the students.
        $humanreadable = display_size($maxfilesize);
        $constr = ['maxwidth' => $maxwidth, 'maxheight' => $maxheight, 'maxfilesize' => $humanreadable];
        $mform->addElement('static', 'constraints', get_string('constraints', 'assignsubmission_comprimg'), 
            get_string('constraintdetails', 'assignsubmission_comprimg', $constr));
        
        $data = file_prepare_standard_filemanager($data,
                                                  'files',
                                                  $fileoptions,
                                                  $this->assignment->get_context(),
                                                  'assignsubmission_comprimg',
                                                  assignsubmission_comprimg_FILEAREA,
                                                  $submissionid);
        $mform->addElement('filemanager', 'files_filemanager', $this->get_name(), null, $fileoptions);

        // Student override.
        if ($this->get_config('noforce') == 1) {
            $mform->addElement('advcheckbox', 'studentoverride', '', get_string('studentoverride', 'assignsubmission_comprimg'));
        }
    }

    /**
     * Count the number of files
     *
     * @param int $submissionid
     * @param string $area
     * @return int
     */
    private function count_files($submissionid, $area) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($this->assignment->get_context()->id,
                                     'assignsubmission_comprimg',
                                     $area,
                                     $submissionid,
                                     'id',
                                     false);

        return count($files);
    }

    /**
     * Save the files and trigger plagiarism plugin, if enabled,
     * to scan the uploaded files via events trigger
     *
     * @param stdClass $submission
     * @param stdClass $data
     * @return bool
     */
    public function save(stdClass $submission, stdClass $data) {
        global $USER, $DB;

        $fileoptions = $this->get_file_options();

        $data = file_postupdate_standard_filemanager($data,
                                                     'files',
                                                     $fileoptions,
                                                     $this->assignment->get_context(),
                                                     'assignsubmission_comprimg',
                                                     assignsubmission_comprimg_FILEAREA,
                                                     $submission->id);

        $filesubmission = $this->get_file_submission($submission->id);

        // Plagiarism code event trigger when files are uploaded.

        $fs = get_file_storage();
        $files = $fs->get_area_files($this->assignment->get_context()->id,
                                     'assignsubmission_comprimg',
                                     assignsubmission_comprimg_FILEAREA,
                                     $submission->id,
                                     'id',
                                     false);

        // Check if the files are okay.
        $adminconf = get_config('assignsubmission_comprimg');
        $teacherconf = $this->get_config();
        
        $maxfilesize = $adminconf->maxfilesize;
        if (!$adminconf->forcemaxfilesize AND isset($teacherconf->maxfilesize)) {
            $maxfilesize = $teacherconf->maxfilesize;
        }
        
        $maxwidth = $adminconf->maxwidth;
        if (!$adminconf->forcemaxwidth AND isset($teacherconf->maxwidth)) {
            $maxwidth = $teacherconf->maxwidth;
        }

        $maxheight = $adminconf->maxheight;
        if (!$adminconf->forcemaxheight AND isset($teacherconf->maxheight)) {
            $maxheight = $teacherconf->maxheight;
        }
        
        $steps = 1; // How many compressiongrades do we degrade with every try?
        $keepaspectratio = true;
        
        $prefixscaled = get_string('prefixscaled', 'assignsubmission_comprimg'); //'zugeschnitten_';
        $prefixcomp = get_string('prefixcomp', 'assignsubmission_comprimg');

        foreach ($files as $file) {
            $imageinfo = $file->get_imageinfo();
            
            // Default if we see no image, break this loop and look at next.
            $compressable = array('image/jpeg', 'image/png', 'image/gif');
            if (!in_array ($imageinfo['mimetype'], $compressable)) {
                continue;
            }
            
            $filename = $file->get_filename();
            
            // Ignore images that are already within width and height range.
            $needswork = 0;
            if (isset($maxwidth) AND $maxwidth > 0) {
                if ($maxwidth < $imageinfo['width']) {
                    $needswork = 1;
                }
            }
            if ($needswork == 0 AND isset($maxheight) AND $maxheight > 0) {
                if ($maxheight < $imageinfo['height']) {
                    $needswork = 1;
                }
            }

            // Correct width and height first, according to the settings.
            if ($needswork) {

                $file_record = array('contextid'=>$file->get_contextid(), 'component'=>$file->get_component(), 'filearea'=>$file->get_filearea(),
                    'itemid'=>$file->get_itemid(), 'filepath'=>$file->get_filepath(),
                    'filename'=>$prefixscaled.$filename, 'userid'=>$file->get_userid());

                try {
                    $newfile = $fs->convert_image($file_record, $file, $maxwidth, $maxheight, $keepaspectratio, null);
                    $file->delete();
                    $file = $newfile;
                } catch (Exception $e) {
                    debugging($e->getMessage());
                    $this->set_error(get_string('errorwidthheight', 'assignsubmission_comprimg'));
                    return false;
                }
            }
            
            // Work to get the file sizes under control, try until we get lucky.
            $compressiongrade = 8;
            while ($file->get_filesize() > $maxfilesize) {
                $file_record = array('contextid'=>$file->get_contextid(), 'component'=>$file->get_component(), 'filearea'=>$file->get_filearea(),
                    'itemid'=>$file->get_itemid(), 'filepath'=>$file->get_filepath(),
                    'filename'=>$prefixcomp.$compressiongrade.'_'.$filename, 'userid'=>$file->get_userid());

                try {
                    // Try to fix them by autocompression and replacing them.
                    $newfile = $fs->convert_image($file_record, $file, null, null, true, $compressiongrade);
                    $file->delete();
                    $file = $newfile;
                                
                } catch (Exception $e) {
                    debugging($e->getMessage());
                    $this->set_error(get_string('errorcompression', 'assignsubmission_comprimg'));
                    return false;
                }            
                
                $compressiongrade = $compressiongrade - $steps;
                if ($compressiongrade < 1) {
                    break;
                }
                
            }

            // Skip to next file and don't evaluate if studentoverride.
            if (isset($data->studentoverride) AND $data->studentoverride) {
                continue;
            }

            // Return feedback after final tries were not successful.
            if ($file->get_filesize() > $maxfilesize) {
                $filedetails = array('filesize' => display_size($newfile->get_filesize()), 'maxfilesize' => display_size($maxfilesize));
                $this->set_error(get_string('errormaxsize', 'assignsubmission_comprimg', $filedetails));
                return false;
            }  

        }

        $count = $this->count_files($submission->id, assignsubmission_comprimg_FILEAREA);

        $params = array(
            'context' => context_module::instance($this->assignment->get_course_module()->id),
            'courseid' => $this->assignment->get_course()->id,
            'objectid' => $submission->id,
            'other' => array(
                'content' => '',
                'pathnamehashes' => array_keys($files)
            )
        );
        if (!empty($submission->userid) && ($submission->userid != $USER->id)) {
            $params['relateduserid'] = $submission->userid;
        }
        if ($this->assignment->is_blind_marking()) {
            $params['anonymous'] = 1;
        }
        $event = \assignsubmission_comprimg\event\assessable_uploaded::create($params);
        $event->trigger();

        $groupname = null;
        $groupid = 0;
        // Get the group name as other fields are not transcribed in the logs and this information is important.
        if (empty($submission->userid) && !empty($submission->groupid)) {
            $groupname = $DB->get_field('groups', 'name', array('id' => $submission->groupid), MUST_EXIST);
            $groupid = $submission->groupid;
        } else {
            $params['relateduserid'] = $submission->userid;
        }

        // Unset the objectid and other field from params for use in submission events.
        unset($params['objectid']);
        unset($params['other']);
        $params['other'] = array(
            'submissionid' => $submission->id,
            'submissionattempt' => $submission->attemptnumber,
            'submissionstatus' => $submission->status,
            'filesubmissioncount' => $count,
            'groupid' => $groupid,
            'groupname' => $groupname
        );

        if ($filesubmission) {
            $filesubmission->numfiles = $this->count_files($submission->id,
                                                           assignsubmission_comprimg_FILEAREA);
            $updatestatus = $DB->update_record('assignsubmission_comprimg', $filesubmission);
            $params['objectid'] = $filesubmission->id;

            $event = \assignsubmission_comprimg\event\submission_updated::create($params);
            $event->set_assign($this->assignment);
            $event->trigger();
            return $updatestatus;
        } else {
            $filesubmission = new stdClass();
            $filesubmission->numfiles = $this->count_files($submission->id,
                                                           assignsubmission_comprimg_FILEAREA);
            $filesubmission->submission = $submission->id;
            $filesubmission->assignment = $this->assignment->get_instance()->id;
            $filesubmission->id = $DB->insert_record('assignsubmission_comprimg', $filesubmission);
            $params['objectid'] = $filesubmission->id;

            $event = \assignsubmission_comprimg\event\submission_created::create($params);
            $event->set_assign($this->assignment);
            $event->trigger();
            return $filesubmission->id > 0;
        }
    }

    /**
     * Remove files from this submission.
     *
     * @param stdClass $submission The submission
     * @return boolean
     */
    public function remove(stdClass $submission) {
        global $DB;
        $fs = get_file_storage();

        $fs->delete_area_files($this->assignment->get_context()->id,
                               'assignsubmission_comprimg',
                               assignsubmission_comprimg_FILEAREA,
                               $submission->id);

        $currentsubmission = $this->get_file_submission($submission->id);
        if ($currentsubmission) {
            $currentsubmission->numfiles = 0;
            $DB->update_record('assignsubmission_comprimg', $currentsubmission);
        }

        return true;
    }

    /**
     * Produce a list of files suitable for export that represent this feedback or submission
     *
     * @param stdClass $submission The submission
     * @param stdClass $user The user record - unused
     * @return array - return an array of files indexed by filename
     */
    public function get_files(stdClass $submission, stdClass $user) {
        $result = array();
        $fs = get_file_storage();

        $files = $fs->get_area_files($this->assignment->get_context()->id,
                                     'assignsubmission_comprimg',
                                     assignsubmission_comprimg_FILEAREA,
                                     $submission->id,
                                     'timemodified',
                                     false);

        foreach ($files as $file) {
            // Do we return the full folder path or just the file name?
            if (isset($submission->exportfullpath) && $submission->exportfullpath == false) {
                $result[$file->get_filename()] = $file;
            } else {
                $result[$file->get_filepath().$file->get_filename()] = $file;
            }
        }
        return $result;
    }

    /**
     * Display the list of files  in the submission status table
     *
     * @param stdClass $submission
     * @param bool $showviewlink Set this to true if the list of files is long
     * @return string
     */
    public function view_summary(stdClass $submission, & $showviewlink) {
        $count = $this->count_files($submission->id, assignsubmission_comprimg_FILEAREA);

        // Show we show a link to view all files for this plugin?
        $showviewlink = $count > assignsubmission_comprimg_MAXSUMMARYFILES;
        if ($count <= assignsubmission_comprimg_MAXSUMMARYFILES) {
            return $this->assignment->render_area_files('assignsubmission_comprimg',
                                                        assignsubmission_comprimg_FILEAREA,
                                                        $submission->id);
        } else {
            return get_string('countfiles', 'assignsubmission_comprimg', $count);
        }
    }

    /**
     * No full submission view - the summary contains the list of files and that is the whole submission
     *
     * @param stdClass $submission
     * @return string
     */
    public function view(stdClass $submission) {
        return $this->assignment->render_area_files('assignsubmission_comprimg',
                                                    assignsubmission_comprimg_FILEAREA,
                                                    $submission->id);
    }

    /**
     * The assignment has been deleted - cleanup
     *
     * @return bool
     */
    public function delete_instance() {
        global $DB;
        // Will throw exception on failure.
        $DB->delete_records('assignsubmission_comprimg',
                            array('assignment'=>$this->assignment->get_instance()->id));

        return true;
    }

    /**
     * Formatting for log info
     *
     * @param stdClass $submission The submission
     * @return string
     */
    public function format_for_log(stdClass $submission) {
        // Format the info for each submission plugin (will be added to log).
        return get_string('comprimgforlog', 'assignsubmission_comprimg');
    }

    /**
     * Return true if there are no submission files
     * @param stdClass $submission
     */
    public function is_empty(stdClass $submission) {
        return $this->count_files($submission->id, assignsubmission_comprimg_FILEAREA) == 0;
    }

    /**
     * Determine if a submission is empty
     *
     * This is distinct from is_empty in that it is intended to be used to
     * determine if a submission made before saving is empty.
     *
     * @param stdClass $data The submission data
     * @return bool
     */
    public function submission_is_empty(stdClass $data) {
        global $USER;
        $fs = get_file_storage();
        // Get a count of all the draft files, excluding any directories.
        $files = $fs->get_area_files(context_user::instance($USER->id)->id,
                                     'user',
                                     'draft',
                                     $data->files_filemanager,
                                     'id',
                                     false);
        return count($files) == 0;
    }

    /**
     * Get file areas returns a list of areas this plugin stores files
     * @return array - An array of fileareas (keys) and descriptions (values)
     */
    public function get_file_areas() {
        return array(assignsubmission_comprimg_FILEAREA=>$this->get_name());
    }

    /**
     * Copy the student's submission from a previous submission. Used when a student opts to base their resubmission
     * on the last submission.
     * @param stdClass $sourcesubmission
     * @param stdClass $destsubmission
     */
    public function copy_submission(stdClass $sourcesubmission, stdClass $destsubmission) {
        global $DB;

        // Copy the files across.
        $contextid = $this->assignment->get_context()->id;
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid,
                                     'assignsubmission_comprimg',
                                     assignsubmission_comprimg_FILEAREA,
                                     $sourcesubmission->id,
                                     'id',
                                     false);
        foreach ($files as $file) {
            $fieldupdates = array('itemid' => $destsubmission->id);
            $fs->create_file_from_storedfile($fieldupdates, $file);
        }

        // Copy the assignsubmission_comprimg record.
        if ($filesubmission = $this->get_file_submission($sourcesubmission->id)) {
            unset($filesubmission->id);
            $filesubmission->submission = $destsubmission->id;
            $DB->insert_record('assignsubmission_comprimg', $filesubmission);
        }
        return true;
    }

    /**
     * Return a description of external params suitable for uploading a file submission from a webservice.
     *
     * @return external_description|null
     */
    public function get_external_parameters() {
        return array(
            'files_filemanager' => new external_value(
                PARAM_INT,
                'The id of a draft area containing files for this submission.',
                VALUE_OPTIONAL
            )
        );
    }

    /**
     * Return the plugin configs for external functions.
     *
     * @return array the list of settings
     * @since Moodle 3.2
     */
    public function get_config_for_external() {
        global $CFG;

        $configs = $this->get_config();

        // Get a size in bytes.
        if ($configs->maxsubmissionsizebytes == 0) {
            $configs->maxsubmissionsizebytes = get_max_upload_file_size($CFG->maxbytes, $this->assignment->get_course()->maxbytes,
                                                                        get_config('assignsubmission_comprimg', 'maxbytes'));
        }
        return (array) $configs;
    }

    /**
     * Get the type sets configured for this assignment.
     *
     * @return array('groupname', 'mime/type', ...)
     */
    private function get_configured_typesets() {
        $typeslist = (string)$this->get_config('filetypes');

        $util = new \core_form\filetypes_util();
        $sets = $util->normalize_file_types($typeslist);

        return $sets;
    }

    /**
     * Determine if the plugin allows image file conversion
     * @return bool
     */
    public function allow_image_conversion() {
        return true;
    }
}
