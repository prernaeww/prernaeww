<?php

// Home
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});

Breadcrumbs::for('Boarddashboard', function ($trail) {
    $trail->push('Dashboard', route('board.dashboard'));
});

Breadcrumbs::for('Storedashboard', function ($trail) {
    $trail->push('Dashboard', route('store.dashboard'));
});

Breadcrumbs::for('users', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Users', route('admin.users.index'));
});

Breadcrumbs::for('edituser', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('User', route('admin.users.index'));
    $trail->push('Edit', route('admin.users.index'));
});

Breadcrumbs::for('aprofile', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Profile', route('admin.profile'));
});

/*Admin Board */
Breadcrumbs::for('board', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Board', route('admin.board.index'));
});

Breadcrumbs::for('addboard', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Board', route('admin.board.index'));
    $trail->push('Add', route('admin.board.create'));
});

Breadcrumbs::for('editboard', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Board', route('admin.board.index'));
    $trail->push('Edit', route('admin.board.edit',''));
});

Breadcrumbs::for('viewboard', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Board', route('admin.board.index'));
    $trail->push('View', route('admin.board.show',''));
});

Breadcrumbs::for('viewuser', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Users', route('admin.users.index'));
    $trail->push('View', route('admin.user.show',''));
});

/*Admin Orders */
Breadcrumbs::for('order', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Order', route('admin.orders.index'));
});
Breadcrumbs::for('vieworder', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Order', route('admin.orders.index'));
    $trail->push('view', route('admin.orders.show',''));
});

/*Admin Measurement */
Breadcrumbs::for('measurement', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Measurement', route('admin.measurement.index'));
});

Breadcrumbs::for('addmeasurement', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Measurement', route('admin.measurement.index'));
    $trail->push('Add', route('admin.measurement.create'));
});

Breadcrumbs::for('editmeasurement', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Measurement', route('admin.measurement.index'));
    $trail->push('Edit', route('admin.measurement.edit',''));
});

Breadcrumbs::for('viewmeasurement', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Measurement', route('admin.measurement.index'));
    $trail->push('View', route('admin.measurement.show',''));
});


/*Admin Inventory */
Breadcrumbs::for('inventory', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Inventory', route('admin.inventory.index'));
});

Breadcrumbs::for('addinventory', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Inventory', route('admin.inventory.index'));
    $trail->push('Add', route('admin.inventory.create'));
});

Breadcrumbs::for('editinventory', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Inventory', route('admin.inventory.index'));
    $trail->push('Edit', route('admin.inventory.edit',''));
});

Breadcrumbs::for('viewinventory', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Inventory', route('admin.inventory.index'));
    $trail->push('View', route('admin.inventory.show',''));
});


/*Admin Family */
Breadcrumbs::for('family', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Family', route('admin.family.index'));
});

Breadcrumbs::for('addfamily', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Family', route('admin.family.index'));
    $trail->push('Add', route('admin.family.create'));
});

Breadcrumbs::for('editfamily', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Family', route('admin.family.index'));
    $trail->push('Edit', route('admin.family.edit',''));
});

/*Admin Contactus */
Breadcrumbs::for('Contact Us', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Contact Us', route('admin.contactus.index'));
});

Breadcrumbs::for('viewcontactus', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Contact Us', route('admin.contactus.index'));
    $trail->push('View', route('admin.contactus.show',''));
});



/*Board Store */
Breadcrumbs::for('Boardstore', function ($trail) {
    $trail->parent('Boarddashboard');
    $trail->push('Store', route('board.store.index'));
});

Breadcrumbs::for('boardstore', function ($trail) {
    $trail->parent('Boarddashboard');
    $trail->push('Store', route('board.store.index'));
    $trail->push('AddBoard', route('board.store.create'));
});

Breadcrumbs::for('editBoardstore', function ($trail) {
    $trail->parent('Boarddashboard');
    $trail->push('Store', route('board.store.index'));
    $trail->push('EditBoard', route('board.store.edit',''));
});

