<?php

function reformat_course($course)
{
    if (is_null($course)) {
        return null;
    }

    $info = new stdClass();
    $info->id = $course->remoteid;
    $info->category = $course->category;
    $info->sortorder = $course->sortorder;
    $info->fullname = $course->fullname;
    $info->shortname = $course->shortname;
    $info->idnumber = $course->idnumber;
    $info->summary = $course->summary;
    $info->summaryformat = $course->summaryformat;
    $info->format = $course->format;
    $info->showgrades = $course->showgrades;
    $info->newsitems = $course->newsitems;
    $info->startdate = $course->startdate;
    $info->marker = $course->marker;
    $info->maxbytes = $course->maxbytes;
    $info->legacyfiles = $course->legacyfiles;
    $info->showreports = $course->showreports;
    $info->visible = $course->visible;
    $info->visibleold = $course->visibleold;
    $info->groupmode = $course->groupmode;
    $info->groupmodeforce = $course->groupmodeforce;
    $info->defaultgroupingid = $course->defaultgroupingid;
    $info->lang = $course->lang;
    $info->calendartype = $course->calendartype;
    $info->theme = $course->theme;
    $info->timecreated = $course->timecreated;
    $info->timemodified = $course->timemodified;
    $info->requested = $course->requested;
    $info->enablecompletion = $course->enablecompletion;
    $info->completionnotify = $course->completionnotify;
    $info->cacherev = $course->cacherev;
    $info->categoryname = $course->categoryname;
    $info->thumbnail = '0';

    return $info;
}

function reformat_lesson($lesson) {
    if(is_null($lesson)) {
        return null;
    }

    $info = new stdClass();
    $info->firstpageid = null;
    $info->lastpageid = null;
    $info->pages = array();
    $info->loadedallpages = false;
    $info->properties = array(
        'id' => strval($lesson->id),
        'course' => strval($lesson->course),
        'name' => strval($lesson->name),
        'intro' => strval($lesson->intro),
        'introformat' => strval($lesson->introformat),
        'practice' => strval($lesson->practice),
        'modattempts' => strval($lesson->modattempts),
        'usepassword' => strval($lesson->usepassword),
        'password' => strval($lesson->password),
        'dependency' => strval($lesson->dependency),
        'conditions' => strval($lesson->conditions),
        'grade' => strval($lesson->grade),
        'custom' => strval($lesson->custom),
        'ongoing' => strval($lesson->ongoing),
        'usemaxgrade' => strval($lesson->usemaxgrade),
        'maxanswers' => strval($lesson->maxanswers),
        'maxattempts' => strval($lesson->maxattempts),
        'review' => strval($lesson->review),
        'nextpagedefault' => strval($lesson->nextpagedefault),
        'feedback' => strval($lesson->feedback),
        'minquestions' => strval($lesson->minquestions),
        'maxpages' => strval($lesson->maxpages),
        'timelimit' => strval($lesson->timelimit),
        'retake' => strval($lesson->retake),
        'activitylink' => strval($lesson->activitylink),
        'mediafile' => strval($lesson->mediafile),
        'mediaheight' => strval($lesson->mediaheight),
        'mediawidth' => strval($lesson->mediawidth),
        'mediaclose' => strval($lesson->mediaclose),
        'slideshow' => strval($lesson->slideshow),
        'width' => strval($lesson->width),
        'height' => strval($lesson->height),
        'bgcolor' => strval($lesson->bgcolor),
        'displayleft' => strval($lesson->displayleft),
        'displayleftif' => strval($lesson->displayleftif),
        'progressbar' => strval($lesson->progressbar),
        'available' => strval($lesson->available),
        'deadline' => strval($lesson->deadline),
        'timemodified' => strval($lesson->timemodified),
        'completionendreached' => strval($lesson->completionendreached),
        'completiontimespent' => strval($lesson->completiontimespent)
    );

    return $info;
}