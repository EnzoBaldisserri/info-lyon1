<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('DATE_FORMAT', 'Y-m-d');

/**
 * Look for the next non-empty timetable in the 3 next days.
 *
 * @param int       $resources
 * @param string    $period     'day' or 'week'
 * @param DateTime  $datetime   (default: today)
 * @return array Formatted timetable
 *
 * @see getTimetable()
 */
function getNextTimetable($resources, $period, &$datetime = NULL) {
    global $timezone;
    $timezone = new DateTimeZone('Europe/Paris');

    if ($datetime === NULL) {
        $datetime = new DateTime();
        $datetime->setTimezone($timezone);
    }

    $tempDate = clone $datetime;
    $limit = 0;

    // If hour >= 18h, take next day timetable
    if ($tempDate->format('H') >= 18) {
        $tempDate->modify('+1 day');
        $limit = 1;
    }

    $timetable = getTimetable($resources, $period, $tempDate);

    if (strcasecmp($period, 'day') === 0) {
        // Look at next not empty timetable within 3 days
        while ($limit < 3 && empty($timetable)) {
            $tempDate->modify('+1 day');
            $timetable = getTimetable($resources, $period, $tempDate);
            $limit++;
        }
    }

    // If timetable still empty, reset date
    if (!empty($timetable) && strcasecmp($period, 'day') === 0) {
        $datetime = $tempDate;
    }

    return $timetable;
}

/**
 * Get the timetable of a period.
 *
 * @param int       $resources
 * @param string    $period     'day' or 'week'
 * @param DateTime  $datetime   (default: today)
 * @return array Formatted timetable
 */
function getTimetable($resources, $period, $datetime = NULL)
{
    global $timezone;

    $CI = get_instance();
    $CI->load->model('Timetables');

    // Default $datetime to today
	if ($datetime === NULL) {
        $datetime = new DateTime();
        $datetime->setTimezone($timezone);
    }

    // Generalize time period
	if (strcasecmp($period, 'day') === 0)
	{
        $beginDate = clone $datetime;
        $endDate = clone $datetime;
    }
    else if (strcasecmp($period, 'week') === 0) {

	    $datetimeDay = $datetime->format('N');

	    $beginDate = clone $datetime;
	    $beginDate->modify('-' . ($datetimeDay - 1) . ' days');

	    $endDate = clone $datetime;
	    $endDate->modify('+' . abs(6 - $datetimeDay). ' days');
    }
    else {
	    trigger_error('Period isn\'t "day" nor "week"');
	    return array();
    }

    $updated = false;
	$existedInDB = true;
	$timetable = $CI->Timetables->getJSON($resources);

    if (!empty($timetable)) {
	    $timetable = json_decode($timetable, true);

        $temp = clone $beginDate;
        $validTimeLimit = (new DateTime())
            ->setTimezone($timezone)
            ->modify('-1 day')
            ->getTimeStamp();

        do {
            $year = $temp->format('Y');
            $week = $temp->format('W');
            $dayOfWeek = $temp->format('N');

            // If one day isn't up-to-date, update entire period
            if (!(array_key_exists($year, $timetable)
                && array_key_exists($week, $timetable[$year])
                && (($period === 'week'
                        && $validTimeLimit < $timetable[$year][$week]['updated'])
                    || (array_key_exists($dayOfWeek, $timetable[$year][$week])
                        && $validTimeLimit < $timetable[$year][$week][$dayOfWeek]['updated'])))
            ) {
                $timetable = mergeArrays(
                    _icsToTimetable(
                        _getAdeRequest($resources, $beginDate, $endDate),
                        $beginDate,
                        $endDate
                    ),
                    $timetable
                );
                $updated = true;
                break;
            }

            $temp->modify('+1 ' . $period);
            $diff = $temp->diff($endDate);
        } while ($diff->days !== 0 && $diff->invert !== 1);
    }
	// If no preexisting data was found
	else {
	    // Create from scratch
		$timetable = _icsToTimetable(
            _getAdeRequest($resources, $beginDate, $endDate),
            $beginDate,
            $endDate
        );
		$existedInDB = $timetable !== FALSE;
		$updated = true;
	}

	if ($updated === true) {
        if ($existedInDB) {
            $CI->Timetables->setJSON($resources, json_encode($timetable, JSON_PRETTY_PRINT));
        } else {
            $CI->Timetables->create($resources, json_encode($timetable, JSON_PRETTY_PRINT));
        }
    }
    
	return _narrow($timetable, $beginDate, $endDate, $period);
}

