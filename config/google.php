<?php

return [
    "type" => "service_account",
    "project_id" => "spodial-market-statistic",
    "private_key_id" => "85e5a0046db745d809af6f808539568cd34d4b4c",
    "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCoRdeCVWHm6xUp\nNK7Ph49Iw80fmykuXOIsuVsdJg+F4uL/iwbVN8BOEIQNnusmnyeoWTlF9nr+Awsj\nw7NADVhd+yFX/og043/t4MWUCHz07Jb1Qto+2RO8aqkvh/Fe0OdOJLJMD/5+6/a4\n9ag70dqUf6zhfLMTLvgsR1N44l+n2Xp6qUr5FWHd1aDi/87RbUmc2POD22GRwDIm\nf6llokcLfGWY+7nDHaLK/bcIbLuR4Nxml4jAZDBM9m1HX0NY1r39AK0TZu7ENzn2\nLNBRwQdx8mZgMUBVK17CA00c3jRZxqY+9MUci2THW6wapS+ge9kBWfFW+8q0PoBG\n+P8vJM3dAgMBAAECggEACMEVbKTngn1xbOLeQ1KwnB7/oWjPlCdl5uC/wjz0Ksl6\neMPxV49edFE8pX7CihBWnlYB+kdP774VyscIIMlw8va5LdxClsnNIoGCleh5I77Z\nPao8M2/UrIU6BwyB/KZOmkyjb/yXB2BcRhQVrAMHZZlc06BKqrFTXO5bA9t3EYLT\n2N4ZpEqrld+HtC5daMhKul2Us/iHia4DCb9mhL5wmnzZSN8pHwcE+1EJOcWVfuHV\nXMx3Reauk9bDmz9GoT4dotYhTHEjs7wCQEDIGvb5sl1o2YFU5J2M6AWOHKxNWG3p\nSTruZsSuIsTWTZ5UhOCkigCVQUojg2/ZTXk8LOqeYQKBgQDSbAhpV9+0VVbXbCyM\nilLgzYXrtOneMDozrWGSx8Tdsvmw0AGVrKfj5Q7Vck5jqof/tU4AFJmS5ghrfsb4\n7ztv2YjqPXlfC0xClMSSEyEeptt8JUm7wR/kt0wqey4m8bNPwMltyf3951bfFTLL\nxLqslrmO0tCKW77pFiQKVsXaMQKBgQDMuJ+REoaqYFqgyka7VxS9YWpNLrItzoEs\nGtqRSymiMKT2NFoPzQRUhBjQB/XtjJ7cHpXaCO/buHIVmuCbKhEfI6xPWb4/WqI1\n8ZEP5itULQdudcHCE4rRb0GUquRY4u6Hxmvw97G2WwvkE1PSB3EG+eDTTXDBtdar\noIRXm3SXbQKBgQCIBQW68u7Mp42nW2nAv3mrj69OGnhDBHHbDezQ8Qm1Nghp30vm\n8ODmVB8uouFBU+7twMlXC/nqF4DB3AXKzPpi5v+2S161rZvyPjCDLJJwOfQPYBs1\nnzV6p3I407+VtQ/wMfMDYVsqUey57/4R8m2pOxVf2a6sgXn7OjC57jWfQQKBgA8z\nPhXFbF5GHTSHEE1kn9OX4g6tAunHxWuC6uuSyxqRxg3JwU4fCM1FqZn4nVfv8vOh\nCbtQoo6L2VylTTv4GaFYQrj5jtihYB3lO8IUcu8jEMQw1hwU21/FhPcG4UGAR/mo\nz+bqXTWO+QkMLlMP3mKINxs5Sr/3QmMn3eurTTLZAoGBAIDncBCYOcKNYwMvJQt9\ncrzO3Bovo9b+naciALGQXPQtd2XYfVoD6DUlRiTz2JOZVz5KbZB+1OE5pjAPR+m2\nd8nqWBUA8ynkEGow/TZrO29cX96WeWKgf0ChDM6KvjllNqealU6wDxRwakwfIkB4\nGZKbnj+iRf94T1yCtneuJP1k\n-----END PRIVATE KEY-----\n",
    "client_email" => "spodial@spodial-market-statistic.iam.gserviceaccount.com",
    "client_id" => "117107838309340864710",
    "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
    "token_uri" => "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
    "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/spodial%40spodial-market-statistic.iam.gserviceaccount.com",
    "universe_domain" => "googleapis.com",
    "exportShops" => env('GOOGLE_TABLE_EXPORT', 'OFF'),
    "sheetId" => env('GOOGLE_TABLE_SHEET_ID', ''),
    "pageName" => env('GOOGLE_TABLE_PAGE_NAME', ''),
];