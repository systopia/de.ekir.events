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
use Civi\RemoteParticipant\Event\GetParticipantFormEventBase as GetParticipantFormEventBase;

/**
 * Implements profile 'Presbytertag'
 */
class CRM_Remoteevent_RegistrationProfile_PresbyterTagUpdate extends CRM_Remoteevent_RegistrationProfile_PresbyterTag
{
    /**
     * Get the internal name of the profile represented
     *
     * @return string name
     */
    public function getName()
    {
        return 'PresbyterTagUpdate';
    }

    /**
     * @param string $locale
     *   the locale to use, defaults to null (current locale)
     *
     * @return array field specs
     * @see CRM_Remoteevent_RegistrationProfile::getFields()
     *
     */
    public function getFields($locale = null)
    {
        $l10n = CRM_Remoteevent_Localisation::getLocalisation($locale);
        $fields = [
            'contact_base' => [
                'type'        => 'fieldset',
                'name'        => 'contact_base',
                'label'       => $l10n->localise("Persönliche Daten"),
                'weight'      => 10,
                'description' => '',
            ],
            'gender_id'    => [
                'name'        => 'gender_id',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 70,
                'required'    => 1,
                'options'     => $this->getOptions('gender', $locale),
                'label'       => $l10n->localise('Geschlecht'),
                'description' => $l10n->localise("Geschlecht des Teilnehmers"),
                'parent'      => 'contact_base'
            ],
            'age_range'    => [
                'name'        => 'age_range',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 70,
                'required'    => 1,
                'options'     => $this->getOptions('age_range', $locale),
                'label'       => $l10n->localise('Altersgruppe'),
                'description' => $l10n->localise("Alterskohorte des Teilnehmers"),
                'parent'      => 'contact_base'
            ],

            'ekir_data' => [
                'type'        => 'fieldset',
                'name'        => 'ekir_data',
                'label'       => $l10n->localise("Erweiterte Daten"),
                'weight'      => 20,
                'description' => '',
            ],
            'presbyter_since' => [
                'name'        => 'presbyter_since',
                'type'        => 'Date',
                'validation'  => 'Date',
                'weight'      => 70,
                'required'    => 1,
                'label'       => $l10n->localise('Im Presbyterium seit'),
                'description' => $l10n->localise("Sollten Sie das genaue Datum nicht mehr wissen, dann reicht auch eine ungefähre Angabe."),
                'parent'      => 'ekir_data',
            ],

            'social_media' => [
                'type'        => 'fieldset',
                'name'        => 'social_media',
                'label'       => $l10n->localise("Social Media"),
                'weight'      => 30,
                'description' => '',
            ],
            'sm_instagram' => [
                'name'        => 'sm_instagram',
                'type'        => 'Text',
                'validation'  => '', //'regex:/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,29}$/igm',
                'weight'      => 110,
                'required'    => 0,
                'label'       => $l10n->localise('Instagram Account'),
                'description' => '',
                'parent'      => 'social_media'
            ],
            'sm_twitter' => [
                'name'        => 'sm_twitter',
                'type'        => 'Text',
                'validation'  => '', // 'regex:/^@?(\w){1,15}$/',
                'weight'      => 110,
                'required'    => 0,
                'label'       => $l10n->localise('Twitter Account'),
                'description' => '',
                'parent'      => 'social_media'
            ],
            'sm_facebook' => [
                'name'        => 'sm_facebook',
                'type'        => 'Text',
                'validation'  => '', // 'regex:/^[a-z\d.]{5,}$/i',
                'weight'      => 130,
                'required'    => 0,
                'label'       => $l10n->localise('Facebook Account'),
                'description' => '',
                'parent'      => 'social_media'
            ],
        ];

        // add GTAC if set
        $presbyter_tag_gtac = Civi::settings()->get('presbyter_gtac');
        if (!empty($presbyter_tag_gtac)) {
            $fields['presbyter_gtac'] = [
                'name' => 'presbyter_gtac',
                'type' => 'Checkbox',
                'validation' => '',
                'weight' => 110,
                'required' => 1,
                'label' => $l10n->localise("Ich akzeptiere die folgenden Nutzungsbedingungen"),
                'description' => $l10n->localise("Die Zusatzvereinbarung muss akzeptiert werden."),
                'parent' => 'gtacs',
                'suffix' => $presbyter_tag_gtac,
                'suffix_display' => 'dialog',
                'suffix_display_label' => $l10n->localise("Details"),
            ];
        }

        return $fields;
    }

    /**
     * Add the default values to the form data, so people using this profile
     *  don't have to enter everything themselves
     *
     * @param GetParticipantFormEventBase $resultsEvent
     *   the locale to use, defaults to null none. Use 'default' for current
     *
     */
    public function addDefaultValues(GetParticipantFormEventBase $resultsEvent)
    {
        // add contact default values
        if ($resultsEvent->getContactID()) {
            // get contact field list from that
            $data_mapping = [
                'gender_id'       => 'gender_id',
                'email'           => 'email',
                'presbyter_since' => 'contact_ekir.ekir_presbyter_since',
            ];
            $field_list = array_flip($data_mapping);
            CRM_Events_CustomData::resolveCustomFields($field_list);

            // adn then use the generic function
            $this->addDefaultContactValues($resultsEvent, array_keys($field_list), $field_list);
        }

        // add contact default values
        if ($resultsEvent->getParticipantID()) {
            $participant = civicrm_api3('Participant', 'getsingle', ['id' => $resultsEvent->getParticipantID()]);
            CRM_Events_CustomData::labelCustomFields($participant);
            foreach (CRM_Events_PresbyterTag::PARTICIPANT_MAPPING as $form_field => $participant_field) {
                $value = CRM_Utils_Array::value($participant_field, $participant, '');
                $resultsEvent->setPrefillValue($form_field, $value);
            }
        }
    }
}
