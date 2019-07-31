# Changelog

## V1.1.0
- Moved source code to dedicated `src/` folder.
- Added default `docs/` folder.
- Renamed `.docker/` to `docker/`.

## V1.0.1
- Front-end routes receive the password reset and invitation token in the url (ex: `/invitation/accept/{token}` and `/password/reset/{token}`).
- Invitations and password resets gave away if an email was used in the application, this has been fixed.
- Invitations and password resets now give a status `400` instead of a `422` as a `422` is expected to be a proper validation error response. 

## V1.0.0
- `laravel/passport` (OAuth2) for authentication.
- User crud.
- User invitation (when storing a new user).
- Password resets.
- LocaleSelector middleware (reads the locale from the `Accept-Language` header).
