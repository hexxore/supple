# Matter 

Matter is a Data Abstract Layer, which aims to support different database backends including but not limited to SQL backends 
( through pdo ), filetypes ( xml, csv etc. ), and data streams. 

Resultsets implement the IteratorAgregators with a datastream of provided entity classes. 
! When no Entity is provided, the stdClass object will be used;
