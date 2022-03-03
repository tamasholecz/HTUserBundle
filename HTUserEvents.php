<?php

namespace HT\UserBundle;

final class HTUserEvents
{
    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const CHANGE_PASSWORD_INITIALIZE = 'user.change_password.edit.initialize';

    /**
     * @Event("HT\UserBundle\Event\FormEvent")
     */
    const CHANGE_PASSWORD_SUCCESS = 'user.change_password.edit.success';

    /**
     * @Event("HT\UserBundle\Event\FilterUserResponseEvent")
     */
    const CHANGE_PASSWORD_COMPLETED = 'user.change_password.edit.completed';

    /**
     * @Event("HT\UserBundle\Event\GroupEvent")
     */
    const GROUP_CREATE_INITIALIZE = 'user.group.create.initialize';

    /**
     * @Event("HT\UserBundle\Event\FormEvent")
     */
    const GROUP_CREATE_SUCCESS = 'user.group.create.success';

    /**
     * @Event("HT\UserBundle\Event\FilterGroupResponseEvent")
     */
    const GROUP_CREATE_COMPLETED = 'user.group.create.completed';

    /**
     * @Event("HT\UserBundle\Event\FilterGroupResponseEvent")
     */
    const GROUP_DELETE_COMPLETED = 'user.group.delete.completed';

    /**
     * @Event("HT\UserBundle\Event\GetResponseGroupEvent")
     */
    const GROUP_EDIT_INITIALIZE = 'user.group.edit.initialize';

    /**
     * @Event("HT\UserBundle\Event\FormEvent")
     */
    const GROUP_EDIT_SUCCESS = 'user.group.edit.success';

    /**
     * @Event("HT\UserBundle\Event\FilterGroupResponseEvent")
     */
    const GROUP_EDIT_COMPLETED = 'user.group.edit.completed';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const PROFILE_EDIT_INITIALIZE = 'user.profile.edit.initialize';

    /**
     * @Event("HT\UserBundle\Event\FormEvent")
     */
    const PROFILE_EDIT_SUCCESS = 'user.profile.edit.success';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const PROFILE_EDIT_COMPLETED = 'user.profile.edit.completed';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const REGISTRATION_INITIALIZE = 'user.registration.initialize';

    /**
     * @Event("HT\UserBundle\Event\FormEvent")
     */
    const REGISTRATION_SUCCESS = 'user.registration.success';

    /**
     * @Event("HT\UserBundle\Event\FormEvent")
     */
    const REGISTRATION_FAILURE = 'user.registration.failure';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const REGISTRATION_COMPLETED = 'user.registration.completed';

    /**
     * @Event("HT\UserBundle\Event\UserNullableEvent")
     */
    const REGISTRATION_INIT_CONFIRM = 'user.registration.init_confirm';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const REGISTRATION_CONFIRM = 'user.registration.confirm';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const REGISTRATION_CONFIRMED = 'user.registration.confirmed';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const RESETTING_RESET_REQUEST = 'user.resetting.reset.request';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const RESETTING_RESET_INITIALIZE = 'user.resetting.reset.initialize';

    /**
     * @Event("HT\UserBundle\Event\FormEvent ")
     */
    const RESETTING_RESET_SUCCESS = 'user.resetting.reset.success';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const RESETTING_RESET_COMPLETED = 'user.resetting.reset.completed';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const SECURITY_IMPLICIT_LOGIN = 'user.security.implicit_login';

    /**
     * @Event("HT\UserBundle\Event\UserNullableEvent")
     */
    const RESETTING_SEND_EMAIL_INITIALIZE = 'user.resetting.send_email.initialize';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const RESETTING_SEND_EMAIL_CONFIRM = 'user.resetting.send_email.confirm';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const RESETTING_SEND_EMAIL_COMPLETED = 'user.resetting.send_email.completed';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const USER_CREATED = 'user.user.created';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const USER_PASSWORD_CHANGED = 'user.user.password_changed';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const USER_ACTIVATED = 'user.user.activated';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const USER_DEACTIVATED = 'user.user.deactivated';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const USER_PROMOTED = 'user.user.promoted';

    /**
     * @Event("HT\UserBundle\Event\UserEvent")
     */
    const USER_DEMOTED = 'user.user.demoted';
}
