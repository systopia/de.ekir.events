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
        return [
            'first_name'   => [
                'name'        => 'first_name',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 40,
                'required'    => 1,
                'label'       => $l10n->localise('First Name'),
                'description' => $l10n->localise("Participant's First Name"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Data"),
            ],
            'last_name'    => [
                'name'        => 'last_name',
                'type'        => 'Text',
                'validation'  => '',
                'weight'      => 50,
                'required'    => 1,
                'label'       => $l10n->localise('Last Name'),
                'description' => $l10n->localise("Participant's Last Name"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Data"),
            ],
            'gender_id'    => [
                'name'        => 'gender_id',
                'type'        => 'Select',
                'validation'  => '',
                'weight'      => 70,
                'required'    => 1,
                'options'     => $this->getOptions('gender', $locale),
                'label'       => $l10n->localise('Gender'),
                'description' => $l10n->localise("Participant's Gender"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Data"),
            ],
            'email' => [
                'name'        => 'email',
                'type'        => 'Text',
                'validation'  => 'Email',
                'weight'      => 90,
                'required'    => 1,
                'label'       => $l10n->localise('Email'),
                'description' => $l10n->localise("Participant's email address"),
                'group_name'  => 'contact_base',
                'group_label' => $l10n->localise("Contact Data"),
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
                'group_name'  => 'ekir_data',
                'group_label' => $l10n->localise("EKIR Daten"),
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
                'group_name'  => 'ekir_data',
                'group_label' => $l10n->localise("EKIR Daten"),
            ],
            'presbyter_since' => [
                'name'        => 'presbyter_since',
                'type'        => 'Text',
                'validation'  => 'Integer',
                'weight'      => 70,
                'required'    => 1,
                'label'       => $l10n->localise('Presbyter seit'),
                'description' => $l10n->localise("Seit wann sind Sie im Presbyterium?"),
                'group_name'  => 'ekir_data',
                'group_label' => $l10n->localise("EKIR Daten"),
            ],

        ];
    }
}