/**
 * Reduce the array to the time between begin date and end date.
 * If $begin and $end don't correspond to $period, $period has priority and
 * $period is applied to $begin
 *
 * @param array     $timetable
 * @param DateTime  $begin
 * @param DateTime  $end
 * @param string    $period     'day' or 'week'
 * @return array
 */
function _narrow($timetable, $begin, $end, $period)
{
    $final = array();

    $temp = clone $begin;
    do {
        $year = $temp->format('Y');
        $week = $temp->format('W');
        $dayOfWeek = $temp->format('N');

        if (array_key_exists($year, $timetable)
            && array_key_exists($week, $timetable[$year])
            &&  array_key_exists($dayOfWeek, $timetable[$year][$week])
        ) {
            if (!array_key_exists($year, $final)) {
                $final[$year] = array();
            }
            if (!array_key_exists($week, $final[$year])) {
                $final[$year][$week] = array();
            }

            unset($timetable[$year][$week][$dayOfWeek]['updated']);
            $final[$year][$week][$dayOfWeek] = $timetable[$year][$week][$dayOfWeek];
        }

        $temp->modify('+1 day');
        $diff = $temp->diff($end);
    } while ($diff->days != 0 && $diff->invert != 1);


    $year = $begin->format('Y');
    $week = $begin->format('W');
    $dayOfWeek = $begin->format('N');

    // Reduce period to week
    if (!isset($final[$year]) || !isset($final[$year][$week])) {
        return array();
    }

    $final = $final[$year][$week];

    // If necessary, reduce week to day
    if (strcasecmp($period, 'day') === 0) {
        if (!isset($final[$dayOfWeek])) {
            trigger_error('Undefined day');
            return array();
        }
        $final = $final[$dayOfWeek];
    }

    return $final;
}

/**
 * Converts a ICS Timetable file to an array that represents the file in PHP.
 *
 * @param string $icsFilepath
 * @param DateTime $beginDate
 * @param DateTime $endDate
 * @return array
 */
