# Changelog

## V1.0.1
- Front-end routes receive the password reset and invitation token in the url (ex: `/invitation/accept/{token}` and `/password/reset/{token}`).
- Invitations and password resets gave away if an email was used in the application, this has been fixed.

## V1.0.0
- `laravel/passport` (OAuth2) for authentication.
- User crud.
- User invitation (when storing a new user).
- Password resets.
- LocaleSelector middleware (reads the locale from the `Accept-Language` header).
