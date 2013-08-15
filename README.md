Fatty
=====
In short, Fatty is a DCI (Data, Context, and Interaction) implementation for the very cool Laravel PHP framework. It provides the ability to extend model objects at runtime so as to minimize object instantiation. It is mostly beneficial for big, fat model. Why would we need something like this?

Why
---
Projects as they grow, tend to start providing very large models. This can slow down object instantiation (though the performance benefit of using the DCI pattern is questionable), but really the main benefit is the improved code management across these models. Instead of having a single model with hundreds, or even thousands of lines of code - you simply provide all the required functionality for the model to work in its basic form (validation rules, relationships.etc.) and provide extended functionality in what are known as Decorators.

Installation
------------
Usual bundle install does the trick:

    php artisan bundle:install fatty

Then, update your relevant files in your applicatioa, starting with bundles.php in your application directory, by adding:

    'fatty' => array('auto' => true)

to the bundles array. Additionally, if you wish for plug'n' play status (ie. by not having to update your model extensions), modify the following line in config/application.php:

    'Eloquent'   => 'Laravel\\Database\\Eloquent\\Model',

to:

    'Eloquent'   => 'Fatty\\Model',

This ensures that all your models can still extend "eloquent", but will in fact be extending Fatty's model implementation, which extends the native Eloquent model class anyway.

Last, but not least - you need to create a "decorators" folder inside your models directory. Inside this, you will have decorators for each model that you wish to create.

Usage
-----
From here on out, I will assume that you've updated your aliases, but if you haven't, replace all Eloquent extensions with \Fatty\Model.

Consider we have a user model:

    class User extends Eloquent {}

Let's assume for argument's sake that said User may take on the role of Customer at some points, and as a result there are a few methods that mainly represent the role of Customer. Let's say, we need a customer_name method. Usually, you would add a public method called customer_name, which probably returns the first name and last name of the User. A trivial example, but bear with me. The idea of DCI is to split your models up that represent roles a given object may need to undertake at certain points during execution.

Here we create a new file in decorators/user/customer.php. Let's give it the following example:

    <?php
    return array(
        'customer_name' => function() use ($model) {
            return $model->first_name . ' ' . $model->last_name;
	}
    );

This array represents a grouping of methods that will be applied to the object. There are couple of things to note here: first, the array index "customer_name" is the name of the method that will be created. Secondly - note the use of (use ($model)). This is a very important piece, as it provides access to the object in question, to the function. Because of how closures work, and the context in they are appended to the object - $this is never actually available. So, be wary as this means that private and protected properties and methods on the object are not ever available to your Decorator methods. It also means, that if you need to deal with fellow decorator methods, you need to use the $model variable, not $this.

Next, let's work with them!

    $user = new User;
    $user->first_name = 'Kirk';
    $user->last_name = 'Bushell';
    
    // let's involved our customer methods now!
    $user->extend('customer');
    
    echo $user->customer_name(); // returns Kirk Bushell

Again, this is a very trivial example, but hopefully you can see the benefit when dealing with fat models!
