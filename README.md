# phpAr

## About phpAr
phpAr is a simple PHP class to let you unarchive .ar files.

## Usage
> $archive = new phpAr(/* Name of your file */);

This is how to load a file.

> $archive->listfiles();

This will return an array with all the files inside the archive.

> $archive->getfile(/* File that you want to extract */);

This will return an object with a the informations and the content of the specified file.

## Licence
phpAr is released under CeCILL-C license.