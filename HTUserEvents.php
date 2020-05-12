<?php

namespace App\UserBundle;

final class HTUserEvents
{
	/**
	 * The CHANGE_PASSWORD_INITIALIZE event occurs when the change password process is initialized.
	 *
	 * This event allows you to modify the default values of the user before binding the form.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const CHANGE_PASSWORD_INITIALIZE = 'user.change_password.edit.initialize';

	/**
	 * The CHANGE_PASSWORD_SUCCESS event occurs when the change password form is submitted successfully.
	 *
	 * This event allows you to set the response instead of using the default one.
	 *
	 * @Event("App\UserBundle\Event\FormEvent")
	 */
	const CHANGE_PASSWORD_SUCCESS = 'user.change_password.edit.success';

	/**
	 * The CHANGE_PASSWORD_COMPLETED event occurs after saving the user in the change password process.
	 *
	 * This event allows you to access the response which will be sent.
	 *
	 * @Event("App\UserBundle\Event\FilterUserResponseEvent")
	 */
	const CHANGE_PASSWORD_COMPLETED = 'user.change_password.edit.completed';

	/**
	 * The GROUP_CREATE_INITIALIZE event occurs when the group creation process is initialized.
	 *
	 * This event allows you to modify the default values of the user before binding the form.
	 *
	 * @Event("App\UserBundle\Event\GroupEvent")
	 */
	const GROUP_CREATE_INITIALIZE = 'user.group.create.initialize';

	/**
	 * The GROUP_CREATE_SUCCESS event occurs when the group creation form is submitted successfully.
	 *
	 * This event allows you to set the response instead of using the default one.
	 *
	 * @Event("App\UserBundle\Event\FormEvent")
	 */
	const GROUP_CREATE_SUCCESS = 'user.group.create.success';

	/**
	 * The GROUP_CREATE_COMPLETED event occurs after saving the group in the group creation process.
	 *
	 * This event allows you to access the response which will be sent.
	 *
	 * @Event("App\UserBundle\Event\FilterGroupResponseEvent")
	 */
	const GROUP_CREATE_COMPLETED = 'user.group.create.completed';

	/**
	 * The GROUP_DELETE_COMPLETED event occurs after deleting the group.
	 *
	 * This event allows you to access the response which will be sent.
	 *
	 * @Event("App\UserBundle\Event\FilterGroupResponseEvent")
	 */
	const GROUP_DELETE_COMPLETED = 'user.group.delete.completed';

	/**
	 * The GROUP_EDIT_INITIALIZE event occurs when the group editing process is initialized.
	 *
	 * This event allows you to modify the default values of the user before binding the form.
	 *
	 * @Event("App\UserBundle\Event\GetResponseGroupEvent")
	 */
	const GROUP_EDIT_INITIALIZE = 'user.group.edit.initialize';

	/**
	 * The GROUP_EDIT_SUCCESS event occurs when the group edit form is submitted successfully.
	 *
	 * This event allows you to set the response instead of using the default one.
	 *
	 * @Event("App\UserBundle\Event\FormEvent")
	 */
	const GROUP_EDIT_SUCCESS = 'user.group.edit.success';

	/**
	 * The GROUP_EDIT_COMPLETED event occurs after saving the group in the group edit process.
	 *
	 * This event allows you to access the response which will be sent.
	 *
	 * @Event("App\UserBundle\Event\FilterGroupResponseEvent")
	 */
	const GROUP_EDIT_COMPLETED = 'user.group.edit.completed';

	/**
	 * The PROFILE_EDIT_INITIALIZE event occurs when the profile editing process is initialized.
	 *
	 * This event allows you to modify the default values of the user before binding the form.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const PROFILE_EDIT_INITIALIZE = 'user.profile.edit.initialize';

	/**
	 * The PROFILE_EDIT_SUCCESS event occurs when the profile edit form is submitted successfully.
	 *
	 * This event allows you to set the response instead of using the default one.
	 *
	 * @Event("App\UserBundle\Event\FormEvent")
	 */
	const PROFILE_EDIT_SUCCESS = 'user.profile.edit.success';

	/**
	 * The PROFILE_EDIT_COMPLETED event occurs after saving the user in the profile edit process.
	 *
	 * This event allows you to access the response which will be sent.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const PROFILE_EDIT_COMPLETED = 'user.profile.edit.completed';

	/**
	 * The REGISTRATION_INITIALIZE event occurs when the registration process is initialized.
	 *
	 * This event allows you to modify the default values of the user before binding the form.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const REGISTRATION_INITIALIZE = 'user.registration.initialize';

	/**
	 * The REGISTRATION_SUCCESS event occurs when the registration form is submitted successfully.
	 *
	 * This event allows you to set the response instead of using the default one.
	 *
	 * @Event("App\UserBundle\Event\FormEvent")
	 */
	const REGISTRATION_SUCCESS = 'user.registration.success';

