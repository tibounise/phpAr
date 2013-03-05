# phpAr

## About phpAr
phpAr is a simple PHP class to let you unarchive .ar files.

## Usage
> $archive = new phpAr(/* Name of your file */);

This is how to load a file.

> $archive->listfiles();

This will return an array with all the files inside the archive. If no files were found, will return false.

> $archive->getfile(/* File that you want to extract (string) */);

This will return an object with a the informations and the content of the specified file. If phpAr can't find the file, will return false.

## Licence
phpAr is released under CeCILL-C license.