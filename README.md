Extension : OWSeo v1.0
Requires : eZ Publish 4.x.x (not tested on 3.X)
Author : Open Wide <http://www.openwide.fr>

What is OWSeo?
-------------------

OWSeo provides a datatype for Search Engine Optimisation.

It will provides you a datatype with fields :
- Title : for HTML tag <title></title>
- Description : for meta description
- Keywords : for meta keywords

If some content exists in these fields, it will overwrite default value.
 
Usage
------
1. Add an attribute with this datatype

2. Create global rules needed in settings/owseo.ini
You can set global rules for a specifi content class.
It's also possible to use attribute names like "[[<attribute_identifier>]]" in rules to create dynamic content.

3. Add owseo into AdditionalSiteDesignList in site.ini, or overwrite page_head.tpl.

Enjoy !!


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
