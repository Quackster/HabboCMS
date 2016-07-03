<?php

// HoloFigure class
// Checks if the submitted figure is valid.
// Copyright (R) 2009 - Yifan Lu (www.yifanlu.com)
// Please do not remove this :-)

class HoloFigure
{
    var $error = 0;

    function CheckFigure($figure, $gender, $club = false)
    {
        //Init XML engine
        $xml = simplexml_load_file('./xml/figuredata.xml'); // TODO: Change file location
        //Split the figure into sets
        $sets = explode('.', $figure);

        foreach ($sets as $set) {
            //everything is invalid until verified
            $valid = array(false, false, false, false);
            //each set is composed of three parts, 1 - part, 2 - partid, 3 - color
            $parts = explode('-', $set);
            $havesets[] = $parts[0];

            //verify if settype exists
            foreach ($xml->sets->settype as $settype) {
                //make a array of mandatory sets for future use
                if ((string)$settype['mandatory'] == '1') {
                    $mandatory[] = $settype['type'];
                }
                if ((string)$settype['type'] == $parts[0]) {
                    $parts[3] = $settype['paletteid'];
                    $valid[0] = true;
                    //valid, this is the set we are going to use
                    $type = $settype;
                    break;
                }
            }
            //no set found under that name
            if ($valid[0] != true) {
                $this->error = 1;
                return false;
            }
            foreach ($type->set as $xset) {
                if ((string)$xset['id'] == $parts[1]) {
                    //this set is not selectable
                    if ($xset['selectable'] == '0') {
                        $this->error = 2;
                        return false;
                    }
                    if ($xset['colorable'] == '0') {
                        $nocolor = true;
                        if ($parts[2] != '') {
                            $this->error = 3;
                            return false;
                        }
                    } else {
                        //cannot color a uncolorable object
                        $nocolor = false;
                    }
                    // wrong gender
                    if ($xset['gender'] != $gender && $xset['gender'] != "U") {
                        $this->error = 4;
                        return false;
                    }
                    //Not a club member
                    if ($xset['club'] == '1' && $club == false) {
                        $this->error = 5;
                        return false;
                    }
                    $valid[1] = true;
                    //Make an array of the eyes, legs, etc (not in use currently)
                    $details = $xset;
                    break;
                }
            }
            //set id not found
            if ($valid[1] != true) {
                $this->error = 6;
                return false;
            }
            //if item cannot be colors, then skip this check
            if ($nocolor != true) {
                //check if palette exists
                foreach ($xml->colors->palette as $palette) {
                    if ((string)$palette['id'] == (string)$parts[3]) {
                        $valid[2] = true;
                        $pat = $palette;
                        break;
                    }
                }
                //palette not found
                if ($valid[2] != true) {
                    $this->error = 7;
                    return false;
                }
                //check if color exists
                foreach ($pat->color as $color) {
                    if ((string)$color['id'] == $parts[2]) {
                        //club color, not club member
                        if ($color['club'] == '1' && $club == false) {
                            $this->error = 8;
                            return false;
                        }
                        // color not selectable
                        if ($color['selectable'] == '1') {
                            $this->error = 9;
                            return false;
                        }
                        $valid[3] = true;
                        break;
                    }
                }
                // color not found
                if ($valid[3] != true) {
                    $this->error = 10;
                    return false;
                }
            }
        }
        //Check if all mandatory sets are used
        if (count($mandatory) != count(array_intersect($mandatory, $havesets))) {
            $this->error = 11;
            return false;
        }
        return true;
    }
}