Breadcrumbs::for('viewBoardstore', function ($trail) {
    $trail->parent('Boarddashboard');
    $trail->push('Store', route('board.store.index'));
    $trail->push('View', route('board.store.show',''));
});

/*View Board Banner*/
Breadcrumbs::for('BoardBanner', function ($trail) {
    $trail->parent('Boarddashboard');
    $trail->push('Banner', route('board.banner.index'));
});
Breadcrumbs::for('viewbanner', function ($trail) {
    $trail->parent('Boarddashboard');
    $trail->push('Banner', route('board.banner.index'));
    $trail->push('View', route('board.banner.index'));
    
});
/*View Board Banner*/


/*Board Order*/
Breadcrumbs::for('Board-order', function ($trail) {
    $trail->parent('Boarddashboard');
    $trail->push('Order', route('board.orders.index'));
});
Breadcrumbs::for('Board-view', function ($trail) {
    $trail->parent('Boarddashboard');
    $trail->push('Order', route('board.orders.index'));
    $trail->push('View', route('board.orders.index'));
    
});
/*Board Order*/

/* Store */
Breadcrumbs::for('store', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Store', route('admin.store.index'));
});

Breadcrumbs::for('addstore', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Store', route('admin.store.index'));
    $trail->push('Add', route('admin.store.create'));
});

Breadcrumbs::for('editstore', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Store', route('admin.store.index'));
    $trail->push('Edit', route('admin.store.edit',''));
});

Breadcrumbs::for('viewstore', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Store', route('admin.store.index'));
    $trail->push('View', route('admin.store.show',''));
});



/* School */
Breadcrumbs::for('school', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('School', route('admin.school.index'));
});

Breadcrumbs::for('addschool', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('School', route('admin.school.index'));
    $trail->push('Add', route('admin.school.create'));
});

Breadcrumbs::for('editschool', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('School', route('admin.school.index'));
    $trail->push('Edit', route('admin.school.edit',''));
});
Breadcrumbs::for('viewschool', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('School', route('admin.school.index'));
    $trail->push('View', route('admin.school.show',''));
});

Breadcrumbs::for('boarddashboard', function ($trail) {
    $trail->push('Dashboard', route('board.dashboard'));
});

Breadcrumbs::for('grade', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Grade', route('admin.grade.index'));
});

Breadcrumbs::for('addgrade', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Grade', route('admin.grade.index'));
    $trail->push('Add', route('admin.grade.create'));
});

Breadcrumbs::for('editgrade', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Grade', route('admin.grade.index'));
    $trail->push('Edit', route('admin.grade.edit',''));
});




Breadcrumbs::for('banner', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Banner', route('admin.banner.index'));
});

Breadcrumbs::for('addbanner', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Banner', route('admin.banner.index'));
    $trail->push('Add', route('admin.banner.create'));
});

Breadcrumbs::for('editbanner', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Banner', route('admin.banner.index'));
    $trail->push('Edit', route('admin.banner.edit',''));
});

Breadcrumbs::for('all', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('All', route('admin.order.all'));
});
Breadcrumbs::for('viewall', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('All', route('admin.order.all'));
    $trail->push('View', route('admin.order.all.view',''));
});

/* iSSUE */
Breadcrumbs::for('issue', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Issue', route('admin.issue.index'));
});
Breadcrumbs::for('reportedissue', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Reported Issue', route('admin.reported.issue'));
});

Breadcrumbs::for('addissue', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Issue', route('admin.issue.index'));
    $trail->push('Add', route('admin.issue.create'));
});

Breadcrumbs::for('editissue', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Issue', route('admin.issue.index'));
    $trail->push('Edit', route('admin.issue.edit',''));
});

/*Admin Category */

Breadcrumbs::for('admincategory', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Category', route('admin.category.index'));
});

Breadcrumbs::for('adminaddcategory', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Category', route('admin.category.index'));
    $trail->push('Add', route('admin.category.create'));
});


Breadcrumbs::for('admineditcategory', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Category', route('admin.category.index'));
    $trail->push('Edit', route('admin.category.create'));
});

Breadcrumbs::for('adminviewcategory', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Category', route('admin.category.index'));
    $trail->push('View', route('admin.category.show',''));
});

