<?php
    class Date{
        public static function FormatNow(){
            return self::FormatSQLDate( time() );
        }

        public static function FormatSQLDate( string $sql_date ){
            $fmt = new IntlDateFormatter(
                    "fr_CA",
                    IntlDateFormatter::MEDIUM,
                    IntlDateFormatter::SHORT,
                    'America/New_York',
                    IntlDateFormatter::GREGORIAN
            );
            return $fmt->format( strtotime( $sql_date ) );
        }
    }
?>