function _icsToTimetable($icsFilepath, $beginDate, $endDate)
{
    global $timezone;

	// Remove beginning and ending whitespace characters
	$content = trim(file_get_contents($icsFilepath));

	// Check if file is valid
	if (startsWith('BEGIN:VCALENDAR', $content)
		&& endsWith('END:VCALENDAR', $content))
	{
		$VERSION_SUPPORTED = array('2.0');
		
		$ics = _strToIcs($content);

		// Check if file version is supported
		if (!array_key_exists('VERSION', $ics)
            || !in_array($ics['VERSION'] , $VERSION_SUPPORTED)
        ) {
			trigger_error('ICS File: Unsupported file version', E_USER_WARNING);
			return array();
		}

        $timetable = array();
        $now = time();

        if (array_key_exists('VEVENT', $ics)) {
			// Sort each event at it's place, week then day

            foreach ($ics['VEVENT'] as $event) {
				$startTime = new DateTime($event['DTSTART']);
				$startTime->setTimezone($timezone);

				$year = $startTime->format('Y');
				if (!array_key_exists($year, $timetable)) {
                    $timetable[$year] = array();
                }
				
				$week = $startTime->format('W');
				if (!array_key_exists($week, $timetable[$year])) {
                    $timetable[$year][$week] = array(
                        'updated' => $now
                    );
                }
				
				$day = $startTime->format('N');
				if (!array_key_exists($day, $timetable[$year][$week])) {
					$timetable[$year][$week][$day] = array();
				}

                $description = explode('\n', $event['DESCRIPTION']);

				$groupLimit = 1;
				while (preg_match('/^(G[1-9]+)?S[1-9]+$/i', $description[$groupLimit])) {
				    $groupLimit++;
				}
				
				$groups = implode(', ', array_slice($description, 1, $groupLimit - 1));
				$teachers = implode(', ', array_slice($description, $groupLimit, -1));

                $timetable[$year][$week][$day]['updated'] = $now;
                $timetable[$year][$week][$day][] = array(
                    'name' => $event['SUMMARY'],
                    'timeStart' => $startTime->format('H:i'),
                    'timeEnd' => (new DateTime($event['DTEND']))->setTimezone($timezone)->format('H:i'),
                    'location' => str_replace('\\', '', $event['LOCATION']),
                    'groups' => $groups,
                    'teachers' => $teachers
                );
			}
		}

		$tempDate = clone $beginDate;
		do {
            $year = $tempDate->format('Y');
            if (!array_key_exists($year, $timetable)) {
                $timetable[$year] = array();
            }

            $week = $tempDate->format('W');
            if (!array_key_exists($week, $timetable[$year])) {
                $timetable[$year][$week] = array(
                    'updated' => $now
                );
            }

            $day = $tempDate->format('N');
            if (!array_key_exists($day, $timetable[$year][$week])) {
                $timetable[$year][$week][$day] = array(
                    'updated' => $now
                );
            }

            $tempDate->modify('+1 day');
		    $diff = $tempDate->diff($endDate);
        } while ($diff->days !== 0 || $diff->invert);

		return $timetable;
	}
	else {
		trigger_error('ICS File: Not a valid ICS file "' . $icsFilepath . '"');
		return array();
	}
}


/**
 * Converts a string to an array that represents the ICS element.
 * The string must contain the "BEGIN:" and "END:" lines of the element
 *
 * @param string $str
 * @return array
 */
function _strToIcs($str)
{
	$ics = array();
	
	$str = explode(PHP_EOL, trim($str));
	$len = count($str) - 1;

	if (!(startsWith('BEGIN:', $str[0])
		&& startsWith('END:', $str[$len])))
	{
        trigger_error('Not an ICS element');
		return array();
	}

	// Skip first and last lines, they're BEGIN and END of ics element
	for ($i = 1; $i < $len; $i++) {

		$currLine = explode(':', $str[$i], 2);

		// Line is 'correct'
		if (count($currLine) == 2) {
			if ($currLine[0] == 'BEGIN') {
				// Create new ics element
				$elementType = $currLine[1];
				$elementLines = '';
				
				// Read the whole element
				do {
                    $elementCurrLine = $str[$i];

					// ADE only uses 73-74 characters per line,
                    // So add a new line only if last line isn't 73*n characters long
                    $lenLastLine = strlen(getLastLines($elementLines));
                    if ($lenLastLine % 73 !== 0 && $lenLastLine % 74 !== 0) {
                        $elementLines .= PHP_EOL;
                    }

					$elementLines .=  $elementCurrLine;
				} while ($elementCurrLine !== 'END:'.$elementType && $i++);

				// Make sure there's no whitespace character
				$elementType = trim($elementType);
				if (!array_key_exists($elementType, $ics)) {
                    $ics[$elementType] = array();
                }
				// Compute it
				$ics[$elementType][] = _strToIcs($elementLines);
				
			} else {
				// Add attribute value
				if (array_key_exists($currLine[0], $ics))
					trigger_error('ICS File: Content "' . $currLine[0] . '" overriden');
				$ics[$currLine[0]] = $currLine[1];
			}
		
		}
		// Line is not correct
		else {
			trigger_error('ICS File: Line "' . $str[$i] . '" is invalid'); 
		}
	}
	return $ics;
}