/*Board Category */

Breadcrumbs::for('category', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Category', route('board.category.index'));
});
Breadcrumbs::for('profile', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Profile', route('board.profile'));
});

Breadcrumbs::for('addcategory', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Category', route('board.category.index'));
    $trail->push('Add', route('board.category.create'));
});

Breadcrumbs::for('editcategory', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Category', route('board.category.index'));
    $trail->push('Edit', route('board.category.edit',''));
});
Breadcrumbs::for('viewcategory', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Category', route('board.category.index'));
    $trail->push('View', route('board.category.show',''));
});

/*Store Product */

Breadcrumbs::for('store-product', function ($trail) {
    $trail->parent('Storedashboard');
    $trail->push('Product', route('store.product.index'));
});

Breadcrumbs::for('productview', function ($trail) {
    $trail->parent('Storedashboard');
    $trail->push('Product', route('store.product.index'));
    $trail->push('view', route('store.product.show',''));
});


/*Store order */

Breadcrumbs::for('store-order', function ($trail) {
    $trail->parent('Storedashboard');
    $trail->push('Order', route('store.orders.index'));
});

Breadcrumbs::for('store-order-view', function ($trail) {
    $trail->parent('Storedashboard');
    $trail->push('Order', route('store.orders.index'));
    $trail->push('View', route('store.orders.show',''));
});


/*Admin Product */

Breadcrumbs::for('admin-product', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Product', route('admin.product.index'));
});

Breadcrumbs::for('admin-add-product', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Product', route('admin.product.index'));
    $trail->push('Add', route('admin.product.create'));
});

Breadcrumbs::for('admin-edit-product', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Product', route('admin.product.index'));
    $trail->push('Edit', route('admin.product.edit',''));
});
Breadcrumbs::for('admin-view-product', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Product', route('admin.product.index'));
    $trail->push('View', route('admin.product.show',''));
});

/*Admin Product */




/*Board Product */

Breadcrumbs::for('product', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Product', route('board.product.index'));
});

Breadcrumbs::for('addproduct', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Product', route('board.product.index'));
    $trail->push('Add', route('board.product.create'));
});

Breadcrumbs::for('editproduct', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Product', route('board.product.index'));
    $trail->push('Edit', route('board.product.edit',''));
});
Breadcrumbs::for('viewproduct', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Product', route('board.product.index'));
    $trail->push('View', route('board.product.show',''));
});
/* Meal */

Breadcrumbs::for('meal', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Meal', route('board.meal.index'));
});

Breadcrumbs::for('addmeal', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Meal', route('board.meal.index'));
    $trail->push('Add', route('board.meal.create'));
});

Breadcrumbs::for('editmeal', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Meal', route('board.meal.index'));
    $trail->push('Edit', route('board.meal.edit',''));
});
Breadcrumbs::for('viewmeal', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Meal', route('board.meal.index'));
    $trail->push('View', route('board.meal.show',''));
});
Breadcrumbs::for ('config', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('System Configuration', route('admin.system.config'));
});
Breadcrumbs::for ('app_config', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Application Configuration', route('admin.system.config'));
});

//orders
Breadcrumbs::for('inprocess', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Current', route('board.order.inprocess'));
});
Breadcrumbs::for('completed', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Completed', route('board.order.completed'));
});
Breadcrumbs::for('fail', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Fail', route('board.order.fail'));
});

Breadcrumbs::for('viewinprocess', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Current', route('board.order.inprocess'));
    $trail->push('View', route('board.order.inprocess.view',''));
});
Breadcrumbs::for('editinprocess', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Current', route('board.order.inprocess'));
    $trail->push('Edit', route('board.order.inprocess',''));
});
Breadcrumbs::for('viewcompleted', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Completed', route('board.order.completed'));
    $trail->push('View', route('board.order.completed.view',''));
});
Breadcrumbs::for('viewfail', function ($trail) {
    $trail->parent('boarddashboard');
    $trail->push('Fail', route('board.order.fail'));
    $trail->push('View', route('board.order.fail.view',''));
});
