# ramverk1-project

[![Build Status](https://circleci.com/gh/Toddez/ramverk1.svg?style=svg)](https://circleci.com/gh/Toddez/ramverk1)

[![Maintainability](https://api.codeclimate.com/v1/badges/ccd9790c234c8418d729/maintainability)](https://codeclimate.com/github/Toddez/ramverk1-project/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/ccd9790c234c8418d729/test_coverage)](https://codeclimate.com/github/Toddez/ramverk1-project/test_coverage)

Project for the course *ramverk1* at *Blekinge Tekniska Högskola*.

## Installation

Clone  
``git clone git@github.com:Toddez/ramverk1-project.git``

Install dependencies  
``make install``

## Setup database

Create tables  
``sqlite3 data/db.sqlite < sql/ddl/db_sqlite.sql``

Set permissions  
``chmod 666 data/db.sqlite``  

May require ``data/`` to have write perms as well  
``chmod a+w data/``

## Testing

Create tables  
``sqlite3 data/test_db.sqlite < sql/ddl/db_sqlite.sql``

Set permissions  
``chmod 666 data/test_db.sqlite``  

May require ``data/`` to have write perms as well  
``chmod a+w data/``

Run entire test suite  
``make test``

Run only unit tests  
``make phpunit``
