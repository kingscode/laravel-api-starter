# Docs

### POST: auth/dispense

##### Request

```
{
    redirect_uri: [sometimes, string],
    email:        [required, string, email],
    token:        [required, string],
}
```

##### Response

```
//
```

### POST: auth/login

##### Request

```
{
    email   : [required, string, email:rfc,dns],
    password: [required, string],
}
```

##### Response

```
{
    token: string,
}
```

### POST: auth/logout

##### Request

```
//
```

##### Response

```
//
```

### GET: docs/{doc?}

##### Request

```
//
```

##### Response

```
//
```

### POST: invitation/accept

##### Request

```
{
    token   : [required, string],
    email   : [required, string, email:rfc,dns,],
    password: [required, string, min:10, confirmed],
}
```

##### Response

```
{
    message: string,
}
```

### POST: invitation/resend

##### Request

```
{
    email: [required, string, email:rfc,dns],
}
```

##### Response

```
//
```

### POST: password/forgotten

##### Request

```
{
    email: [required, string, email:rfc,dns],
}
```

##### Response

```
//
```

### POST: password/reset

##### Request

```
{
    token   : [required, string],
    email   : [required, string, email:rfc,dns],
    password: [required, string, min:10, confirmed],
}
```

##### Response

```
{
    message: string,
}
```

### GET: profile

##### Request

```
//
```

##### Response

```
{
    id: string,
    name: string,
    email: string,
}
```

### PUT: profile

##### Request

```
{
    name: [required, string],
}
```

##### Response

```
//
```

### PUT: profile/email

##### Request

```
{
    email: [required, string, email:rfc,dns],
}
```

##### Response

```
//
```

### POST: profile/email/verify

##### Request

```
{
    email: [required, string, email:rfc,dns],
    token: [required, string],
}
```

##### Response

```
//
```

### PUT: profile/password

##### Request

```
{
    password        : [required, string, min:10, confirmed],
    current_password: [required, string],
}
```

##### Response

```
//
```

### POST: registration

##### Request

```
{
    name : [required, string],
    email: [required, string, email:rfc,dns],
}
```

##### Response

```
//
```

### POST: registration/verify

##### Request

```
{
    email   : [required, string, email:rfc,dns,],
    token   : [required, string],
    password: [required, string, min:10, confirmed],
}
```

##### Response

```
//
```

### POST: user

##### Request

```
{
    name : [required, string],
    email: [required, string, email:rfc,dns],
}
```

##### Response

```
//
```

### GET: user

##### Request

```
//
```

##### Response

```
{
    id:    string
    name:  string
    email: string
}
```

### DELETE: user/{user}

##### Request

```
//
```

##### Response

```
//
```

### PUT: user/{user}

##### Request

```
{
    name : [required, string],
    email: [required, string, email:rfc,dns],
}
```

##### Response

```
//
```

### GET: user/{user}

##### Request

```
//
```

##### Response

```
{
    id:    string
    name:  string
    email: string
}
```

