<?php
/*-------------------------------------------------------+
| SYSTOPIA Remote Event Extension                        |
| Copyright (C) 2020 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_Events_ExtensionUtil as E;
use  \Civi\RemoteParticipant\Event\RegistrationEvent as RegistrationEvent;

/**
 * Implements Logic for the Presbyter Tag
 */
class CRM_Events_PresbyterTag
{
    /**
     * Get the event type ID of the PresbyterTag event type
     *
     * @return integer|null
     *   event type id of the PresbyterTag events
     */
    public static function getEventTypeID()
    {
        static $event_type_id = false;
        if ($event_type_id === false) {
            // look up the event type
            try {
                $event_type_id = civicrm_api3('OptionValue', 'getvalue', [
                    'return'          => 'value',
                    'option_group_id' => 'event_type',
                    'name'            => 'PresbyterTag',
                ]);
            } catch (Exception $ex) {
                // todo: throw exception?
                $event_type_id = null;
            }
        }
        return $event_type_id;
    }

    /**
     * Check if the registration is for a PresbyterTag event
     *
     * @param $event array
     *   registration event data
     */
    public static function isPresbyterTag($event)
    {
        return ($event['event_type_id'] == self::getEventTypeID());
    }

    /**
     * Will identify a contact by its remote ID
     *
     * @param $registration RegistrationEvent
     *   registration event
     */
    public static function mapRegistrationFieldsToContactFields($registration)
    {
        if (self::isPresbyterTag($registration->getEvent())) {
            // todo: if we want to pass registration fields also to XCM (like Kirchenkreis/Kirchengemeinde)
            //  we can map them here to the respective contact fields so they can be passed to XCM

            // map registration fields to custom fields
            $contact_data = &$registration->getContactData();
            $submission = $registration->getSubmission();
            $mapping = [
                'church_district' => 'contact_ekir.ekir_church_district',
                'church_parish'   => 'contact_ekir.ekir_church_parish',
                'presbyter_since' => 'contact_ekir.ekir_presbyter_since',
            ];
            foreach ($mapping as $registration_field => $contact_custom_field) {
                if (isset($submission[$registration_field])) {
                    $contact_data[$contact_custom_field] = $submission[$registration_field];
                }
            }

            // also: derive prefix_id from gender_id
            if (!empty($contact_data['gender_id']) && empty($contact_data['prefix_id'])) {
                switch ($contact_data['gender_id']) {
                    case 1: // female
                        $contact_data['prefix_id'] = 5; // Frau
                        break;

                    case 2: // male
                        $contact_data['prefix_id'] = 6; // Herr
                        break;

                    default:
                        // unknown gender, nothing to do
                }
            }
        }
    }

    /**
     * Allows us to tweak the data for the participant just before it's being created
     *
     * @param $registration RegistrationEvent
     *   registration event
     */
    public static function adjustParticipantParameters($registration)
    {
        if (!$registration->hasErrors() && self::isPresbyterTag($registration->getEvent())) {
            // map registration fields to custom fields
            $mapping = [
                'age_range'       => 'participant_presbytertag.event_presbyter_age_range',
                'sm_instagram'    => 'participant_presbytertag.event_presbyter_comm_instagram',
                'sm_twitter'      => 'participant_presbytertag.event_presbyter_comm_twitter',
                'sm_facebook'     => 'participant_presbytertag.event_presbyter_comm_facebook',
            ];
            $participant_data = &$registration->getParticipant();
            foreach ($mapping as $registration_key => $custom_field) {
                $participant_data[$custom_field] = CRM_Utils_Array::value($registration_key, $participant_data, '');
            }

            // todo: anything else special about the PresbyterTag registration should go in here
        }
    }

    /**
     * PostProcessing after the registrx`ation was finished
     *
     * @param $registration RegistrationEvent
     *   registration event
     */
    public static function registrationPostProcessing($registration)
    {
        if (!$registration->hasErrors() && self::isPresbyterTag($registration->getEvent())) {
            // todo: anything to be done after the registration was successful should go in here
        }
    }
}
