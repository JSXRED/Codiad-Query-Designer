# Codiad Database Query Designer

This plugin allow designing database queries through Codiad user interface.
**At the moment only MySQL is supported.** You can manage more than one database connection, so multi-project usage is possible.

# Installation

- Download the zip file and extract it to your plugins folder
- Enable this plugin in the plugins manager in Codiad

# Usage

Manage your connections in `dbengine\common\connections.php`. There is simple json-configuration array with examples.
In the codiad user interface at the right sidebar you will find a link "Query Designer". Click on it and have fun. 
You can also keypress ALT+Q.

On the left side you will see the database objects - contains views,tables and columns with column type.

## Hotkeys
- STRG+Q  - Open Query Designer

##Example

####Codiad Database Query Designer (Screenshot)

![QueryDesigner](http://jsx.imgserver.eu/GitHub/codiad-query-designer.png "QueryDesigner")

# Future improvements

* support for MSSQL, Postgres, etc.
* combine database connection selection with user rights

# Friendly advice
don't be evil and have fun! :)