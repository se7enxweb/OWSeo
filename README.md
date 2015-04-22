Extension : OWSeo v1.0
Requires : eZ Publish 4.x.x (not tested on 3.X)
Author : Open Wide <http://www.openwide.fr>

# What is OWSeo?

OWSeo provides a datatype for Search Engine Optimisation.

It will provides you a datatype with fields :

* **Title** : for meta title
* **Description** : for meta description
* **Keywords** : for meta keywords

If some content exists in these fields, it will overwrite default value.
 
# Usage

1. Add an attribute with this datatype

2. Create global rules needed in **settings/owseo.ini**.
You can set global rules for a specific content class.

3. Add owseo into **AdditionalSiteDesignList** in site.ini, or overwrite **page_head.tpl**.

Enjoy !!

# Rules

You can use OWSeo variables like "SiteName", "PathString" or "ContentName" in your rules

* **SiteName** : get the name from the site.ini file
* **PathString** : get the path string with some other options : **PathStringMinDepth**, **PathStringMaxLevels**, **PathStringSeparator**
* **ContentName** : get the name of the content object

It's also possible to use attribute names like "{attribute_identifier}" in rules to create dynamic content.
You can use condition with "|" {attribute_identifier1|attribute_identifier2} : if a content is not defined, it will use the other field

So, your title can be like : *{ContentName} - {PathString} | {SiteName}*
Your "Meta description" can be like *{ContentName} - {PathString} {description|intro}*


License
-------

This program is free software; you can redistribute it and/or
modify it under the terms of version 2.0 of the GNU General
Public License as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

Read /LICENSE


Installation
------------

Read doc/INSTALL
