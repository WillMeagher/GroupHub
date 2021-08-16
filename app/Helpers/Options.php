<?php
namespace App\Helpers;

class Options
{

    protected static $platformDropdowns = [
        'discord.gg/'           => 'Discord',
        'groupme.com/'          => 'Groupme',
        'm.me/'                 => 'Messenger',
        'chat.whatsapp.com/'    => 'WhatsApp',
        'fb.me/'                => 'Facebook',
        'www.snapchat.com/'     => 'Snapchat',
        ''                      => 'Other'
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

    /**
     * Get the domains for the different platforms
     *
     * @return array
     */
    public static function platformDomains() {
        return Self::$platformDropdowns;
    }

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