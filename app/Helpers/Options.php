<?php
namespace App\Helpers;

class Options
{

    protected static $platforms = [
        'discord.gg/'           => 'Discord',
        'groupme.com/'          => 'Groupme',
        'm.me/'                 => 'Messenger',
        'chat.whatsapp.com/'    => 'WhatsApp',
        'fb.me/'                => 'Facebook',
        'www.snapchat.com/'     => 'Snapchat',
        ''                      => 'Other'
    ];

    protected static $schools = [
        '@terpmail.umd.edu'  =>  'UMD'
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
     * Get the domains for the different platforms
     *
     * @return array
     */
    public static function platformDomains() {
        return Self::$platforms;
    }

    /**
     * Get dropdown options for groups
     *
     * @return array
     */
    public static function groups() 
    {
        foreach(Self::$platforms as $platform) {
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
     * Get dropdown options for account create and search
     *
     * @return array
     */
    public static function accounts() 
    {
        foreach(Self::$genderDropdowns as $gender) {
            $options['gender'][$gender] = $gender;
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