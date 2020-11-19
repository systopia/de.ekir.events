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
use \Civi\RemoteParticipant\Event\ValidateEvent as ValidateEvent;
use Civi\RemoteParticipant\Event\GetCreateParticipantFormEvent as GetCreateParticipantFormEvent;

/**
 * Implements profile 'Presbytertag'
 */
class CRM_Remoteevent_RegistrationProfile_PresbyterTag extends CRM_Remoteevent_RegistrationProfile
{
    /**
     * Get the internal name of the profile represented
     *
     * @return string name
     */
    public function getName()
    {
        return 'PresbyterTag';
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
                'label'       => $l10n->localise("Stammdaten"),
                'weight'      => 10,
                'description' => '',
            ],
            'first_name'   => [
                'name'        => 'first_name',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 40,
                'required'    => 1,
                'label'       => $l10n->localise('Vorname'),
                'description' => $l10n->localise("Vorname des Teilnehmers"),
                'parent'      => 'contact_base'
            ],
            'last_name'    => [
                'name'        => 'last_name',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 50,
                'required'    => 1,
                'label'       => $l10n->localise('Nachname'),
                'description' => $l10n->localise("Nachname des Teilnehmers"),
                'parent'      => 'contact_base'
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
            'email' => [
                'name'        => 'email',
                'type'        => 'Text',
                'validation'  => 'Email',
                'weight'      => 90,
                'required'    => 1,
                'label'       => $l10n->localise('E-Mail'),
                'description' => $l10n->localise("E-Mail Adresse"),
                'parent'      => 'contact_base'
            ],

            'ekir_data' => [
                'type'        => 'fieldset',
                'name'        => 'ekir_data',
                'label'       => $l10n->localise("Erweiterte Daten"),
                'weight'      => 20,
                'description' => '',
            ],
            'church_district' => [
                'name'        => 'church_district',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 70,
                'required'    => 1,
                'options'     => $this->getOptions('church_district', $locale),
                'label'       => $l10n->localise('Kirchenkreis'),
                'description' => $l10n->localise("Zu welchem Kirchenkreis gehören Sie?"),
                'parent'      => 'ekir_data',
            ],
            'church_parish' => [
                'name'        => 'church_parish',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 70,
                'required'    => 1,
                'options'     => $this->getOptions('church_parish', $locale),
                'label'       => $l10n->localise('Kirchengemeinde'),
                'description' => $l10n->localise("Zu welcher Kirchengemeinde gehören Sie?"),
                'parent'      => 'ekir_data',
            ],
            'presbyter_since' => [
                'name'        => 'presbyter_since',
                'type'        => 'Date',
                'validation'  => 'Date',
                'weight'      => 70,
                'required'    => 1,
                'label'       => $l10n->localise('Presbyter seit'),
                'description' => $l10n->localise("Seit wann sind Sie im Presbyterium?"),
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
                //'validation'  => 'regex:/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,29}$/igm',
                'weight'      => 110,
                'required'    => 0,
                'label'       => $l10n->localise('Instagram Account'),
                'description' => $l10n->localise(""),
                'parent'      => 'social_media'
            ],
            'sm_twitter' => [
                'name'        => 'sm_twitter',
                'type'        => 'Text',
                //'validation'  => 'regex:/^@?(\w){1,15}$/',
                'weight'      => 110,
                'required'    => 0,
                'label'       => $l10n->localise('Twitter Account'),
                'description' => $l10n->localise(""),
                'parent'      => 'social_media'
            ],
            'sm_facebook' => [
                'name'        => 'sm_facebook',
                'type'        => 'Text',
                //'validation'  => 'regex:/^[a-z\d.]{5,}$/i',
                'weight'      => 130,
                'required'    => 0,
                'label'       => $l10n->localise('Facebook Account'),
                'description' => $l10n->localise(""),
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
     * @param GetCreateParticipantFormEvent $resultsEvent
     *   the locale to use, defaults to null none. Use 'default' for current
     *
     */
    public function addDefaultValues(GetCreateParticipantFormEvent $resultsEvent)
    {
        if ($resultsEvent->getContactID()) {
            // get contact field list from that
            $data_mapping = [
                'first_name'      => 'first_name',
                'last_name'       => 'last_name',
                'gender_id'       => 'gender_id',
                'email'           => 'email',
                'church_district' => 'contact_ekir.ekir_church_district',
                'church_parish'   => 'contact_ekir.ekir_church_parish',
                'presbyter_since' => 'contact_ekir.ekir_presbyter_since',
            ];
            $field_list = array_flip($data_mapping);
            CRM_Events_CustomData::resolveCustomFields($field_list);

            // adn then use the generic function
            $this->addDefaultContactValues($resultsEvent, array_keys($field_list), $field_list);
        }
    }

    /**
     * Validate the profile fields individually.
     * This only validates the mere data types,
     *   more complex validation (e.g. over multiple fields)
     *   have to be performed by the profile implementations
     *
     * @param ValidateEvent $validationEvent
     *      event triggered by the RemoteParticipant.validate or submit API call
     */
    public function validateSubmission($validationEvent)
    {
        parent::validateSubmission($validationEvent);

        // validate that the parish matches the district
        $submission = $validationEvent->getSubmission();
        if (!empty($submission['church_parish']) && !empty($submission['church_district'])) {
            // the first 6 digits of the parish should match the district
            if ($submission['church_district'] != substr($submission['church_parish'], 0, 6)) {
                $l10n = $validationEvent->getLocalisation();
                $validationEvent->addError('church_parish', $l10n->localise("Diese Kirchengemeinde gehört nicht zum gewählten Kirchenkreis."));
            }
        }
    }

}
