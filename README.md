# CakePHP Ticketing System


A simple ticketing system build with [CakePHP](http://cakephp.org) 3.x.

## Installation

[Read the installation doc.](./docs/install.md) (Currently untested.)

After the steps outlined above you should be able to run the server with `bin/cake server`

## Configuration

You can make your first admin account by adding `return true` at the beginning of AppControllers isAuthorized function. Be sure to remove this after you made your admin account however.

Be sure to make some tags before you start making projects, projects will not show up in the projects index if their tag attribute is null.

## Known issues.

The tickets index is not filtering results based on user level in project.

Unchecking the default values when making a new ticket or comment leads to trouble, there is a solution upcoming.