/**
 * Return the URL of the request to ADE.
 *
 * @param int       $resources
 * @param DateTime  $beginDate
 * @param DateTime  $endDate
 * @return string
 */
function _getAdeRequest($resources, $beginDate, $endDate)
{
	return 'http://adelb.univ-lyon1.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?'
        . 'resources=' . $resources
        . '&projectId=1&calType=ical'
        . '&firstDate=' . $beginDate->format(DATE_FORMAT)
        . '&lastDate=' . $endDate->format(DATE_FORMAT);
}

/**
 * Compare two timetable items.
 * Should be used with function usort().
 *
 * @param array $item1
 * @param array $item2
 * @return int As specified by function 'usort'
 */
function sortTimetable($item1, $item2)
{
    return $item1['timeStart'] > $item2['timeStart'] ? 1 : -1;
}

/**
 * @param DateTime  $date   The date to be formatted
 * @return string A readabble date, in french
 */
function translateAndFormat($date)
{
    static $days = array(
        'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'
    );
    static $months = array(
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet',
        'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
    );

    return $days[$date->format('w')] . ' '
        . $date->format('j') . ' '
        . $months[$date->format('n') - 1];
}

/**
 * Compute the height (in percent) a DOM element should take
 * comparing the hours it represents in a day of 10h.
 * To be used in views.
 *
 * @param string    $begin  The beginning of time
 * @param string    $end    The end of time
 * @return string
 */
function computeTimeToHeight($begin, $end)
{
    $interval = abs(strtotime($begin) - strtotime($end));
    return ($interval / 360) . '%';
}

/**
 * Fills time in timetables.
 * To be used in views.
 *
 * @param string    $from
 * @param string    $to
 */
function fillTime($from, $to) {
    ?>
    <div class="fill hide-on-med-and-down" style="height: <?= computeTimeToHeight($from, $to) ?>"></div>
    <?php
}

if (!function_exists('mergeArrays'))
{
    /**
     * Merges two arrays.
     * In case of keys matching, takes the values of $original.
     *
     * @param array $original
     * @param array $added
     * @return array
     */
    function mergeArrays($original, $added)
    {
        foreach ($added as $key => $value) {
            if (is_array($value)) {
                // Make key exists if not
                if (!array_key_exists($key, $original))
                    $original[$key] = array();

                // Merge the sub array
                $original[$key] = mergeArrays($original[$key], $added[$key]);
            } else {
                if (!array_key_exists($key, $original))
                    $original[$key] = $added[$key];
            }
        }
        return $original;
    }
}

if (!function_exists('getLastLine'))
{
    /**
     * Returns the last lines of a string.
     *
     * @param string    $string
     * @param int       $n      The number of lines
     * @return string The last $n lines
     */
    function getLastLines($string, $n = 1)
    {
        $lines = explode(PHP_EOL, $string);
        $lines = array_slice($lines, -$n);
        return implode(PHP_EOL, $lines);
    }
}

if (!function_exists('swap'))
{
    /**
     * Swap the values of the two variables.
     *
     * @param mixed $x
     * @param mixed $y
     */
    function swap(&$x, &$y)
    {
		$tmp = $x;
		$x = $y;
		$y = $tmp;
	}
}

if (!function_exists('startsWith'))
{
    /**
     * Checks if a string begins with another string.
     *
     * @param string    $subject    The subject string
     * @param string    $sub        The substring
     * @return bool
     */
    function startsWith($sub, $subject)
    {
		return substr($subject, 0, strlen($sub)) === $sub;
	}
}

if (!function_exists('endsWith'))
{
    /**
     * Checks if a string ends with another string.
     *
     * @param string    $subject    The subject string
     * @param string    $sub        The substring
     * @return bool
     */
    function endsWith($sub, $subject)
    {
		return substr($subject, strlen($subject) - strlen($sub)) === $sub;
	}
}
