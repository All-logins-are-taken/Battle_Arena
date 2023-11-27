```composer install```

and 

```php ./bin/console app:game:play```

___
***Behavioral*** *(which committed to help communicate and assign responsibilities between objects) design pattern* ***STRATEGY*** 
*(allows us rewrite part of the class outside one)*

"To differentiate an attack (also I added the same for the Armor) of the Character without adding conditions in the perform attack method 
and without creating subclasses with necessity of overriding full logic we can create interface to encapsulate an attack logic and give us ability 
add as much as we like without overwhelming Character's code"
 - Creating describing logic Interface with perform attack method
 - Creating classes implementing this Interface with any logic we want
 - Using them in Character class
___
***Creational*** *(instantiating objects) design patterns* ***BUILDER FACTORY*** *(allows us to create different types and representatives of an object)*

"As we have different Characters it has sense to move logic outside and give freedom to apply any pack of abilities/properties to them"
- Again we create interface. To give our builders set of attributes of our Character object to not finding out every time which methods should be but choose implementation from the list
- Create builders for every type of Character
- To build any type of Character we create Factory and implement it 
- As our factory is a service we can autowire it whenever we need and start build characters. Factory will call builder to create different types of Characters
___
***Structural*** *(organizing relationships between objects)* ***OBSERVER*** *(allows us to be subscribed on event)*

"When we need to listen some event we can use observing Interface that will subscribe on certain logic of subject 
and when it will happen all objects that implement Interface get known about"
- Create Interface which will be implemented by objects. In our case we want to know when battle is finished to add experience logic and output it within summary data.
- We pass to our subject observer as the parameter
- Create subscriber, also unsubscriber which is optional and we don't use it. Both have observer Interface as parameter
- Create notifier to trigger array of objects those implementing observer Interface

___________________________________________***PUBLISHER/SUBSCRIBER*** *(it makes the same as OBSERVER)*

"Here is the difference only in additional separated class Publisher, EVENT, which notifying Subscriber. In this implementation we subscribed on the event when battle is starting"
- All the same in approach of observing logic instead of Publisher now we create special object in Event folder

___________________________________________***DECORATOR*** *(allows us to replace class with our implementation, do whatever we need and call original method)*

"We want add something to logic that already exists. We know that it is an implementation then we can create our Interface that will implement it  
and add our logic to implementation without any changes in original"
- Creating our Interface as Service in named folder and named method that we want to extend in other class
- Creating class that implements Interface from above
- And type-hinting our Interface in constructor of the goal class that implementing his own Interface to have injection possible  