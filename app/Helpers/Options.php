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
        'Accounting',
        'Aerospace Engineering',
        'African American Studies',
        'Agricultural and Resource Economics',
        'Agricultural Science and Technology',
        'American Studies',
        'Animal Sciences',
        'Anthropology',
        'Arabic Studies',
        'Architecture',
        'Art Education',
        'Art History and Archaeology',
        'Astronomy',
        'Atmospheric and Oceanic Science',
        'Biochemistry',
        'Bioengineering',
        'Biological Sciences',
        'Central European, Russian and Eurasian Studies',
        'Chemical and Biomolecular Engineering',
        'Chemistry',
        'Chinese',
        'Cinema & Media Studies',
        'Civil and Environmental Engineering',
        'Classics',
        'Communication',
        'Community Health',
        'Computer Engineering',
        'Computer Science',
        'Criminology and Criminal Justice',
        'Dance',
        'Early Childhood and Early Childhood Special Education',
        'Economics',
        'Electrical Engineering',
        'Elementary Education',
        'Elementary/Middle Special Education',
        'English',
        'Environmental Science',
        'Family Science',
        'Finance',
        'Fire Protection Engineering',
        'French',
        'Geographical Sciences and GIS',
        'Geology',
        'Germanic Studies',
        'Government and Politics',
        'Hearing and Speech Sciences',
        'History',
        'Human Development',
        'Immersive Media Design',
        'Individual Studies Program',
        'Information Science',
        'Information Systems',
        'International Business',
        'International Relations',
        'Italian Studies',
        'Japanese',
        'Jewish Studies',
        'Journalism',
        'Kinesiology',
        'Letters and Sciences (Undecided/Undeclared)',
        'Linguistics',
        'Management',
        'Marketing',
        'Materials Science and Engineering',
        'Mathematics',
        'Mechanical Engineering',
        'Middle School Math and Science Education',
        'Music',
        'Neuroscience',
        'Nutrition and Food Science',
        'Operations Management and Business Analytics',
        'Persian Studies',
        'Philosophy',
        'Physics',
        'Plant Sciences',
        'Psychology',
        'Public Health Science',
        'Public Policy',
        'Religions of the Ancient Middle East',
        'Romance Languages',
        'Russian Language and Literature',
        'Secondary Education',
        'Sociology',
        'Spanish Language, Literature and Cultures',
        'Studio Art',
        'Supply Chain Management',
        'Theatre',
        'Women, Gender, and Sexuality Studies',
    ];

    protected static $yearDropdowns = [
        'Freshman',
        'Sophomore',
        'Junior',
        'Senior',
        'Freshman',
        'Graduate Student',
        'Faculty'
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