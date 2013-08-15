## Fatty

In short, Fatty is a DCI (Data, Context, and Interaction) implementation for PHP. It provides the ability to extend objects at runtime so as to minimize object instantiation and load, only providing the necessary methods when required. It is mostly beneficial for objects that can get quite large, thereby providing a method of breaking down classes into smaller bite-size pieces.

### Why

Projects as they grow tend have classes that grow into monolithic objects. This can slow down object instantiation (though the performance benefit of using the DCI pattern is questionable), but really the main benefit is the improved code management across these objects. Instead of having a single object with hundreds, or even thousands of lines of code - you simply provide all the required functionality for the object to work in its basic form (validation rules, relationships.etc.) and provide extended functionality in what are known as decorators (think of it in the sense that you're decorating an object with another object's code).

### Requirements

* PHP 5.4+

### Installation

Fatty can be installed via composer, simply by adding the following line to your composer.json:

    {
        "require": {
            "kirkbushell/fatty", "*"
        }
    }

and then run

    composer install

This will install Fatty to the relevant vendor folder and make it available for your classes. Now, let's get to the meat of it - what exactly is/can Fatty do?

Let's take concept of a model as our use-case. Specifically, let's work with a User model. Users can have all sorts of crazy functionality tied to them, because most of our applications that we build are focused on users, but Fatty can be used for any class or object.

    class User extends \Eloquent {
        use \KirkBushell\Fatty\Context;

        public function name() {
            echo $this->first_name . ' ' . $this->last_name;
        }
    }

Pretty simple, right? We've setup a User class that has a basic method called name(). Because we're extending Eloquent, it's returning a string that aggregates the first_name and last_name fields together. We can safely assume that all users will have a first and last name, and so will probably need this method.

Now, what if our User class can represent many different types of users? Perhaps the user could be a customer, or a salesperson, or maybe a farmhand. These three different user types represent possibly very different functionality. For example, I can't imagine a customer wanting to plow a field, or a salesman wanting to purchase products. So, what we'll do, is extend the User object when necessary to provide this functionality.

This provides two main benefits:

1. It ensures that the User class is lighter, meaning the object can be instantiated faster and
2. It breaks up our user class into smaller chunks, ensuring code is easier to read and manage.

So how do we do this? Easy. Let's just take one of those examples, the farm hand that we mentioned before - and give that user type a new method called plow.

    class Farmhand {
        public function plow() {
            return function() {
                echo 'Mr Plow, I\'m Mr Plow!';
            };
        }
    }

By itself, this class will do nothing - but we can give it purpose. Let's fetch that user object.

    $user = User::find(1);
    $user->name(); // Kirk Bushell

    $user->extend( 'Farmhand' );
    $user->plow(); // Mr plow, I'm Mr Plow!

Woaaaahhh... That's neat! We've somehow accessed the methods of Farmhand! This is super powerful. We've now got the ability to separate huge classes into objects that make more sense when certain functionality is required. But why is plow() returning an anonymous function?

### PHP 5.3, 5.4 - and beyond!

PHP 5.3 introduced some really cool features - one of them being the ability to define anonymous functions. These are functions that get defined when and as we need them. No more ugly create_function() business! However, despite how good this feature was - creating anonymous functions that could access methods and properties from within an object was difficult, and it had its limitations - such as not being able to access private or protected methods of a class its defined on. Say hello to closure object binding in PHP 5.4. What this allows us to do is bind an anonymous function to an object context, in this case - our User object - and allow it to access all the functionality therein. How do we do that?

Let's extend our Farmhand example above and give it a new method that will access user methods!

    public function extendedName() {
        return function() {
            echo $this->name();
            echo . ' the Third';
        }
    }

Fatty when it has an anonymous function, binds that anonymous function to the User object scope - allowing the anonymous function access to the $this keyword, and thereby all the methods and functionality that User has.

### Working with arguments

We haven't yet touched on arguments. How do we work with them? Easy. Just ensure you define the arguments on the anonymous function you declare.

    public function foo() {
        return function( $bar ) {
            echo $bar;
        };
    }

Then when we call the method:

    $user->foo( 'bar' ); // 'bar'

It's really that simple!

### Laravel 3
Fatty was originally built specifically with Laravel 3 in mind, but now is a more general-purpose library. For Laravel 3 projects you can still use it if you prefer the bundle arrangement, otherwise some manual work will be required on the new master branch. If you'd like to see the docs for Laravel 3, please checkout the "old" branch and go from there.
