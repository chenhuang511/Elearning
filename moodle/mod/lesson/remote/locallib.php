<?php

function reformat_course($course)
{
    if (is_null($course)) {
        return null;
    }

    $info = new stdClass();
    $info->id = $course->id;
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
}