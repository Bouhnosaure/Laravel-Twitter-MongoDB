## Quick implementation of Twitter's Stream API - Aggregations - MapReduce with laravel and MongoDB

all this stuff was made in 3.5 hours, the code is not clean, 

it's just for tests and experimentations

all code is not commented, just look the routes :

-  ```'/'``` is simili dashboard 
- ```'/keywords'``` is for looking keyword after a map reduce
- ```'/stream'``` is for collecting data from Stream API of twitter ( it's quick ~2800 tweets / 10 sec)
- ```'/mapreduce'``` it's for mapreduce all keywords in all tweets

so i put my logic in services classes.

don't forget to increase exec_time_limit in phpand install mongo.so or mongo.dll on windows

Voilaaa !

thanks to jenssegers/laravel-mongodb and makotokw/twient for awsome composer packages :o 


![alt tag](http://www.google.fr/url?source=imglanding&ct=img&q=http://www.exeterengineering.com/wp-content/uploads/2014/07/potatoes.jpg&sa=X&ei=HmlaVZekLcKuU8H8gbAC&ved=0CAkQ8wc&usg=AFQjCNFsD8ASgvb5yzQmpBYLUHZwZjduIw)

somes potatoes for you !
