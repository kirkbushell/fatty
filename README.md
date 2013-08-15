## Fatty

In short, Fatty is a DCI (Data, Context, and Interaction) implementation for PHP. It provides the ability to extend objects at runtime so as to minimize object instantiation and load, only providing the necessary methods when required. It is mostly beneficial for objects that can get quite large, thereby providing a method of breaking down classes into smaller bite-size pieces.

### Why

Projects as they grow tend have classes that grow into monolithic objects. This can slow down object instantiation (though the performance benefit of using the DCI pattern is questionable), but really the main benefit is the improved code management across these objects. Instead of having a single object with hundreds, or even thousands of lines of code - you simply provide all the required functionality for the object to work in its basic form (validation rules, relationships.etc.) and provide extended functionality in what are known as decorators (think of it in the sense that you're decorating an object with another object's code).

### How

Fatty can be installed via composer, simply by adding the following line to your repository:



### Laravel 3
Fatty was originally built specifically with Laravel 3 in mind, but now is a more general-purpose library. For Laravel 3 projects you can still use it if you prefer the bundle arrangement, otherwise some manual work will be required on the new master branch. If you'd like to see the docs for Laravel 3, please checkout the "old" branch and go from there.
