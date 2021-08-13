<?php
namespace App\Helpers;

class Options
{

    protected static $platformDropdowns = [
        'Discord',
        'Groupme',
        'Instagram',
        'Facebook',
        'Other'
    ];

    protected static $typeDropdowns = [
        'Class',
        'Sports',
        'Clubs',
        'Intermurals',
        'Greek Life',
        'Univerity Sponsored',
        'Other'
    ];

    protected static $privacyDropdowns = [
        'Public',
        'Private',
        'Delisted'
    ];
    
    protected static $searchForDropdowns = [
        'Groups',
        'Users'
    ];

    /**
     * Get dropdown options for groups
     *
     * @return array
     */
    public static function groups() 
    {
        foreach(Self::$platformDropdowns as $platform) {
            $options['platform'][$platform] = $platform;
        }
        foreach(Self::$typeDropdowns as $type) {
            $options['type'][$type] = $type;
        }
        foreach(Self::$privacyDropdowns as $privacy) {
            $options['privacy'][$privacy] = $privacy;
        }
        foreach(Self::$searchForDropdowns as $searchFor) {
            $options['searchfor'][$searchFor] = $searchFor;
        }

        return $options;
    }

    protected static $genderDropdowns = [
        'Male',
        'Female',
        'Non-Binary'
    ];

    protected static $schoolDropdowns = [
        'UMD',
        'GC',
        'AMU'
    ];

    protected static $majorDropdowns = [
        'Computer Science',
        'Biology',
        'Electrical Engineering',
        'History'
    ];

    protected static $yearDropdowns = [
        'Undergraduate Freshman',
        'Undergraduate Sophomore',
        'Undergraduate Junior',
        'Undergraduate Senior',
        'Graduate Freshman',
        'Other'
    ];

    public static function accounts() 
    {
        foreach(Self::$genderDropdowns as $gender) {
            $options['gender'][$gender] = $gender;
        }

        foreach(Self::$schoolDropdowns as $school) {
            $options['school'][$school] = $school;
        }

        foreach(Self::$majorDropdowns as $major) {
            $options['major'][$major] = $major;
        }

        foreach(Self::$yearDropdowns as $year) {
            $options['year'][$year] = $year;
        }

        return $options;
    }
    
}