	/**
	 * The REGISTRATION_FAILURE event occurs when the registration form is not valid.
	 *
	 * This event allows you to set the response instead of using the default one.
	 * The event listener method receives a App\UserBundle\Event\FormEvent instance.
	 *
	 * @Event("App\UserBundle\Event\FormEvent")
	 */
	const REGISTRATION_FAILURE = 'user.registration.failure';

	/**
	 * The REGISTRATION_COMPLETED event occurs after saving the user in the registration process.
	 *
	 * This event allows you to access the response which will be sent.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const REGISTRATION_COMPLETED = 'user.registration.completed';

	/**
	 * The REGISTRATION_CONFIRM event occurs just before confirming the account.
	 *
	 * This event allows you to access the user which will be confirmed.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const REGISTRATION_CONFIRM = 'user.registration.confirm';

	/**
	 * The REGISTRATION_CONFIRMED event occurs after confirming the account.
	 *
	 * This event allows you to access the response which will be sent.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const REGISTRATION_CONFIRMED = 'user.registration.confirmed';

	/**
	 * The RESETTING_RESET_REQUEST event occurs when a user requests a password reset of the account.
	 *
	 * This event allows you to check if a user is locked out before requesting a password.
	 * The event listener method receives a App\UserBundle\Event\UserEvent instance.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const RESETTING_RESET_REQUEST = 'user.resetting.reset.request';

	/**
	 * The RESETTING_RESET_INITIALIZE event occurs when the resetting process is initialized.
	 *
	 * This event allows you to set the response to bypass the processing.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const RESETTING_RESET_INITIALIZE = 'user.resetting.reset.initialize';

	/**
	 * The RESETTING_RESET_SUCCESS event occurs when the resetting form is submitted successfully.
	 *
	 * This event allows you to set the response instead of using the default one.
	 *
	 * @Event("App\UserBundle\Event\FormEvent ")
	 */
	const RESETTING_RESET_SUCCESS = 'user.resetting.reset.success';

	/**
	 * The RESETTING_RESET_COMPLETED event occurs after saving the user in the resetting process.
	 *
	 * This event allows you to access the response which will be sent.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const RESETTING_RESET_COMPLETED = 'user.resetting.reset.completed';

	/**
	 * The SECURITY_IMPLICIT_LOGIN event occurs when the user is logged in programmatically.
	 *
	 * This event allows you to access the response which will be sent.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const SECURITY_IMPLICIT_LOGIN = 'user.security.implicit_login';

	/**
	 * The RESETTING_SEND_EMAIL_INITIALIZE event occurs when the send email process is initialized.
	 *
	 * This event allows you to set the response to bypass the email confirmation processing.
	 * The event listener method receives a App\UserBundle\Event\GetResponseNullableUserEvent instance.
	 *
	 * @Event("App\UserBundle\Event\GetResponseNullableUserEvent")
	 */
	const RESETTING_SEND_EMAIL_INITIALIZE = 'user.resetting.send_email.initialize';

	/**
	 * The RESETTING_SEND_EMAIL_CONFIRM event occurs when all prerequisites to send email are
	 * confirmed and before the mail is sent.
	 *
	 * This event allows you to set the response to bypass the email sending.
	 * The event listener method receives a App\UserBundle\Event\UserEvent instance.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const RESETTING_SEND_EMAIL_CONFIRM = 'user.resetting.send_email.confirm';

	/**
	 * The RESETTING_SEND_EMAIL_COMPLETED event occurs after the email is sent.
	 *
	 * This event allows you to set the response to bypass the the redirection after the email is sent.
	 * The event listener method receives a App\UserBundle\Event\UserEvent instance.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const RESETTING_SEND_EMAIL_COMPLETED = 'user.resetting.send_email.completed';

	/**
	 * The USER_CREATED event occurs when the user is created with UserManipulator.
	 *
	 * This event allows you to access the created user and to add some behaviour after the creation.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const USER_CREATED = 'user.user.created';

	/**
	 * The USER_PASSWORD_CHANGED event occurs when the user is created with UserManipulator.
	 *
	 * This event allows you to access the created user and to add some behaviour after the password change.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const USER_PASSWORD_CHANGED = 'user.user.password_changed';

	/**
	 * The USER_ACTIVATED event occurs when the user is created with UserManipulator.
	 *
	 * This event allows you to access the activated user and to add some behaviour after the activation.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const USER_ACTIVATED = 'user.user.activated';

	/**
	 * The USER_DEACTIVATED event occurs when the user is created with UserManipulator.
	 *
	 * This event allows you to access the deactivated user and to add some behaviour after the deactivation.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const USER_DEACTIVATED = 'user.user.deactivated';

	/**
	 * The USER_PROMOTED event occurs when the user is created with UserManipulator.
	 *
	 * This event allows you to access the promoted user and to add some behaviour after the promotion.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const USER_PROMOTED = 'user.user.promoted';

	/**
	 * The USER_DEMOTED event occurs when the user is created with UserManipulator.
	 *
	 * This event allows you to access the demoted user and to add some behaviour after the demotion.
	 *
	 * @Event("App\UserBundle\Event\UserEvent")
	 */
	const USER_DEMOTED = 'user.user.demoted';
}
